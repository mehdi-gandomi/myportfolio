<?php
/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/23/2018
 * Time: 10:46 PM
 */

namespace App\Models;


use Core\Model;
use Core\resize;
use PDO;
class ProjectsModel extends Model
{
    public static function get_all_projects($page=1,$limit=1){
        if ($page==="all"){
            $sql="SELECT projects.id,projects.project_title,projects.project_image,projects.project_link,projects.project_description,GROUP_CONCAT(skills.skill_title) as project_skills FROM projects INNER JOIN skill_project ON projects.id=skill_project.project_id INNER JOIN skills ON skills.skill_id=skill_project.skill_id GROUP BY projects.id ";
        }else{
            $page_limit=($page-1)*$limit;
            $sql="SELECT projects.id,projects.project_title,projects.project_image,projects.project_link,projects.project_description,GROUP_CONCAT(skills.skill_title) as project_skills FROM projects INNER JOIN skill_project ON projects.id=skill_project.project_id INNER JOIN skills ON skills.skill_id=skill_project.skill_id GROUP BY projects.id LIMIT $page_limit,$limit";
        }

        $db=static::getDB();
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){
            return false;
        }
    }
    public static function newProject($projectData)
    {
        unset($projectData['MAX_FILE_SIZE']);
        if ($_FILES['project_image']['name']){
            unset($projectData['defaultPic']);
            $fileName=$_FILES["project_image"]["name"];
            $target_file = "public/images/projects/" . $fileName;
            if (!file_exists($target_file)){
                move_uploaded_file($_FILES['project_image']['tmp_name'], $target_file);
            }
            $projectData['project_image']=$fileName;
            try{
                $skills=$projectData['skill_ids'];
                unset($projectData['skill_ids']);
                $categories=$projectData['cat_ids'];
                unset($projectData['cat_ids']);
                $project_id=self::genCode("projects","id");
                $projectData['id']=$project_id;
                static::insert("projects",$projectData,true);
                static::insertProjectSkills($skills,$project_id);
                static::insertProjectCategories($categories,$project_id);
                if (!empty($_FILES['gallery-images']['name'][0])>=1){
                    static::insert_project_gallery_pics($_FILES['gallery-images'],$project_id);
                }
                return $project_id;
            }catch (\Exception $e){

                return false;
            }
        }else{
            $personal_info=$_POST;
            if (isset($projectData['defaultPic']) && $projectData['defaultPic']=="yes"){
                unset($projectData['defaultPic']);
                $personal_info['project_image']="/projects/default.jpg";
            }else{
                unset($projectData['defaultPic']);
                $personal_info['project_image']=null;
            }
            try{
                $skills=$projectData['skill_ids'];
                unset($projectData['skill_ids']);
                $categories=$projectData['cat_ids'];
                unset($projectData['cat_ids']);
                $project_id=self::genCode("projects","id");
                $projectData['id']=$project_id;
                static::insert("projects",$projectData);
                static::insertProjectSkills($skills,$project_id);
                static::insertProjectCategories($categories,$project_id);
                if (!empty($_FILES['gallery-images']['name'])){
                    static::insert_project_gallery_pics($_FILES['gallery-images'],$project_id);
                }
                return true;
            }catch (\Exception $e){

                return false;
            }
        }

    }
    public static function get_all_project_skill_categories(){
        $result=self::select("skills");
        return $result;
    }
    public static function get_project_by_id($id){
        try{
            return self::select("projects","*","id='$id'",true);
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_project_skills($id)
    {
        $db=self::getDB();
        $id=self::prepare_input($id);
        $sql="SELECT skills.skill_id,skills.skill_title,skills.color FROM skill_project INNER JOIN skills ON skills.skill_id=skill_project.skill_id WHERE skill_project.project_id='$id'";
        $res=$db->query($sql);
        if ($res->rowCount()>0){
            return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
    public static function get_skills_except_selectedfields($id){
        $db=self::getDB();
        $id=self::prepare_input($id);
        $sql="SELECT * FROM skills WHERE skill_id NOT IN(SELECT skill_id FROM skill_project WHERE skill_project.project_id='$id')";
        $res=$db->query($sql);
        if ($res->rowCount()>0){
            return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public static function update_project($id,$projectData)
    {
        unset($projectData['MAX_FILE_SIZE']);
        if ($_FILES['project_image']['name']){
            unset($projectData['defaultPic']);
            $fileName=$_FILES["project_image"]["name"];
            $target_file = "public/images/projects/" . $fileName;
            if (!file_exists($target_file)){
                move_uploaded_file($_FILES['project_image']['tmp_name'], $target_file);
            }
            $projectData['project_image']=$fileName;
            try{
                $skills=$projectData['skill_ids'];
                unset($projectData['skill_ids']);
                $categories=$projectData['cat_ids'];
                unset($projectData['cat_ids']);
                static::update("projects",$projectData,"id='$id'");
                static::delete_all_project_skills($id);
                static::delete_all_project_categories($id);
                self::insertProjectSkills($skills,$id);
                self::insertProjectCategories($categories,$id);
                if (!empty($_FILES['gallery-images']['name'][0])>=1){
                    static::delete_project_gallery_pics($id);
                    static::insert_project_gallery_pics($_FILES['gallery-images'],$id);
                }
                return true;
            }catch (\Exception $e){

                return false;
            }
        }else{

            if (isset($projectData['defaultPic']) && $projectData['defaultPic']=="yes"){
                unset($projectData['defaultPic']);
                $projectData['project_image']="/projects/default.jpg";
            }else{
                unset($projectData['defaultPic']);
            }
            try{
                $skills=$projectData['skill_ids'];
                unset($projectData['skill_ids']);
                $categories=$projectData['cat_ids'];
                unset($projectData['cat_ids']);
                static::update("projects",$projectData,"id='$id'");
                static::delete_all_project_skills($id);
                static::delete_all_project_categories($id);
                self::insertProjectSkills($skills,$id);
                self::insertProjectCategories($categories,$id);
                if (!empty($_FILES['gallery-images']['name'][0])>=1){
                    static::delete_project_gallery_pics($id);
                    static::insert_project_gallery_pics($_FILES['gallery-images'],$id);
                }
                return true;
            }catch (\Exception $e){

                return false;
            }
        }
    }
    protected static function delete_all_project_skills($project_id){
        try{
            self::delete("skill_project","project_id='$project_id'");
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function delete_project_by_id($project_id)
    {
        try{
            self::delete("projects","id='$project_id'");
            return true;
        }catch (\Exception $e){

            return false;
        }
    }

    public static function get_project_info_by_id($id){
        $id=self::prepare_input($id);
        $db=self::getDB();
        $sql="SELECT projects.id,projects.project_title,projects.project_image,projects.project_link,projects.location,projects.project_description,GROUP_CONCAT(skills.skill_title) as project_skills FROM projects INNER JOIN skill_project ON projects.id=skill_project.project_id INNER JOIN skills ON skills.skill_id=skill_project.skill_id  WHERE projects.id='$id' GROUP BY projects.id";

        try{
            $res=$db->query($sql);
            if ($res->rowCount()>0){
                return $res->fetch(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){

            return false;
        }
    }
    public static function check_if_project_has_thumb($id){
        try{
            $res=self::select("project_thumbs","*","project_id='$id'");
            if ($res){
                return true;
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }
    public static function get_all_categories()
    {
        try{
            return self::select("project_categories");
        }catch (\Exception $e){
            return false;
        }

    }

    private static function insertProjectSkills($skills,$project_id)
    {

        foreach ($skills as $skill){
            self::insert("skill_project",array(
                'project_id'=>$project_id,
                'skill_id'=>$skill
            ));
        }
    }

    private static function insertProjectCategories($categories, $project_id)
    {

        foreach ($categories as $category){
            self::insert("cat_project",array(
                'project_id'=>$project_id,
                'cat_id'=>$category
            ));
        }
    }

    private static function delete_all_project_categories($id)
    {
        $id=self::prepare_input($id);
        self::delete("cat_project","project_id='$id'");
    }

    public static function insert_project_gallery_pics($files, $project_id)
    {

        for ($i=0;$i<count($files['name']);$i++){
            $filename=pathinfo($files['name'][$i],PATHINFO_FILENAME);
            $ext=pathinfo($files['name'][$i],PATHINFO_EXTENSION);
            move_uploaded_file($files['tmp_name'][$i], "public/images/projects/gallery/".$files['name'][$i]);
            self::insert("project_thumbs",array(
                    'project_id'=>$project_id,
                    'thumb'=>$files['name'][$i]
            ));
            $resizeObj = new resize("public/images/projects/gallery/".$files['name'][$i]);
            $resizeObj -> resizeImage(400, 350,'exact');
            $resizeObj -> saveImage("public/images/projects/gallery/".$filename."-thumb.$ext", 100);
        }
        return true;


    }

    public static function get_project_gallery_by_id($id)
    {
        $id=self::prepare_input($id);
        $sql="SELECT projects.project_title,project_thumbs.thumb FROM project_thumbs INNER JOIN projects ON projects.id=project_thumbs.project_id WHERE projects.id='$id'";
        $db=self::getDB();
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_categories_except_selectedfields($id)
    {
        $db=self::getDB();
        $id=self::prepare_input($id);
        $sql="SELECT * FROM project_categories WHERE project_categories.id NOT IN(SELECT cat_id FROM cat_project WHERE cat_project.project_id='$id')";
        $res=$db->query($sql);
        if ($res->rowCount()>0){
            return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public static function get_project_categories($id)
    {
        $db=self::getDB();
        $id=self::prepare_input($id);
        $sql="SELECT project_categories.id,project_categories.cat_title,project_categories.cat_name FROM cat_project INNER JOIN project_categories ON project_categories.id=cat_project.cat_id WHERE cat_project.project_id='$id'";
        $res=$db->query($sql);
        if ($res->rowCount()>0){
            return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    private static function delete_project_gallery_pics($id)
    {
        try{
            self::delete("project_thumbs","project_id='$id'");
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function insertProjectComment($commentData)
    {
        try{
            static::insert("project_comments",$commentData);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_active_comments($id)
    {
        try{
            $res=self::select("project_comments","*","project_id='$id' AND state='1'");
            if ($res){
                return $res;
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_all_projects_comments($page=1,$limit=1)
    {
        $db=self::getDB();
        try{
            $page_limit=($page-1)*$limit;
//            $res=self::select("project_comments","project_comments.id AS comment_id,project_comments.username,project_comments.email,project_comments.message,IF(project_comments.state=1, 'نمایش', 'مخفی') AS state,IF(project_comments.state=1, 'مخفی', 'نمایش') AS changeBtnState,project_comments.reply_to,projects.id,projects.project_title","1",false,"INNER JOIN projects ON projects.id=project_comments.project_id LIMIT $page_limit");
            $sql="SELECT project_comments.id AS comment_id,project_comments.username,project_comments.email,project_comments.message,IF(project_comments.state=1, 'نمایش', 'مخفی') AS state,IF(project_comments.state=1, 'مخفی', 'نمایش') AS changeBtnState,project_comments.reply_to,projects.id,projects.project_title FROM project_comments INNER JOIN projects ON projects.id=project_comments.project_id LIMIT $page_limit,$limit";
            $res=$db->query($sql);
            if ($res->rowCount()>0){
                return $res->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_all_projects_comments_count()
    {
        try{
            return self::select("project_comments","id","1",false,"",true);
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_projects_count()
    {
        try{
            return self::select("projects","id","1",false,"",true);
        }catch (\Exception $e){
            return false;
        }
    }

}