<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/11/2018
 * Time: 10:13 AM
 */
namespace App\Controllers;
use App\Config;
use Core\View;
use Core\Controller;

class About extends  Controller{

  protected function before()
    {

    }
    
    public function indexAction(){
        View::renderMustache("about.html",array('title'=>"درباره من",'base_url'=>Config::BASE_URL));
    }
    
      protected function after()
    {

    }
}