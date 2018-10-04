<?php
namespace App\Controllers;
use Core\View;
use App\Models\Post;

class Posts extends \Core\Controller
{
    protected function before()
    {

    }


    public function indexAction(){
       View::renderMustache("posts/index",[
           'title'=>'post page',

       ]);
    }
    public function AddNewAction(){
        echo "new fucked";
    }
    public function editAction(){
        echo "Hello from edit function";
        echo "Route Params : <pre>".htmlspecialchars(print_r($this->route_params),true)."</pre>";
    }
}