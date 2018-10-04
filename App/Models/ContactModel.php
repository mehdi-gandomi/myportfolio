<?php
/**
 * Created by PhpStorm.
 * User: Coderguy
 * Date: 8/22/2018
 * Time: 10:57 PM
 */

namespace App\Models;


use Core\Model;

class ContactModel extends Model
{
    public static function get_contact_info(){
        try{
            return self::select("personal_info","email,phone,address","1",true);

        }catch (\Exception $e){
            return false;
        }
    }
}
