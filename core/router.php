<?php

namespace core;



class Router {

   var $url;
   var $method ;
   var $routes;
   var $params;
   var $matchesItem;
   var $controller ;
   var $action;


    public function generate($url, $match)
    {
        $paramsKeys = [];

        foreach ($match as $item) {

            $paramsKeys[] = trim($item,'{}');

            $url = str_replace(
                $item, 
                sprintf('(?P<%s>[\w]+)', preg_replace('/\{|\}/', '', $item)),
                $url    
            );
        }

        return ['^' . $url . '$', $paramsKeys];
    }

    public function collect ($val)
    {
        preg_match_all('(\{[a-zA-Z][\w]+\})', $val['url'], $matches);

        if (count($matches[0])) {

            list($reg,$params) = $this->generate($val['url'], $matches[0]);

            $val['reg'] = $reg;
            $val['params'] = $params;
        }

        if (!isset($this->routes[$val['method']])) {
            $this->routes[$val['method']] = [];
        }

        $this->routes[$val['method']][] = $val;

    }

    public function __construct ()
    {
        $this->url = rtrim($_SERVER['REQUEST_URI'],'/');
        $this->method = isset($_REQUEST['_method']) ? $_REQUEST['_method'] : $_SERVER['REQUEST_METHOD'];
        $routes = require_once('controllers/routes.php');
        $this->routes = [];

        foreach ($routes as &$value) {

            $this->collect ($value);

        }

        $this->dispatch();

    }

    public function execute ()
    {

       $this->controller = explode('@',$this->matchesItem['action'])[0];
       $this->action = explode('@',$this->matchesItem['action'])[1];

    }


    public function dispatch ()
    {

        if (!isset($this->routes[$this->method])) {

            exit('wrong method!');

        } else {

            foreach ($this->routes[$this->method] as $item) {

                if (isset($item['reg'])) {

                    $pattern =  preg_replace('/\//','\/',$item['reg']);

                    preg_match("~$pattern~",$this->url,$matches);

                    if(!empty($matches)) {

                        if(!empty($item['params'])){

                            foreach ($item['params'] as $key => $val) {

                                $this->params[$val] = $matches[$val];

                            }
                        }

                      $this->matchesItem = $item;
                      $this->execute();

                    }

                } else {

                    if($this->url == $item['url']) {

                        $this->matchesItem = $item;
                        $this->execute();

                    }
                }
            }
        }
    }

    public function start()
    {

       if(!isset($this->controller)) {

           if($this->url == '') {

               $this->controller = 'PhotoController';

           } else {

               exit('page not found');

           }
       }

           if (class_exists("controllers\\$this->controller")) {

               $controllerName = "controllers\\$this->controller";
               $controllerObj = new $controllerName();

               if (!isset($this->action)) {

                   $this->action = 'view';

               }

               if (method_exists($controllerObj, $this->action)) {

                       $reflection = new \ReflectionMethod($controllerObj, $this->action);

                       if (!$reflection->isPublic()) {

                           exit('this action is not a public!');

                       } else {

                           $actionName  = $this->action;

                       if(isset($this->params)) {

                           return $controllerObj->$actionName($this->params);

                       } else {

                           return $controllerObj->$actionName();

                       }

                       }

               } else {

                   exit('Wrong action name!');

               }

           } else {

               exit('Wrong class name!');

           }
    }
}