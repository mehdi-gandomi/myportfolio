<?php
/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 9/6/2018
 * Time: 7:13 PM
 */

namespace App\Models;
use PDO;
class LoginModel extends  \Core\Model
{
    public static function auth($username,$password){
        if (!$username || !$password){
            throw new \Exception("نام کاربری یا پسورد وارد نشده است.","0");
        }
        $result=self::select("users","*","username='$username'",true);
        if (!$result){
            throw new \Exception("چنین کاربری یافت نشد!","1");
        }

        if (password_verify($password,$result['password'])){
            session_start();
            $_SESSION['id']=$username;
            $_SESSION['fname']=$result['fname'];
            $_SESSION['lname']=$result['lname'];
            $_SESSION['avatar']=$result['avatar'];
            setcookie(session_name(),session_id(),time()+3600);
            return true;
        }else{
            throw new \Exception("پسورد وارد شده اشتباه است!","3");
        }
    }
}