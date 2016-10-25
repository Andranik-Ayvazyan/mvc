<?php
namespace core;


   abstract class Base
   {
       private   $queryBuilder;
       protected $table;
       protected $fillable = [];
       protected $property = [];

       public function __construct()
       {
           $this -> queryBuilder = QueryBuilder::getInstance();

       }


       public function __set($key, $value)
       {
           if(!isset($this->property[$key])) {

             $this -> property[$key] =  $value;

           }

           return $this->property;
       }


       public function __get($key)
       {
           if (isset($this->property[$key])) {

               return $this -> property[$key];
           }

       }



       public function save ()
       {
           $saveArray = [];

           // var_dump($this->property);

           foreach ($this -> property as $key => $value) {

              if (in_array($key,$this -> fillable)) {

                  $saveArray[$key] = $value;

              }

               
           }

               if (isset($saveArray['id'])) {

                   $id = $saveArray['id'];
                   array_shift($saveArray);

                   $this -> queryBuilder -> update($this->table,$saveArray) -> where([$this->property]) -> execute();

               }  else  {

                   $this -> queryBuilder -> insert($this->table,$saveArray)->execute();

               }

       }


       public function findAll ()
       {
 
         $this -> fillEmptyProperty() ;
         
         $result =   $this -> queryBuilder -> select('*')->optTable('test')->where([$this->property]) -> get();

           foreach ($result as $key) {

            foreach ($key as $item => $part) {

                echo "<br>" . $item . ' ' . $part . "<br>";

            }

         } 

       }



      public function find ()
       {
 
         $this -> fillEmptyProperty() ;

         $result =   $this -> queryBuilder -> select('*')->optTable('test')-> where([$this->property])  -> first();

          foreach ($result as $key => $val) {
             echo "<br>".$key.$val."<br>";
         }

       }



       public function delete () 
       {

          $this -> fillEmptyProperty() ;

          $this -> queryBuilder -> delete($this->table) -> where([$this->property])  -> execute();

       }


       public function fillEmptyProperty() 
       {

        if (empty($this->property)) {

          $this->property = ['column'=>'id','expression'=>'>', 'value'=>'0'] ; 

        }

       }

   }   