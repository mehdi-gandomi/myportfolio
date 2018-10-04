<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/12/2018
 * Time: 2:19 PM
 */
namespace App\Controllers;
use App\Config;
use App\Models\ContactModel;
use App\Models\DashboardModel;
use Core\Controller;
use Core\View;


class Contact extends Controller {

  protected function before()
    {

    }
    
    public function indexAction(){
        if (isset($_COOKIE['avatar_path'])){
            $image_path=$_COOKIE['avatar_path'];
        }else{
            $personal_info=DashboardModel::get_all_personal_info();
            setcookie("avatar_path","public/images/".$personal_info['avatar']);
            $image_path="public/images/".$personal_info['avatar'];
        }
        $contact_info=ContactModel::get_contact_info();
        View::renderMustache("contact",array('title'=>"درباره من",'base_url'=>Config::BASE_URL,'image_path'=>$image_path,'email'=>$contact_info['email'],'phone'=>$contact_info['phone'],'address'=>$contact_info['address']));
    }
    
      protected function after()
    {

    }
}