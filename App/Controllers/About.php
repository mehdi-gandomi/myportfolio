<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/11/2018
 * Time: 10:13 AM
 */
namespace App\Controllers;
use App\Config;
use App\Models\AboutModel;
use ArrayIterator;
use Core\View;
use Core\Controller;
use App\Models\DashboardModel;
class About extends  Controller{

  protected function before()
    {

    }
    
    public function indexAction(){
        $bio=AboutModel::get_bio();
        $jobFields=AboutModel::get_user_job_fields($bio['id']);
        if (isset($_COOKIE['avatar_path'])){
            $image_path=$_COOKIE['avatar_path'];
        }else{
            $personal_info=DashboardModel::get_all_personal_info();
            setcookie("avatar_path","public/images/".$personal_info['avatar']);
            $image_path="public/images/".$personal_info['avatar'];

        }
        $skills=DashboardModel::get_skills();
        $chunkedArr=array_chunk($skills,6);
        if (isset($chunkedArr[2])){
            $skills1=$chunkedArr[0];
            array_push($skills1,...$chunkedArr[2]);

        }else{
            $skills1=$chunkedArr[0];
        }
        $skills2=array_chunk($skills,6)[1];
        $skills1=new ArrayIterator($skills1);
        $skills2=new ArrayIterator($skills2);
        View::renderMustache("about",array('title'=>"درباره من",'base_url'=>Config::BASE_URL,'skills1'=>$skills1,'skills2'=>$skills2,'image_path'=>$image_path,'bio'=>$bio['bio'],'jobFields'=>$jobFields));
    }
    
      protected function after()
    {

    }
}