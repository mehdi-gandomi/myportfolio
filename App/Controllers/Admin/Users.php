<?php
namespace App\Controllers\Admin;
class Users extends \Core\Controller{
    public function before()
    {
        echo "before";
    }
    public function welcomeAction(){
        echo "Welcome mehdi";
    }
}