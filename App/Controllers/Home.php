<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/8/2018
 * Time: 2:05 PM
 */
namespace App\Controllers;
use Core\View;
use Core\Controller;
use App\Config;
use App\Models\DashboardModel;
class Home extends Controller {

  protected function before()
    {

    }
    
    public function indexAction(){
      $personal_info=DashboardModel::get_all_personal_info();
      if (isset($_COOKIE['avatar_path'])){
          $image_path=$_COOKIE['avatar_path'];
      }else{
          setcookie("avatar_path","public/images/".$personal_info['avatar']);
          $image_path="public/images/".$personal_info['avatar'];
      }
        View::renderMustache("index",[
            'title'=>'صفحه اصلی',
            'base_url'=>Config::BASE_URL,
            'avatar'=>$personal_info['avatar'],
            'fname'=>$personal_info['fname'] ? $personal_info['fname']:"مهدی",
            'lname'=>$personal_info['lname'] ? $personal_info['lname']:"گندمی",
            'job'=>$personal_info['job'],
            'instagram'=>$personal_info['instagram'],
            'telegram'=>$personal_info['telegram'],
            'linkedin'=>$personal_info['linkedin'],
            'github'=>$personal_info['github'],
            'profile'=>$personal_info['profile'] ? $personal_info['profile']:"../#",
            'pinterest'=>$personal_info['pinterest'] ? $personal_info['pinterest']:"../#",
            'twitter'=>$personal_info['twitter'] ? $personal_info['twitter']:"../#",
            'image_path'=>$image_path
        ]);

    }
    
      protected function after()
    {

    }
}