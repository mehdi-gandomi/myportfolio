<?php

//require "./Core/Router.php";
//require "./App/Controllers/Posts.php";

error_reporting(E_ALL);

spl_autoload_register(function ($class){

$file=__DIR__.'/'.str_replace('\\','/',$class).'.php';
if (is_readable($file)){
    require $file;
}
});
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');
$router=new Core\Router();

$router->add('',['controller'=>'Home','action'=>'index']);
$router->add('about-me', ['controller' => 'About', 'action' => 'index']);
$router->add('my-portfolio', ['controller' => 'Portfolio', 'action' => 'index']);
$router->add('contact-me', ['controller' => 'Contact', 'action' => 'index']);
$router->add('blog-page', ['controller' => 'Blog', 'action' => 'index']);
$router->add('blog-page/{page:\d+}', ['controller' => 'Blog', 'action' => 'index']);
$router->add('dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
$router->add('dashboard/edit-profile-post', ['controller' => 'Dashboard', 'action' => 'editProfile']);
$router->add('dashboard/about-me', ['controller' => 'Dashboard', 'action' => 'about_me']);
$router->add('dashboard/edit-about-me', ['controller' => 'Dashboard', 'action' => 'editAboutMe']);
$router->add('dashboard/edit-about-me/job-field', ['controller' => 'Dashboard', 'action' => 'editAboutMeJobField']);
$router->add('dashboard/edit-portfolio', ['controller' => 'Dashboard', 'action' => 'editPortfolio']);
$router->add('dashboard/project/edit/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'editProject']);
$router->add('dashboard/project/update/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'updateProject']);
$router->add('dashboard/project/delete/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'deleteProject']);
$router->add('dashboard/project', ['controller' => 'Dashboard', 'action' => 'Projects']);
$router->add('dashboard/project/{page:\d+}', ['controller' => 'Dashboard', 'action' => 'Projects']);
$router->add('portfolio/{id:\d+}', ['controller' => 'Portfolio', 'action' => 'oneProject']);
$router->add('portfolio/comment/new', ['controller' => 'Portfolio', 'action' => 'newComment']);
$router->add('dashboard/project/all-json', ['controller' => 'Dashboard', 'action' => 'ProjectsJson']);
$router->add('dashboard/project/new', ['controller' => 'Dashboard', 'action' => 'newProject']);
$router->add('dashboard/get_user_skills', ['controller' => 'Dashboard', 'action' => 'get_user_skills']);
$router->add('dashboard/skill/new', ['controller' => 'Dashboard', 'action' => 'newSkill']);
$router->add('dashboard/skill/all', ['controller' => 'Dashboard', 'action' => 'getAllSkills']);
$router->add('dashboard/skill/delete', ['controller' => 'Dashboard', 'action' => 'deleteSkill']);
$router->add('dashboard/comment', ['controller' => 'Dashboard', 'action' => 'AllComments']);
$router->add('dashboard/comment/{page:\d+}', ['controller' => 'Dashboard', 'action' => 'AllComments']);
$router->add('dashboard/comment/{id:\d+}/full-message', ['controller' => 'Dashboard', 'action' => 'getCommentMessage']);
$router->add('dashboard/comment/all-json', ['controller' => 'Dashboard', 'action' => 'AllCommentsJson']);
$router->add('dashboard/comment/update', ['controller' => 'Dashboard', 'action' => 'UpdateComment']);
$router->add('dashboard/comment/delete', ['controller' => 'Dashboard', 'action' => 'DeleteComment']);
$router->add('dashboard/posts', ['controller' => 'Dashboard', 'action' => 'postsPage']);
$router->add('dashboard/posts/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'postsPage']);
$router->add('dashboard/posts/edit/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'editPost']);
$router->add('dashboard/posts/json', ['controller' => 'Dashboard', 'action' => 'postsJson']);
$router->add('dashboard/posts/{page:\d+}/json', ['controller' => 'Dashboard', 'action' => 'postsJson']);
$router->add('dashboard/login', ['controller' => 'DashboardLogin', 'action' => 'index']);
$router->add('dashboard/editor/uploadImage.ajax', ['controller' => 'Dashboard', 'action' => 'ajaxImageUpload']);
$router->add('dashboard/file-manager', ['controller' => 'Dashboard', 'action' => 'fileManager']);
$url=$_SERVER['QUERY_STRING'];
$router->dispatch($_SERVER['QUERY_STRING']);
?>

