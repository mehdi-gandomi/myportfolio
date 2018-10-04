<?php
namespace Core;
require 'vendor/mustache/mustache/src/Mustache/Autoloader.php';
\Mustache_Autoloader::register();

class View{
    public static function render($view,$args=[]){
        extract($args,EXTR_SKIP);
        $file="App/Views/$view";
        if (is_readable($file)){
            require $file;
        }
        else{
            echo "$file Not found";
        }
    }
    public static function renderMustache($template,$args=[]){
       static $m=null;
        $options =  array('extension' => '.html');

        if ($m==null){
           $m = new \Mustache_Engine(array(
               'loader' => new \Mustache_Loader_FilesystemLoader( 'App/Views',$options),
               'partials_loader' => new \Mustache_Loader_FilesystemLoader('App/Views/partials',$options),
               'escape' => function($value) {
//                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                   return $value;
                },
           ));
       }
        echo $m->render($template,$args);

    }

}