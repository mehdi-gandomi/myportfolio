<?php


namespace Core;

use PDO;
use App\Config;
abstract class Model
{
    private static $db;
    protected static function prepare_input($input){
        return trim(htmlspecialchars(stripcslashes($input)));
    }
    protected static function getDB(){

        if (self::$db==null){
//            $host=Config::DB_HOST;
//            $dbname=Config::DB_NAME;
//            $user=Config::DB_USER;
//            $pass=Config::DB_PASSWORD;
            try{
                self::$db=new PDO("mysql:host=".Config::DB_HOST.";dbname=".Config::DB_NAME.";charset=utf8",Config::DB_USER,Config::DB_PASSWORD);
                self::$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
               return self::$db;
            }
            catch (\PDOException $e){
                echo $e->getMessage();
            }
        }
        return self::$db;
    }
    public static function insert($tbl_name,$data,$htmlEntity=true){
        $db=self::getDB();
        $arrayKeys=array_keys($data);
        $lastField=end($arrayKeys);
        $sql="INSERT INTO $tbl_name (";
        $values="VALUES(";

        foreach ($data as $key=>$value){
            if ($htmlEntity){
                $key=self::prepare_input($key);
                $value=self::prepare_input($value);
            }
            if ($key==$lastField){
                $sql.="$key".")";
                $values.="'$value')";
                break;
            }
            $sql.="$key,";
            $values.="'$value',";
        }
        $sql.=" ".$values.";";

        return $db->query($sql);

    }
    protected static function select($tblName,$fields="*",$where="1",$is_one=false,$options=false,$count=false){
        $db=static::getDB();
        $sql="SELECT ".self::prepare_input($fields)." FROM $tblName";

        if ($options){
            $options=self::prepare_input($options);
            $sql.=" $options";
        }
        if ($where){
            $sql.=" WHERE $where";
        }

        $result=$db->query($sql);
        if ($count){
            return $result->rowCount();
        }
        if ($is_one){
            if ($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        }
        if ($result->rowCount()>0){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result->rowCount();
    }
    protected static function update($tbl_name,$data,$where){
        $db=static::getDB();
        $arrayKeys=array_keys($data);
        $lastField=end($arrayKeys);
        $sql="UPDATE $tbl_name SET ";
        foreach ($data as $key=>$value){
            $key=self::prepare_input($key);
            $value=self::prepare_input($value);
            if ($key==$lastField){
                $sql.="$key='$value'";
                break;
            }
            $sql.="$key='$value',";
        }
        $where=self::prepare_input($where);
        $sql.=" WHERE $where";
        return $db->query($sql);
    }
    protected static function delete($tbl_name,$where){
        $db=static::getDB();
        $where=self::prepare_input($where);
        $sql="DELETE FROM $tbl_name WHERE $where";
        return $db->query($sql);
    }
    public static function genCode($tbl_name,$field){
        $codes=[];
        $result=self::select($tbl_name,$field);
        if ($result){
            foreach ($result as $row){
                array_push($codes,$row[$field]);
            }
            $missingNum=self::missing_number($codes);
            if (!empty($missingNum)){
                return $missingNum;
            }
            else{
                $maxCode=self::select($tbl_name,"MAX($field) as code","1",true);
                $result=(int)($maxCode['code'])+1;
                return $result;
            }
        }
        else{
            return 1;
        }

    }
    public static function missing_number($num_list)
    {
            $new_arr = range($num_list[0],max($num_list));
            $res=array_values(array_diff($new_arr, $num_list));
            return array_shift($res);
    }

}