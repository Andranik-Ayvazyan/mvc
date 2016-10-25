<?php

 namespace models;

 use core\Base;

  class User extends Base
  {

       protected $table = 'test';
       protected $fillable = ['id','name','lastname','city','age'];
      

  }