<?php
namespace controllers;
class PhotoController {
    public function view ()
    {
        //echo "this is class Photo view action<br>";
    }
    protected function welcome()
    {
        echo "This is class Photo welcome_action";
    }
    public function user($arr)
    {
        $keys = array_keys($arr);

        echo "<br>This is class PhotoController and  action user <br>parameters:".$arr[$keys[0]]."<br>"."user-id : ".$arr[$keys[1]];
    }

    public function ret ($arr)
    {
        $keys = array_keys($arr);
        echo "This is class PhotoController and  action ret <br>parameters:".$arr[$keys[0]];
    }
}