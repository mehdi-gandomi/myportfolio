<?php

/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/12/2018
 * Time: 8:46 PM
 */
namespace App\Controllers;
use App\Config;
use App\Models\Post;
use App\Models\ProjectsModel;
use ArrayIterator;
use Core\Router;
use Core\View;
use App\Models\DashboardModel;

class Dashboard extends \Core\Controller {


    protected function before()
    {
//        session_start();
//
//        if (!isset($_SESSION['id']) || $_SESSION['id']==false){
//            self::redirect_to(Config::BASE_URL."/dashboard/login");
//        }
    }

    public function AllComments(){
        $pages=[];
        $limit=10;
        $prevUrl="";
        $nextUrl="";
        $is_first_page=false;
        $is_last_page=false;
        $projects_comments_count=ProjectsModel::get_all_projects_comments_count();
        if (isset($this->route_params['page'])){
            $projects_comments=ProjectsModel::get_all_projects_comments($this->route_params['page'],$limit);
            $projects_comments=$this->slice_the_comments($projects_comments);
            $pages_count=$projects_comments_count/$limit;
            if ($pages_count>1){
                $pagination_array=$this->create_pagination($pages_count);
                list($pages,$is_last_page,$is_first_page,$prevUrl,$nextUrl)=array_values($pagination_array);
                $pages=new ArrayIterator($pages);
            }else{
                $is_last_page=true;
                $is_first_page=true;
            }

            View::renderMustache("dashboard/comments",array(
                'base_url'=>Config::BASE_URL,
                'projects_comments'=>$projects_comments,
                'firstPage'=>$is_first_page,
                'lastPage'=>$is_last_page,
                'pages'=>$pages,
                'prevUrl'=>$prevUrl,
                'nextUrl'=>$nextUrl
            ));
        }else{
            $projects_comments=ProjectsModel::get_all_projects_comments(1,$limit);

            $projects_comments=$this->slice_the_comments($projects_comments);
            $pages_count=$projects_comments_count/$limit;
            if ($pages_count>1){

                for ($i=1;$i<=$pages_count;$i++){
                    $pages[$i]['title']=$i;
                    $pages[$i]['url']="dashboard/comment/$i";
                }
                $pages[1]['active']=true;
                $pages=new ArrayIterator($pages);
                $nextUrl=Config::BASE_URL."/dashboard/comment/2";
            }else{
                $is_first_page=true;
            }


            View::renderMustache("dashboard/comments",array(
                'base_url'=>Config::BASE_URL,
                'projects_comments'=>$projects_comments,
                'firstPage'=>$is_first_page,
                'lastPage'=>true,
                'pages'=>$pages,
                'nextUrl'=>$nextUrl
            ));
        }

    }
    public function getCommentMessage(){
        $comment_id=$this->route_params['id'];
        $res=DashboardModel::get_comment_full_message($comment_id);
        if ($res){
            echo $res['message'];
        }
    }
    public function AllCommentsJson(){
        $limit=10;
        $projects_comments_count=ProjectsModel::get_all_projects_comments_count();
        if (isset($_GET['page'])){
            $page=intval($_GET['page']);
            $projects_comments=ProjectsModel::get_all_projects_comments($page,$limit);
            $projects_comments=$this->slice_the_comments($projects_comments);
            $pages_count=$projects_comments_count/$limit;
            if ($pages_count>1){
                $pagination_array=$this->create_pagination($pages_count);
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'project_comments'=>$projects_comments
                    )
                );
            }else{
                $pagination_array['is_last_page']=true;
                $pagination_array['is_first_page']=true;
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'project_comments'=>$projects_comments
                    )
                );
            }
        }else{
            $projects_comments=ProjectsModel::get_all_projects_comments(1,$limit);
            $projects_comments=$this->slice_the_comments($projects_comments);
            $pages_count=$projects_comments_count/$limit;
            if ($pages_count>1) {

                for ($i = 1; $i <= $pages_count; $i++) {
                    $pages[$i]['title'] = $i;
                    $pages[$i]['url'] = "dashboard/comment/$i";
                }
                $pages[1]['active'] = true;
                $pages = new ArrayIterator($pages);
                $pagination_array['nextUrl'] = Config::BASE_URL . "/dashboard/comment/2";
                $pagination_array['pages']=$pages;
                $pagination_array['is_last_page']=false;
                $pagination_array['is_first_page']=true;
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'project_comments'=>$projects_comments
                    )
                );
            }
            else{

                $pagination_array['is_last_page']=true;
                $pagination_array['is_first_page']=true;
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'project_comments'=>$projects_comments
                    )
                );
            }
        }


    }
    public function DeleteComment(){
        if ($_SERVER['REQUEST_METHOD']=="DELETE") {
            parse_str(file_get_contents("php://input"), $post_vars);
            $res = DashboardModel::delete_comment($post_vars['comment_id']);
            if ($res) {
                echo json_encode(
                    array(
                        'status' => 'ok'
                    )
                );
            }else{
                echo json_encode(
                    array(
                        'status' => 'error'
                    )
                );
            }
        }
    }
    public function UpdateCommentAction(){
        if ($_SERVER['REQUEST_METHOD']=="PUT") {
            parse_str(file_get_contents("php://input"), $post_vars);
            $res = DashboardModel::update_comment_state($post_vars['comment_id']);
            if ($res) {
                echo json_encode(
                    array(
                        'status' => 'ok'
                    )
                );
            }else{
                echo json_encode(
                    array(
                        'status' => 'error'
                    )
                );
            }
        }
    }
    public function indexAction(){
        $personal_info=DashboardModel::get_all_personal_info();
        if ($personal_info){
            View::renderMustache("dashboard/index",array(
                'title'=>'داشبورد',
                'base_url'=>Config::BASE_URL,
                'avatar'=>$personal_info['avatar'],
                'fname'=>$personal_info['fname'],
                'lname'=>$personal_info['lname'],
                'phone'=>$personal_info['phone'],
                'address'=>$personal_info['address'],
                'user_id'=>$personal_info['id'],
                'job'=>$personal_info['job'],
                'email'=>$personal_info['email'],
                'instagram'=>$personal_info['instagram'],
                'telegram'=>$personal_info['telegram'],
                'linkedin'=>$personal_info['linkedin'],
                'github'=>$personal_info['github'],
                'twitter'=>$personal_info['twitter'],
                'pinterest'=>$personal_info['pinterest'],
                'profile'=>$personal_info['profile'],
                'image_path'=>'public/images/'.$personal_info['avatar']

            ));
        }
        else{
            View::renderMustache("dashboard/index",array(
                'title'=>'داشبورد'
            ));
        }

    }
    public function editProfileAction(){
        if ($_FILES['avatar']['name']){
            $fileName=$_FILES["avatar"]["name"];
            $target_file = "public/images/" . $fileName;
            if (!file_exists($target_file)){
                move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file);
            }
            $personal_info=$_POST;
            $personal_info['avatar']=$fileName;
            DashboardModel::insert_personal($personal_info);
            setcookie("avatar_path",$target_file);
        }else{
            $personal_info=$_POST;
            if (isset($_POST['defaultPic']) && $_POST['defaultPic']=="yes"){
                $personal_info['avatar']="/users/1.jpg";
            }else{
                $personal_info['avatar']=null;
            }
            DashboardModel::insert_personal($personal_info);
            setcookie("avatar_path",$personal_info['avatar']);
        }


    }
    public function about_meAction(){
        $userData=DashboardModel::get_users();
        $skills=DashboardModel::get_all_skills();
        $skills=new ArrayIterator($skills);
        $job_fields=DashboardModel::get_user_job_fields($userData['id']);
        View::renderMustache("dashboard/about-me",array(
            'title'=>'ویرایش صفحه درباره من',
            'base_url'=>Config::BASE_URL,
            'user_id'=>$userData['id'],
            'skills'=>$skills,
            'bio'=>$userData['bio'],
            'job_fields'=>$job_fields

        ));
    }
    public function editAboutMeAction(){
        $skill_data=$_POST;
        $res=DashboardModel::insert_skill($skill_data);
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
    public function editAboutMeJobField(){
        if ($_SERVER['REQUEST_METHOD']==="POST"){
            $res=DashboardModel::insertJobField($_POST);
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
        else{
            $jobFields=DashboardModel::get_user_job_fields($_GET['user_id']);
            if ($jobFields){
                echo json_encode($jobFields);
            }

        }
    }
    public function editPortfolioAction(){
        $projects=DashboardModel::get_all_projects();
        $projects=new ArrayIterator($projects);
        View::renderMustache("dashboard/projects",array(
            'title'=>'داشبورد',
            'base_url'=>Config::BASE_URL,
            'projects'=>$projects
        ));
    }
    public function editProjectAction(){
        $id=$this->route_params['id'];
        $project=ProjectsModel::get_project_by_id($id);
        $selectedSkills=ProjectsModel::get_project_skills($id);
        $otherSkills=ProjectsModel::get_skills_except_selectedfields($id);
        $selectedCats=ProjectsModel::get_project_categories($id);
        $project_gallery=ProjectsModel::get_project_gallery_by_id($id);
        $otherCats=ProjectsModel::get_categories_except_selectedfields($id);
        View::renderMustache("dashboard/project-edit",array(
           'base_url'=>Config::BASE_URL,
            'selected_skills'=>$selectedSkills,
            'project_skills'=>$otherSkills,
            'selected_categories'=>$selectedCats,
            'other_categories'=>$otherCats,
            'project_title'=>$project['project_title'],
            'project_image'=>$project['project_image'] ? "public/images/projects/".$project['project_image']:"http://localhost/picfaker/?width=150&height=150",
            'project_description'=>$project['project_description'],
            'project_link'=>$project['project_link'],
            'location'=>$project['location'],
            'project_gallery'=>$project_gallery
        ));
    }
    public function get_user_skills(){
        $skills=DashboardModel::get_skills();
        echo json_encode(
            $skills
        );
    }
    public function getAllSkills(){
        $skills=DashboardModel::get_all_skills();
        echo json_encode(
          $skills
        );
    }
    public function newSkillAction(){
        $skillData=$_POST;
        $res=DashboardModel::newSkill($skillData);
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
    public function deleteSkillAction(){
        if ($_SERVER['REQUEST_METHOD']=="DELETE"){
            parse_str(file_get_contents("php://input"),$post_vars);
            $res=DashboardModel::delete_skill($post_vars['id']);
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

    }
    public function ProjectsAction(){
        $pages=[];
        $limit=10;
        $prevUrl="";
        $nextUrl="";
        $is_first_page=false;
        $is_last_page=false;
        $projects_count=intval(ProjectsModel::get_projects_count());
        if (isset($this->route_params['page'])){
            $projects=ProjectsModel::get_all_projects($this->route_params['page'],$limit);
            $categories=ProjectsModel::get_all_project_skill_categories();
            $project_categories=new ArrayIterator(ProjectsModel::get_all_categories());
            $pages_count=$projects_count/$limit;
            if ($pages_count>1){
                $pagination_array=$this->create_pagination($pages_count,"dashboard/project/");
                list($pages,$is_last_page,$is_first_page,$prevUrl,$nextUrl)=array_values($pagination_array);
                $pages=new ArrayIterator($pages);
            }else{
                $is_last_page=true;
                $is_first_page=true;
            }
            View::renderMustache("dashboard/projects",array(
                'base_url'=>Config::BASE_URL,
                'projects'=>$projects,
                'skill_categories'=>$categories,
                'project_categories'=>$project_categories,
                'firstPage'=>$is_first_page,
                'lastPage'=>$is_last_page,
                'pages'=>$pages,
                'prevUrl'=>$prevUrl,
                'nextUrl'=>$nextUrl
            ));
        }else{
            $projects=ProjectsModel::get_all_projects(1,$limit);
            $categories=ProjectsModel::get_all_project_skill_categories();
            $project_categories=new ArrayIterator(ProjectsModel::get_all_categories());
            $pages_count=$projects_count/$limit;
            if ($pages_count>1){
            for ($i=1;$i<=$pages_count;$i++){
                $pages[$i]['title']=$i;
                $pages[$i]['url']="dashboard/project/$i";
            }
            $pages[1]['active']=true;
            $pages=new ArrayIterator($pages);
            $nextUrl=Config::BASE_URL."/dashboard/project/2";
        }else{
            $is_first_page=true;
        }

        View::renderMustache("dashboard/projects",array(
                'base_url'=>Config::BASE_URL,
                'projects'=>$projects,
                'skill_categories'=>$categories,
                'project_categories'=>$project_categories,
                'firstPage'=>$is_first_page,
                'pages'=>$pages,
                'nextUrl'=>$nextUrl
            ));
        }

    }
    public function newProjectAction(){

        if ($_SERVER['REQUEST_METHOD']=="POST"){
            $projectData=$_POST;
            $res=ProjectsModel::newProject($projectData);
            if ($res){

                echo json_encode(
                    array(
                        'status'=>'ok'
                    )
                );
            }else {
                echo json_encode(
                    array(
                        'status'=>'error'
                    )
                );
            }
        }else{
            echo "this route have to be post request";
        }
    }
    public function updateProjectAction()
    {
        $id=$this->route_params['id'];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            unset($_POST['MAX_FILE_SIZE']);
            $res = ProjectsModel::update_project($id,$_POST);
            if ($res) {
                echo json_encode(
                    array(
                        'status' => 'ok'
                    )
                );
            }else{
                echo json_encode(
                    array(
                        'status' => 'error'
                    )
                );
            }
        }else{
            var_dump("this link is supposed to be a post request");
        }
    }
    public function deleteProjectAction(){
        $project_id=$this->route_params['id'];
        $res=ProjectsModel::delete_project_by_id($project_id);
        if ($res) {
            echo json_encode(
                array(
                    'status' => 'ok'
                )
            );
        }else{
            echo json_encode(
                array(
                    'status' => 'error'
                )
            );
        }
    }
    public function ProjectsJsonAction(){
        $limit=10;
        $projects_count=ProjectsModel::get_projects_count();
        if (isset($_GET['page'])){
            $page=intval($_GET['page']);
            $projects=ProjectsModel::get_all_projects($page,$limit);
            $pages_count=$projects_count/$limit;
            if ($pages_count>1){
                $pagination_array=$this->create_pagination($pages_count,"dashboard/project/");
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'projects'=>$projects
                    )
                );
            }else{
                $pagination_array['is_last_page']=true;
                $pagination_array['is_first_page']=true;
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'projects'=>$projects
                    )
                );
            }
        }else{
            $projects=ProjectsModel::get_all_projects(1,$limit);
            $pages_count=$projects_count/$limit;
            if ($pages_count>1) {

                for ($i = 1; $i <= $pages_count; $i++) {
                    $pages[$i]['title'] = $i;
                    $pages[$i]['url'] = "dashboard/project/$i";
                }
                $pages[1]['active'] = true;
                $pages = new ArrayIterator($pages);
                $pagination_array['nextUrl'] = Config::BASE_URL . "/dashboard/project/2";
                $pagination_array['pages']=$pages;
                $pagination_array['is_last_page']=false;
                $pagination_array['is_first_page']=true;
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'projects'=>$projects
                    )
                );
            }
            else{

                $pagination_array['is_last_page']=true;
                $pagination_array['is_first_page']=true;
                echo  json_encode(
                    array(
                        'pagination'=>$pagination_array,
                        'projects'=>$projects
                    )
                );
            }
        }
    }
    public function postsPageAction(){
        if ($_SERVER['REQUEST_METHOD']==="GET"){
            $pages=[];
            $limit=10;
            $prevUrl="";
            $nextUrl="";
            $is_first_page=false;
            $is_last_page=false;
            $postsCount=Post::getAllPostsCount();
            $posts_categories=Post::get_all_post_categories();
            if (isset($this->route_params['page'])){
                $posts=Post::getAll(intval($this->route_params['page']),$limit);
                $pages_count=$postsCount/$limit;
                if ($pages_count>1){
                    $pagination_array=$this->create_pagination($pages_count,"dashboard/posts/");
                    list($pages,$is_last_page,$is_first_page,$prevUrl,$nextUrl)=array_values($pagination_array);
                }else{
                    $is_last_page=true;
                    $is_first_page=true;
                }
                $pages=new \ArrayIterator($pages);
                View::renderMustache("dashboard/post-management",array(
                    'base_url'=>Config::BASE_URL,
                    'title'=>"پست های اخیر",
                    "posts"=>$posts,
                    'firstPage'=>$is_first_page,
                    'lastPage'=>$is_last_page,
                    'pages'=>$pages,
                    'prevUrl'=>$prevUrl,
                    'nextUrl'=>$nextUrl,
                    'post_categories'=>$posts_categories
                ));
            }
            else{
                $posts=Post::getAll(1,$limit);
                $pages_count=$postsCount/$limit;
                if ($pages_count>1){

                    for ($i=1;$i<=$pages_count;$i++){
                        $pages[$i]['title']=$i;
                        $pages[$i]['url']="posts/$i";
                    }
                    $pages[1]['active']=true;
                    $nextUrl=Config::BASE_URL."/posts/2";
                    $pages=new \ArrayIterator($pages);
                    View::renderMustache("dashboard/post-management",array(
                        'base_url'=>Config::BASE_URL,
                        'title'=>"پست های اخیر",
                        "posts"=>$posts,
                        'firstPage'=>true,
                        'lastPage'=>false,
                        'pages'=>$pages,
                        'nextUrl'=>$nextUrl,
                        'post_categories'=>$posts_categories
                    ));
                }else{
                    $is_first_page=true;
                    View::renderMustache("dashboard/post-management",array(
                        'base_url'=>Config::BASE_URL,
                        'title'=>"پست های اخیر",
                        "posts"=>$posts,
                        'firstPage'=>$is_first_page,
                        'lastPage'=>true,
                        'pages'=>$pages,
                        'nextUrl'=>$nextUrl,
                        'post_categories'=>$posts_categories
                    ));
                }

            }
        }
        else if ($_SERVER['REQUEST_METHOD']==="POST"){
//            update post
            if (isset($_POST['_method']) && $_POST['_method']=="PUT"){
                unset($_POST['_method']);
                if ($this->route_params['id']){
                    $res=Post::updatePost($this->route_params['id'],$_POST);
                    if ($res){
                        self::redirect_to(Config::BASE_URL."/dashboard/posts");
                    }else{
                        $this->editPostAction();
                    }
                }
            }

//            new post
            $postData=$_POST;
            $post_categories=$postData['post_categories'];
            unset($postData['post_categories']);

            $post_id=Post::insert_new_post($postData);
            $result=Post::insert_post_categories($post_id,$post_categories);
            $result=Post::generate_and_add_tags_to_post($post_id,$post_categories);
            if ($result){
                echo json_encode(
                    array(
                        'status'=>"ok"
                    )
                );
            }else{
                echo json_encode(
                    array(
                        'status'=>"error"
                    )
                );
            }
        }
        else if($_SERVER['REQUEST_METHOD']==="DELETE"){
            if ($this->route_params['id']){
                $res=Post::delete_post_by_id($this->route_params['id']);
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

        }
        else if($_SERVER['REQUEST_METHOD']==="PUT"){

        }
    }
    public function editPostAction(){
        $post=Post::get_post_data_by_id($this->route_params['id']);
        $selected_categories=Post::get_post_selected_categories($this->route_params['id']);
        $non_selected_categories=Post::get_post_non_selected_categories($this->route_params['id']);

        View::renderMustache("dashboard/post-edit",array(
            'base_url'=>Config::BASE_URL,
           'post_id'=>$post['post_id'],
           'post_thumbnail'=>$post['post_thumbnail'] ? "public/images/posts/".$post['post_thumbnail']:"http://localhost/picfaker/?width=150&height=150",
            'post_title'=>$post['post_title'],
            'post_slug'=>$post['post_slug'],
            'preview_text'=>$post['preview_text'],
            'post_content'=>$post['post_content'],
            'selected_categories'=>$selected_categories,
            'non_selected_categories'=>$non_selected_categories
        ));
    }
    public function postsJsonAction(){
        $pages=[];
        $limit=10;
        $prevUrl="";
        $nextUrl="";
        $is_first_page=false;
        $is_last_page=false;
        $paginationJson=[];
        $postsCount=Post::getAllPostsCount();
        $posts_categories=Post::get_all_post_categories();
        if (isset($this->route_params['page'])){
            $posts=Post::getAll(intval($this->route_params['page']),$limit);
            $pages_count=$postsCount/$limit;
            if ($pages_count>1){
                $paginationJson=$this->create_pagination($pages_count,"dashboard/posts/");
            }else{
                $is_last_page=true;
                $is_first_page=true;
                $paginationJson=array(
                  'is_last_page'=>$is_last_page,
                   'is_first_page'=>$is_first_page,
                    'pages'=>[]
                );
            }

            echo json_encode(
                array(
                    'posts'=>$posts,
                    'pagination'=>$paginationJson
                )
            );
        }else{
            $posts=Post::getAll(1,$limit);
            $pages_count=$postsCount/$limit;

            if ($pages_count>1){

                for ($i=1;$i<=$pages_count;$i++){
                    $pages[$i]['title']=$i;
                    $pages[$i]['url']="dashboard/posts/$i";
                }
                $pages[1]['active']=true;
                $nextUrl="/dashboard//posts/2";

                $paginationJson=array(
                    'lastPage'=>false,
                    'firstPage'=>true,
                    'pages'=>$pages,
                    'nextUrl'=>$nextUrl,
                );
                echo json_encode(
                    array(
                        'posts'=>$posts,
                        'pagination'=>$paginationJson
                    )
                );
            }else{
                $paginationJson=array(
                    'lastPage'=>true,
                    'firstPage'=>true,
                    'pages'=>$pages,
                    'nextUrl'=>$nextUrl,
                );
                echo json_encode(
                    array(
                        'posts'=>$posts,
                        'pagination'=>$paginationJson
                    )
                );
            }

        }
    }
    public function ajaxImageUploadAction(){
        $uploadedImages=[];
        for ($i=0;$i<count($_FILES);$i++){
            $fileName=$_FILES['file-'.$i]['name'];
            $target_file=$this->join_paths("public/images/uploads/editor",$fileName);
            if (!file_exists($target_file)){
                move_uploaded_file($_FILES['file-'.$i]['tmp_name'], $target_file);
            }
            array_push($uploadedImages,['SUNEDITOR_IMAGE_SRC'=>$target_file]);

        }
        echo json_encode(

                $uploadedImages

        );
    }


//    file manager functions

    public function fileManagerAction(){
        if ($_SERVER['REQUEST_METHOD']=="GET"){
            $finalPath=isset($_GET['route']) ? $_GET['route']:"public/images/uploads";
            $dirs=$this->list_directory($finalPath);
            
            echo "<input id='current-path' type='hidden' value='$finalPath'>";
            
            if (count($dirs)>=1){
              foreach ($dirs as $dir){
                  $baseName=pathinfo($dir,PATHINFO_BASENAME);
                  if (is_dir($dir)){
                      echo "
                           <div class='folder'>
                                <a href='$dir'>
                                    <i class='i-folder'></i>
                                </a>
                                <label class='control control--checkbox'>$baseName
                                    <input type='checkbox'  id='$baseName' name='folders[]' value='$dir'>
                                    <div class='control__indicator'></div>
                                </label>
                            </div>

                      ";
                  }
                  else if (in_array(pathinfo($dir,PATHINFO_EXTENSION),['png','jpg','bmp','jpeg'])){


                      echo "
                            <div class='file'>
                                <a href='$dir'>
                                    <img src='$dir' class='image-file' alt='$baseName'>
                                </a>
                                <label class='control control--checkbox'>$baseName
                                    <input type='checkbox' id='$baseName' name='files[]' value='$dir'>
                                    <div class='control__indicator'></div>
                                </label>
                            </div>
                      ";
                  }
              }
            }else{
                echo "<h4 style='grid-column: 1/6;' class='text-center'>در این پوشه فایلی وجود ندارد:(</h4>";
            }

        }
        else if ($_SERVER['REQUEST_METHOD']=="POST"){
            if ($_FILES){
                $path=$_POST['current_path'];
                try{
                    foreach($_FILES as $file){
                        $target_file=$this->join_paths($path,$file['name']);
                        if (!file_exists($target_file)){
                            move_uploaded_file($file['tmp_name'], $target_file);
                        }
                    }
                    echo json_encode(
                        array(
                            'status'=>'ok'
                        )
                    );
                }catch(\Exception $e){
                    echo json_encode(
                        array(
                            'status'=>'error',
                            'error'=>$e
                        )
                    );
                }
            }else{
                $folderName=mb_convert_encoding($_POST['folder'],"UTF-8");
                $path=$_POST['current_path'];
                $full_path=$this->join_paths($path,$folderName);
                if (!file_exists($full_path)){
                    try{
                        mkdir($full_path);
                        echo json_encode(
                            array(
                                'status'=>'ok'
                            )
                        );
                    }catch (\Exception $e){
                        echo json_encode(
                            array(
                                'status'=>'error',
                                'error'=>$e
                            )
                        );
                    }
                }else{
                    echo json_encode(
                        array(
                            'status'=>'ok'
                        )
                    );
                }

            }
        }
        else if($_SERVER['REQUEST_METHOD']=="DELETE"){
            parse_str(file_get_contents("php://input"), $post_vars);
            try{
                if(isset($post_vars['folders'])){
                    foreach($post_vars['folders'] as $folder ){
                        $this->removeDirectory($folder);
                    }
                }
                if(isset($post_vars['files'])){
                    foreach($post_vars['files'] as $file ){
                        unlink($file);
                    }
                }
                echo json_encode(
                    array(
                        'status'=>'ok'
                    )
                );
            }catch(\Exception $e){
                echo json_encode(
                    array(
                        'status'=>'error',
                        'error'=>$e
                    )
                );
            }
            
        }

    }
    private function  removeDirectory($path) {
        $files = glob($path . '/*');
         if($files){
             foreach ($files as $file) {
                 is_dir($file) ? removeDirectory($file) : unlink($file);
             }
         }
        rmdir($path);   
    }
    private function list_directory($path="public/images/uploads"){

        if (file_exists($path)){
            $dirs=scandir($path);
            $dirs=array_diff($dirs, [".", ".."]);
            usort ($dirs, function ($a,$b) {
                return is_dir($a)
                    ? (is_dir($b) ? strnatcasecmp($a, $b) : -1)
                    : (is_dir($b) ? 1 : (
                    strcasecmp(pathinfo($a, PATHINFO_EXTENSION), pathinfo($b, PATHINFO_EXTENSION)) == 0
                        ? strnatcasecmp($a, $b)
                        : strcasecmp(pathinfo($a, PATHINFO_EXTENSION), pathinfo($b, PATHINFO_EXTENSION))
                    ));
            });
            $dirs=array_map(function ($dir) use ($path){return $this->join_paths($path,$dir);},$dirs );
            return $dirs;
        }
        return false;

    }
    private function create_pagination($pages_count,$base_url="dashboard/comment/")
    {
        $output=[];
        for ($i=1;$i<=$pages_count;$i++){
            $pages[$i]['title']=$i;
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
    private function slice_the_comments($projects_comments){
        for ($i=0;$i<count($projects_comments);$i++){
            if (strlen($projects_comments[$i]['message'])>40){
                $id=$projects_comments[$i]['comment_id'];
                $projects_comments[$i]['message']=substr($projects_comments[$i]['message'],0,40)." <a href='#'   data-toggle='modal' data-target='#moreInfoModal' onclick=\"showMore('$id')\">....</a>";
            }
        }
        return $projects_comments;
    }
    private function join_paths() {
        $paths = array();

        foreach (func_get_args() as $arg) {
            if ($arg !== '') { $paths[] = $arg; }
        }

        return preg_replace('#/+#','/',join('/', $paths));
    }

    private function get_os() {

        $user_agent=$_SERVER['HTTP_USER_AGENT'];
        $os_platform  = "Unknown OS Platform";

        $os_array     = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;

        return $os_platform;
    }
    protected function after()
    {

    }
}

