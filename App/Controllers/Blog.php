<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 9/7/2018
 * Time: 7:09 PM
 */
namespace App\Controllers;
use App\Config;
use App\Models\Post;
use Core\View;


class Blog extends \Core\Controller {

  protected function before()
    {

    }
    
    public function indexAction(){
            $pages=[];
            $limit=10;
            $prevUrl="";
            $nextUrl="";
            $is_first_page=false;
            $is_last_page=false;
            $postsCount=Post::getAllPostsCount();
            $posts_categories=Post::getAllCategories();
            if (isset($this->route_params['page'])){
                $posts=Post::getAll(intval($this->route_params['page']),$limit);
                $pages_count=$postsCount/$limit;
                if ($pages_count>1){
                    $pagination_array=$this->create_pagination($pages_count,"blog-page/","pagination_title");
                    list($pages,$is_last_page,$is_first_page,$prevUrl,$nextUrl)=array_values($pagination_array);
                }else{
                    $is_last_page=true;
                    $is_first_page=true;
                }
                $pages=new \ArrayIterator($pages);
                View::renderMustache("blog-page",array(
                    'base_url'=>Config::BASE_URL,
                    'title'=>"پست های اخیر",
                    "posts"=>$posts,
                    'categories'=>$posts_categories,
                    'firstPage'=>$is_first_page,
                    'lastPage'=>$is_last_page,
                    'pages'=>$pages,
                    'prevUrl'=>$prevUrl,
                    'nextUrl'=>$nextUrl
                ));
            }else{
                $posts=Post::getAll(1,$limit);
                $pages_count=$postsCount/$limit;

                if ($pages_count>1){

                    for ($i=1;$i<=$pages_count;$i++){
                        $pages[$i]['pagination_title']=$i;
                        $pages[$i]['url']="blog-page/$i";
                    }
                    $pages[1]['active']=true;
                    $nextUrl=Config::BASE_URL."/blog-page/2";
                    $pages=new \ArrayIterator($pages);
                    View::renderMustache("blog-page",array(
                        'base_url'=>Config::BASE_URL,
                        'title'=>"پست های اخیر",
                        "posts"=>$posts,
                        'categories'=>$posts_categories,
                        'firstPage'=>true,
                        'lastPage'=>false,
                        'pages'=>$pages,
                        'nextUrl'=>$nextUrl
                    ));
                }else{
                    $is_first_page=true;
                    View::renderMustache("blog-page",array(
                        'base_url'=>Config::BASE_URL,
                        'title'=>"پست های اخیر",
                        "posts"=>$posts,
                        'categories'=>$posts_categories,
                        'firstPage'=>$is_first_page,
                        'lastPage'=>true,
                        'pages'=>$pages,
                        'nextUrl'=>$nextUrl
                    ));
                }

            }

    }

    public function singlePostAction(){
        if(isset($this->route_params['slug'])){
            $post=Post::get_post_data_by_slug($this->route_params['slug']);
            
            if($post){
                $userData=Post::get_user_data_by_id($post['user_id']);
                
                View::renderMustache("post",array(
                    'base_url'=>Config::BASE_URL,
                    'post_title'=>$post['post_title'],
                    "post_thumbnail"=>$post['post_thumbnail'],
                    'tags'=>strlen($post['tags'])>0 ? explode(",",$post['tags']):false,
                    'post_content'=>$post['post_content'],
                    'fullname'=>$userData['fname']." ".$userData['lname'],
                    'has_tags'=>strlen($post['tags'])>0
                ));       
            }else{
                echo "پستی با این مشخصات یافت نشد";
            }
            
        }
    }
      private function create_pagination($pages_count,$base_url="dashboard/comment/",$digitsName="title")
    {
        $output=[];
        for ($i=1;$i<=$pages_count;$i++){
            $pages[$i][$digitsName]=$i;
            $pages[$i]['url']=$base_url.$i;

        }
        if (isset($this->route_params['page'])){
            $pages[intval($this->route_params['page'])]['active']=true;
            $is_last_page=intval($this->route_params['page'])===intval($pages_count);
            $is_first_page=intval($this->route_params['page'])===1;
            $prevUrl=Config::BASE_URL."/$base_url".intval($this->route_params['page'] - 1);
            $nextUrl=Config::BASE_URL."/$base_url".intval($this->route_params['page'] + 1);
        }else if($_GET['page']){
            $pages[intval($_GET['page'])]['active']=true;
            $is_last_page=intval($_GET['page'])===intval($pages_count);
            $is_first_page=intval($_GET['page'])===1;
            $prevUrl=Config::BASE_URL."/$base_url".intval($_GET['page'] - 1);
            $nextUrl=Config::BASE_URL."/$base_url".intval($_GET['page'] + 1);
        }


        //create output
        $output['pages']=$pages;
        $output['is_last_page']=$is_last_page;
        $output['is_first_page']=$is_first_page;
        $output['prevUrl']=$prevUrl;
        $output['nextUrl']=$nextUrl;

        return $output;
    }
      protected function after()
    {

    }
}