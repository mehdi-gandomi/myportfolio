<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 9/6/2018
 * Time: 6:22 PM
 */
namespace App\Controllers;
use App\Config;
use App\Models\LoginModel;
use Core\Controller;
use Core\View;

class DashboardLogin extends Controller {

  protected function before()
    {

    }
    
    public function indexAction(){
        if ($_SERVER['REQUEST_METHOD']==="GET"){
            View::renderMustache("dashboard/login",['base_url'=>Config::BASE_URL]);
        }else{
            try{
                if(LoginModel::auth($_POST['username'],$_POST['password'])){
                    self::redirect_to(Config::BASE_URL."/dashboard");
                }

            }catch (\Exception $e){
                View::renderMustache("dashboard/login",['base_url'=>Config::BASE_URL,'error'=>$e->getMessage()]);
            }

        }
    }

    
      protected function after()
    {

    }
}