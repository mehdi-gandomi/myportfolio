<?php
namespace App\Models;
use Core\Model;
use PDO;

class AboutModel extends Model
{
    public static function get_bio(){
        $db=static::getDB();
        $sql="SELECT bio,id FROM personal_info";
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){
            var_dump($e);
        }
    }

    public static function get_user_job_fields($id)
    {
        try{
            $res=self::select("job_fields","*","user_id='$id'");
            if ($res){
                return $res;
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }
}