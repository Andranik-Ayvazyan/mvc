<?php

     

     function  __autoload($className)
     {

        include $className.'.php';

     }

    spl_autoload_register('__autoload');
       
       use core\Router;
       use core\QueryBuilder;
       use controllers\PhotoController;
       use models\User ;

    $router =  new Router();
    $router -> start();
    $user =  new User();

   /*
    $user -> id   = 60;
    $user -> column = 'city';
    $user -> expression = '=';
    $user -> value = 'London';

    $user -> name = 'Jakob';
    $user -> age  = 25;
    $user -> city = 'Texas';
    $user -> lastname = 'Jakens';
    $user -> save(); 
    */


    /*
    $user -> column = 'city';
    $user -> expression = '=';
    $user -> value = 'London';
    $user -> find(); 
    */

/*
    $user -> column = 'id';
    $user -> expression = '=';
    $user -> value = '4';
    $user -> delete(); 
*/


    // $sqlObj-> select('city')->optTable('test')->where([['c'=>'id','ex'=>'>','v'=>'60']]) -> get();
    // $sqlObj ->insert('test',['name'=>'Anna','lastname'=>'Jameson','city'=>'Shanhay','age'=>'117'])->execute();
    // $sqlObj -> update('test',['name'=>'Elen','age'=>'55']) -> where([['c'=>'id','ex'=>'>','v'=>'60','con'=>'OR'],['c'=>'city','ex'=>'=','v'=>'Tokyo']]) -> execute();
    // $sqlObj -> delete('test') -> where([['c'=>'id','ex'=>'=','v'=>'55','con'=>'OR'],['c'=>'age','ex'=>'=','v'=>'100']]) -> execute();