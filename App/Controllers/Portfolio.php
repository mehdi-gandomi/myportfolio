<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/12/2018
 * Time: 1:49 PM
 */
namespace App\Controllers;
use App\Models\DashboardModel;
use App\Models\ProjectsModel;
use ArrayIterator;
use Core\View;
use App\Config;


class Portfolio extends \Core\Controller {

  protected function before()
    {

    }

    /**
     *
     */
    public function indexAction(){
        if (isset($_COOKIE['avatar_path'])){
            $image_path=$_COOKIE['avatar_path'];
        }else{
            $personal_info=DashboardModel::get_all_personal_info();
            setcookie("avatar_path","public/images/".$personal_info['avatar']);
            $image_path="public/images/".$personal_info['avatar'];
        }
        $projects=ProjectsModel::get_all_projects("all");
        $projects=new ArrayIterator($projects);
        View::renderMustache("work",array('title'=>"نمونه کارهای من",'base_url'=>Config::BASE_URL,'image_path'=>$image_path,'projects'=>$projects));
    }
    public function oneProjectAction(){
        if (isset($_COOKIE['avatar_path'])){
            $image_path=$_COOKIE['avatar_path'];
        }else{
            $personal_info=DashboardModel::get_all_personal_info();
            setcookie("avatar_path","public/images/".$personal_info['avatar']);
            $image_path="public/images/".$personal_info['avatar'];
        }
        $id=$this->route_params['id'];
        $project=ProjectsModel::get_project_info_by_id($id);
        $thumb_result=ProjectsModel::check_if_project_has_thumb($project['id']);
        $project_comments=ProjectsModel::get_active_comments($id);
       if ($thumb_result){

           $project_thumbnails=ProjectsModel::get_project_gallery_by_id($project['id']);
           for ($i=0;$i<count($project_thumbnails);$i++){
               $fileName=pathinfo($project_thumbnails[$i]['thumb'],PATHINFO_FILENAME);
               $ext=pathinfo($project_thumbnails[$i]['thumb'],PATHINFO_EXTENSION);
               $base_path="public/images/projects/gallery/";
               $project_thumbnails[$i]=array(
                   'full_img'=>  $base_path.$fileName.".$ext",
                   'thumb'=>$base_path.$fileName."-thumb.$ext"
               );
           }
           View::renderMustache("project",array(
               'base_url'=>Config::BASE_URL,
               'image_path'=>$image_path,
               'project_title'=>$project['project_title'],
               'project_image'=>$project['project_image'],
               'project_description'=>$project['project_description'],
               'project_skills'=>explode(",",$project['project_skills']),
               'project_link'=>$project['project_link'],
               'location'=>$project['location'],
               'project_galleries'=>$project_thumbnails,
               'gallery_title'=>"تصاویری از پروژه",
               'comments'=>$project_comments
           ));

       }else{
           View::renderMustache("project",array(
               'base_url'=>Config::BASE_URL,
               'image_path'=>$image_path,
               'project_title'=>$project['project_title'],
               'project_image'=>$project['project_image'],
               'project_description'=>$project['project_description'],
               'project_skills'=>explode(",",$project['project_skills']),
               'project_link'=>$project['project_link'],
               'location'=>$project['location'],
               'comments'=>$project_comments
           ));

       }


    }
    public function newCommentAction(){
        $res=ProjectsModel::insertProjectComment($_POST);
        if ($res){
            echo json_encode(
                array(
                    'status'=>'ok'
                )
            );
        }else{
            echo json_encode(
                array(
                    'status'=>'error'
                )
            );
        }
    }
    
      protected function after()
    {

    }
}