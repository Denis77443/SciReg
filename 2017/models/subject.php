<?php


class subject {
    //put your code here
    public static function GetUserSubjects($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp.title, "
                              . "       sub_p.title AS title_p, "
                              . "       subp.flag, "
                              . "       concat('{Руководитель темы: ', "
                              . "                 users.surname,' ', "
                              . "                 users.name,' ', "
                              . "                 users.mname ,'}') AS subj_boss "
                              . "FROM user_sub "
                              . "LEFT JOIN subp ON find_in_set(subp.id, user_sub.sub) "
                              . "LEFT JOIN subp AS sub_p ON sub_p.id = subp.parent "
                              . "LEFT JOIN users ON users.uid = subp.hid "
                              . "WHERE user_sub.uid = ? ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;
    }
    
    /*
     * Выборка всех тем по категориям
     */
    public static function GetAllSubjectsByCat($value){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp00.parent AS parent, "
                                  . "subp00.title AS title,  "
                                  . "subp00.id AS id, "
                                  . "subp00.flag AS flag, "
                                  . "subp01.title AS title01, "
                                  . "subp01.id AS id_01, "
                                  . "subp01.hid AS hid_01, "
                                  . "       users.surname,"
                                  . "       concat(' ',LEFT(users.name, 1), '.') AS name, "
                                  . "       concat(' ',LEFT(users.mname, 1), '.') AS mname, "
                                  . "subp.flag AS flagg "
                           . "FROM subp "
                           . "LEFT JOIN subp AS subp00 ON subp00.id = subp.parent "
                           . "LEFT JOIN subp AS subp01 ON subp01.parent = subp00.id AND subp01.flag = '".$value."' "
                           . "LEFT JOIN users ON users.uid = subp01.hid "
                          // . "LEFT JOIN subp AS subp02 ON subp02.parent = subp01.id "
                           . "WHERE subp.flag = ? ");
        
        $sql->bindParam(1, $value, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;    
    }
    
    public static function GetSubSubject($id_01, $flag){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp.title, "
                                       . "       subp.hid, " 
                                       . "       users.surname,"
                                       . "       concat(' ',LEFT(users.name, 1), '.') AS name, "
                                       . "       concat(' ',LEFT(users.mname, 1), '.') AS mname "
                                       . "FROM subp "
                                       . "LEFT JOIN users ON users.uid = subp.hid " 
                                       . "WHERE parent = ? "
                                       . "AND flag = ? ");
        $sql->bindParam(1, $id_01, PDO::PARAM_INT);
        $sql->bindParam(2, $flag, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row; 
    }
    
    /*
     * Выборка Parent тем по флагу {3 флага или 3 категории тем}
     */
    public static function GetSubjectByFlag($flag){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp.id AS id, "
                               . "subp.title AS title, "
                               . "subp.flag AS flag "       
                        . "FROM subp "
                        . "WHERE subp.flag =? "
                        . "AND (subp.parent = '1' "
                        . "OR subp.parent = '4' "
                        . "OR subp.parent = '74')");
        
        $sql->bindParam(1, $flag, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;                   
    }
    
    /*
     * Выборка ТЕМЫ по выбранной Parent  
     */
    public static function GetSubjectByParent($flag, $id_subject){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp.id AS id, "
                               . "subp.title AS title, "
                               . "subp.flag AS flag "       
                        . "FROM subp "
                        . "WHERE subp.flag = ? "
                        . "AND subp.parent = ? ");
        
        $sql->bindParam(1, $flag, PDO::PARAM_INT);
        $sql->bindParam(2, $id_subject, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
       // var_dump($row);
        return $row;                   
    }
    
    /*
     * INSERT NEW ТЕМУ пользователю в БД в таблицу user_sub
     */
    
    public static function InsertSubject($user_id, $id_subject){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("INSERT INTO user_sub (uid, sub) VALUES (?,?)");
        
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->bindParam(2, $id_subject, PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }                
    }
    
    /*
     * Выборка поля SUB из таблицы user_sub
     */
    public static function GetFieldSub($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT user_sub.sub "
                              . "FROM user_sub "
                              . "WHERE user_sub.uid = ? ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;
    }
    
    /*
     * UPDATE поля SUB т.е. ТЕМ в которых учавствует пользователь
     */
    public static function UpdateSubject($user_id, $id_subject){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("UPDATE user_sub SET sub = ? "
                              . "WHERE uid = ? LIMIT 1");
        
        $sql->bindParam(1, $id_subject, PDO::PARAM_STR);
        $sql->bindParam(2, $user_id, PDO::PARAM_INT);
        
        $sql->execute();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }                
    }
    
    /*
     * Выборка ТЕМЫ ДЛЯ УДАЛЕНИЯ из профиля пользователя
     * темы выбираются из таблицы user_sub
     * и таблицы subp (в том случае если пользователь - руководитель темы, 
     * если текущий пользователь не руководитель темы, то там NULL )
     */
    
    public static function GetFieldSubAndHid($user_id, $id_sub){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT user_sub.sub, subp.hid "
                              . "FROM user_sub "
                              . "LEFT JOIN subp ON subp.id = ? AND subp.hid = ? "
                              . "WHERE user_sub.uid = ? LIMIT 1");
        $sql->bindParam(1, $id_sub, PDO::PARAM_INT);
        $sql->bindParam(2, $user_id, PDO::PARAM_INT);
        $sql->bindParam(3, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;
    }
    
    /*
     * UPDATE поля HID в таблице SUBP, 
     * т.е. при удалении темы у пользователя, 
     * в которой он являлся руководителем, 
     * удаляется руководство ТЕМОЙ
     * -----------------------------
     * и также назначается РУКОВОДИТЕЛЬ ТЕМЫ !!!
     */
    public static function UpdateHidInSubp($id_sub, $hid){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("UPDATE subp SET hid = $hid "
                              . "WHERE id = ? LIMIT 1");
        
        $sql->bindParam(1, $id_sub, PDO::PARAM_INT);
        
        $sql->execute();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }                
    }
}
