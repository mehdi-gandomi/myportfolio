<?php
namespace App\Models;
use PDO;
class Post extends \Core\Model
{
    public static function getAll($page=1,$limit=1){
        $db=static::getDB();
        if ($page==="all"){
            $sql="SELECT posts.post_id,posts.post_title,posts.post_slug,posts.post_thumbnail,posts.preview_text,posts.post_content,posts.created_at,posts.updated_at,posts.tags AS category_slugs FROM posts INNER JOIN cat_post ON posts.post_id=cat_post.post_id INNER JOIN post_categories ON cat_post.cat_id=post_categories.cat_id GROUP BY posts.post_id";
        }else{
            $page_limit=($page-1)*$limit;
            $sql="SELECT posts.post_id,posts.post_title,posts.post_slug,posts.post_thumbnail,posts.preview_text,posts.post_content,posts.created_at,posts.updated_at,posts.tags FROM posts INNER JOIN cat_post ON posts.post_id=cat_post.post_id INNER JOIN post_categories ON cat_post.cat_id=post_categories.cat_id GROUP BY posts.post_id LIMIT $page_limit,$limit";

        }
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                $posts= $result->fetchAll(PDO::FETCH_ASSOC);

                return $posts;
            }
        }catch (\Exception $e){
            return false;
        }

    }
    public static function getAllPostsCount(){
        try{
            return self::select("posts","post_id","1",false,"",true);
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_all_post_categories()
    {
        try{
            return self::select("post_categories");

        }catch (\Exception $e){
            return false;
        }
    }

    public static function insert_new_post($postData)
    {
        unset($postData['MAX_FILE_SIZE']);
        try{
            if ($_FILES['post_thumbnail']['name']){
                $fileName=$_FILES["post_thumbnail"]["name"];
                $target_file = "public/images/posts/" . $fileName;
                if (!file_exists($target_file)){
                    move_uploaded_file($_FILES['post_thumbnail']['tmp_name'], $target_file);
                }
                $postData['post_thumbnail']=$fileName;
            }
            $post_id=self::genCode("posts","post_id");
            $postData['post_id']=$post_id;
            self::insert("posts",$postData,false);
            return $post_id;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function insert_post_categories($post_id, $post_categories)
    {
        try{
            foreach ($post_categories as $post_category){
                self::insert("cat_post",array(
                   'post_id'=>$post_id,
                    'cat_id'=>$post_category
                ));
            }
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function generate_and_add_tags_to_post($post_id, $post_categories)
    {
        try{
            $postTags="";
            foreach ($post_categories as $post_category){
                $category_title=self::select("post_categories","cat_title","cat_id='$post_category'",true);
                if (strlen($postTags)>1){
                    $tag=",".$category_title['cat_title'];
                    $postTags.=$tag;
                }else{
                    $postTags=$category_title['cat_title'];
                }
            }
            self::update("posts",array(
               'tags'=>$postTags
            ),"post_id='$post_id'");
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function delete_post_by_id($post_id)
    {
        try{
            self::delete("posts","post_id='$post_id'");
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
    public static function get_post_selected_categories($id){
        $id=self::prepare_input($id);
        $db=self::getDB();
        $sql="SELECT post_categories.cat_id,post_categories.cat_title FROM cat_post INNER JOIN post_categories ON post_categories.cat_id=cat_post.cat_id WHERE cat_post.post_id='$id'";
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }
    public static function get_post_data_by_id($id)
    {
        try{
            $post=self::select("posts","*","post_id='$id'",true);
            if ($post){
                return $post;
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function get_post_non_selected_categories($id)
    {
        $sql="SELECT post_categories.cat_id,post_categories.cat_title FROM post_categories WHERE post_categories.cat_id NOT IN(SELECT cat_id FROM cat_post WHERE cat_post.post_id='$id')";
        $db=self::getDB();
        try{
            $result=$db->query($sql);
            if($result->rowCount()>0){
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function updatePost($post_id, $postData)
    {
        unset($postData['MAX_FILE_SIZE']);
        if ($_FILES['post_thumbnail']['name']){
            unset($postData['defaultPic']);
            $fileName=$_FILES["post_thumbnail"]["name"];
            $target_file = "public/images/posts/" . $fileName;
            if (!file_exists($target_file)){
                move_uploaded_file($_FILES['project_image']['tmp_name'], $target_file);
            }
            $postData['post_thumbnail']=$fileName;
            $postCategories=$postData['post_categories'];
            unset($postData['post_categories']);
            try{
                $postData['tags']=static::create_tags_for_post($postCategories);
                self::update("posts",$postData,"post_id='$post_id'");
                static::delete_all_post_categories_by_id($post_id);
                static::insert_post_categories($postData,$postCategories);
                return true;
            }catch (\Exception $e){
                var_dump($e);
                return false;
            }
        }
        else{

            if ($postData['defaultPic']=="yes"){
                unset($postData['defaultPic']);
                $postData['post_thumbnail']="default.jpg";
            }else{
                unset($postData['defaultPic']);
            }
            $postCategories=$postData['post_categories'];
            unset($postData['post_categories']);
            try{
                $postData['tags']=static::create_tags_for_post($postCategories);
                self::update("posts",$postData,"post_id='$post_id'");
                static::delete_all_post_categories_by_id($post_id);
                static::insert_post_categories($post_id,$postCategories);
                return true;
            }catch (\Exception $e){

                return false;
            }
        }
    }

    private static function delete_all_post_categories_by_id($post_id)
    {
        $post_id=self::prepare_input($post_id);
        try{
            self::delete("cat_post","post_id='$post_id'");
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    private static function create_tags_for_post($postCategories)
    {
        try {
            $tags = "";
            foreach ($postCategories as $postCategoryId) {
                $category = self::select("post_categories", "cat_title", "cat_id='$postCategoryId'", true);
                if (strlen($tags) > 0) {
                    $tags .= ",$category[cat_title]";
                } else {
                    $tags = $category['cat_title'];
                }
            }
            return $tags;

        } catch (\Exception $e) {
            return false;
        }
    }
}