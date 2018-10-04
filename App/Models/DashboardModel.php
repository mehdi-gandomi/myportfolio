<?php
namespace App\Models;
use PDO;
use Core\Model;

class DashboardModel extends Model
{
    public static function insert_personal($postData){
        unset($postData['defaultPic']);
        unset($postData['MAX_FILE_SIZE']);
        if (self::is_empty()){

            try{
                self::insert("personal_info",$postData);
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
//            $sql="INSERT INTO personal_info (fname,lname,avatar,job,instagram,telegram,linkedin,github) VALUES ('$fname','$lname','$postData[avatar]','$postData[job]','$postData[instagram]','$postData[telegram]','$postData[linkedin]','$postData[github]')";
        }else{
            if ($postData['avatar']===null){
                unset($postData['avatar']);
                $user_id=$postData['user_id'];
                unset($postData['user_id']);
                try{
                self::update("personal_info",$postData,"id='$user_id'");
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
//                $sql="UPDATE personal_info SET fname='$fname',lname='$lname',job='$postData[job]',instagram='$postData[instagram]',telegram='$postData[telegram]',linkedin='$postData[linkedin]',github='$postData[github]'";
            }else{
                $user_id=$postData['user_id'];
                unset($postData['user_id']);
                try{
                    self::update("personal_info",$postData,"id='$user_id'");
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
//                $sql="UPDATE personal_info SET fname='$fname',lname='$lname',avatar='$postData[avatar]',job='$postData[job]',instagram='$postData[instagram]',telegram='$postData[telegram]',linkedin='$postData[linkedin]',github='$postData[github]'";
            }

        }

    }
    public static function get_all_personal_info(){
        $db=static::getDB();
        $sql="SELECT * FROM personal_info";
        $result=$db->query($sql);
        if ($result->rowCount()>0){
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
    public static function is_empty(){
        $db=static::getDB();
        $result=$db->query("SELECT * FROM personal_info");
        if ($result->rowCount()<1) return true;
        return false;
    }
    public static function get_users(){
        $sql="SELECT fname,lname,id,bio FROM personal_info";
        $db=static::getDB();
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){
            var_dump($e);
        }
    }
    public static function get_all_skills(){
        $sql="SELECT * FROM `skills`";
        $db=static::getDB();
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){
            var_dump($e);
        }
    }
    public static function insert_skill($skillData){
        static::getDB();
        if (!empty($skillData['description'])){
            try{
                static::update("personal_info",[
                    'bio'=>$skillData['description'],
                ],"id='$skillData[user_id]'");
            }catch (\Exception $e){
                var_dump($e);
                return false;

            }

        }
        if (self::check_skill_existance($skillData)){
            try{
                static::update("skill_user",[
                    'skill_value'=>$skillData['skill_value']
                ],"skill_id='$skillData[skill_id]' AND user_id='$skillData[user_id]'");
            }catch (\Exception $e){
                var_dump($e);
                return false;

            }
        }else{
            try{
                static::insert("skill_user",[
                   'skill_id'=> $skillData['skill_id'],
                    'user_id'=>$skillData['user_id'],
                    'skill_value'=>$skillData['skill_value']
                ]);
            }catch (\Exception $exception){
                var_dump($exception);return false;
            }
        }
        return true;
    }
    public static function check_skill_existance($skillData){
        $db=static::getDB();
        $checkSql="SELECT * FROM skill_user WHERE user_id='$skillData[user_id]' And skill_id='$skillData[skill_id]'";
        $checkResult=$db->query($checkSql);
        if ($checkResult->rowCount()>0){
            return true;
        }
        return false;
    }
    public static function get_skills(){
        $sql="SELECT skills.skill_title,skills.color,skill_user.skill_id,skill_user.skill_value FROM skill_user INNER JOIN skills ON skill_user.skill_id=skills.skill_id";
        $db=static::getDB();
        try{
            $result=$db->query($sql);
            if ($result->rowCount()>0){
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\Exception $e){
            var_dump($e);
        }
    }

    public static function newSkill($data){
        try{
            static::insert("skills",$data);
            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    public static function delete_skill($id)
    {
        try{
            self::delete("skills","skill_id='$id'");
            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    public static function update_comment_state($comment_id)
    {
        $comment_id=self::prepare_input($comment_id);
        $db=self::getDB();
        $sql="UPDATE project_comments SET state=IF(state=1,0,1) WHERE id='$comment_id'";
        try{
            $res=$db->query($sql);
            if ($res){
                return true;
            }else{
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function delete_comment($comment_id)
    {
        $comment_id=self::prepare_input($comment_id);
        try{
            self::delete("project_comments","id='$comment_id'");
            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    public static function get_comment_full_message($comment_id)
    {
        $comment_id=self::prepare_input($comment_id);
        try{
            return self::select("project_comments","message","id='$comment_id'",true);
        }catch (\Exception $e){
            return false;
        }
    }

    public static function insertJobField($jobData)
    {
        try{

            $checkExistance=self::select("job_fields","id","title='$jobData[title]'",true);
            if ($checkExistance){
                $id=$checkExistance['id'];
                self::update("job_fields",$jobData,"id='$id'");
            }else{
                $id=self::genCode("job_fields","id");
                $jobData['id']=$id;
                self::insert("job_fields",$jobData);
            }
            return true;
        }catch (\Exception $e){
            return false;
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