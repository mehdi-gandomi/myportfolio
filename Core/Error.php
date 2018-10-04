<?php


namespace Core;
use App\Config;

class Error
{
    public static function errorHandler($level,$message,$file,$line){
        if (error_reporting()!==0){
            throw new \ErrorException($message,0,$level,$file,$line);
        }
    }
    public static function exceptionHandler($exception){
        $code=$exception->getCode();
        if ($code!=404){
            $code=500;
        }
        http_response_code($code);
        if (Config::SHOW_ERRORS===true){
            echo "<h1>Fatal Error</h1>";
            echo "<p>Uncaught Exception'".get_class($exception)."'</p>";
            echo "<p>Message: '".$exception->getMessage()."'</p>";
            echo "<p>stack trace :<pre>".$exception->getTraceAsString()."</pre></p>";
            echo "<p>Thrown in '".$exception->getFile()."' On Line ".$exception->getLine()."</p>";
        }
        else{
            $logFile="./logs/".date("Y-m-d").".txt";
            ini_set("error_log",$logFile);
            $message= "\n"."<h1>Fatal Error</h1>";
            $message.= "\n"."<p>Uncaught Exception'".get_class($exception)."'</p>";
            $message.= "\n"."<p>Message: '".$exception->getMessage()."'</p>";
            $message.= "\n"."<p>stack trace :<pre>".$exception->getTraceAsString()."</pre></p>";
            $message.= "\n"."<p>Thrown in '".$exception->getFile()."' On Line ".$exception->getLine()."</p>";
            error_log($message);
            View::renderMustache("system/$code.html",[
                'title'=>'error'
            ]);

        }

    }
}