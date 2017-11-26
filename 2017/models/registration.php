<?php

class registration {
    /*
     * Возвращает все должности из таблицы POST
     */
    public static function GetPost(){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT id_post, post_title FROM post");
        $sql->execute();
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    /*
     * Возвращает отделения
     */
    public static function GetDepartment(){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid, title FROM unit WHERE level=4");
        $sql->execute();
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    /*
     * Возвращает подразделения по ID выбранного отделения
     */
    public static function GetDivision($id_depart){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT unit.uid, unit.title, unit.u2, unit.u3 "
                              . "FROM unit "
                              . "WHERE unit.level<=2 AND unit.u4 = ? GROUP BY unit.uid ");
        
        $sql->bindParam(1, $id_depart,PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /*
     * Возвращает ШИФРЫ подразделений в которых пользователь является 
     * руководителем 
     */
    public static function GetListOfCipher($login){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT s1, s2, s3, s4, s5 "
                             . "FROM leadship WHERE login_leadship = ? LIMIT 1");
        $sql->bindParam(1, $login ,PDO::PARAM_STR);
        
        $sql->execute();
        
        if ($sql->rowCount() == 0) {
            return FALSE;
        } else {
          $row = $sql->fetchAll(PDO::FETCH_ASSOC);
          return $row[0];
        }
        
        
    }
    
    /*
     * Возвращает id подразделения по номеру ШИФРА
     */
    public static function GetUnitId($cipher){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid "
                             . "FROM unit WHERE cipher = ? LIMIT 1");
        $sql->bindParam(1, $cipher, PDO::PARAM_INT);
        
        $sql->execute();
       if($sql->rowCount() == 0){
            return FALSE;
        }else{
            $row = $sql->fetchAll();
            return $row[0];
        }
    }
    
    /*
     * Возвращает id пользователя (uid) по логину
     */
    public static function GetUserId($login){
       $db_pdo = DB::connection();
       $sql = $db_pdo->prepare("SELECT uid "
                             . "FROM users WHERE uname = ? LIMIT 1");
        $sql->bindParam(1, $login, PDO::PARAM_INT);
        
        $sql->execute(); 
        $row = $sql->fetchAll();
        return $row[0]['uid'];
    }
    
    /*
     * Возвращает lid (id подразделения в которое входит руководитель, 
     * т.е. подразделение в котором у руководителя самый маленький id post)
     */
    public static function GetLidForHeader($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid "
                             . "FROM unit WHERE hid = ? AND id_post = (SELECT MIN(id_post) FROM unit WHERE hid = ? )");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->bindParam(2, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row[0]['uid'];
    }
    
    /*
     * Возвращает MAX post руководителя 
     */
    public static function GetPostForHeader($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT MAX(id_post) AS post "
                             . "FROM unit WHERE hid = ? ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row[0]['post'];
    }
}
