<?php

class auth {
    //put your code here
    public static function CheckLP($login, $pass){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT users.uid, us_wp.uid AS uid_wp "
                              . "FROM users "
                              . "LEFT JOIN users AS us_wp ON us_wp.uname = ? AND us_wp.password =PASSWORD(?) "
                              . "WHERE users.uname = ? LIMIT 1");
                             // . "AND users.password =  PASSWORD(?)");
        $sql->bindParam(1, $login, PDO::PARAM_STR);
        $sql->bindParam(2, $pass, PDO::PARAM_STR);
        $sql->bindParam(3, $login, PDO::PARAM_STR);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;
    }
    
    /*
     * Поиск одинаковых LOGIN из таблицы USERS {TRUE/FALSE}
     */
    public static function CheckLogin($login){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT * FROM users WHERE uname = ? LIMIT 1");
        $sql->bindParam(1, $login, PDO::PARAM_STR);
        $sql->execute();
        
        if($sql->rowCount()>0){
            return FALSE;
        }else{ 
            return TRUE;
        }
    }
    
  
}
