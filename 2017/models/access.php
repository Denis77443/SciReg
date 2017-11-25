<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of access
 *
 * @author denis
 */
class access {
    
    /*
     * Узнаем - имеет ли руководитель какого-либо подразделения 
     * доступ на список пользователей в подразделении
     */
    public static function GetAccess($user_id, $id_lab){
        $flag = FALSE;
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT un_sl.uid, un_sl.title, unit.title AS title_boss, unit.uid AS uid_boss "
                              . "FROM unit "
                              . "LEFT JOIN unit AS un_sl ON un_sl.u2 = unit.uid "
                                                      . "OR un_sl.u3 = unit.uid "
                                                      . "OR un_sl.u4 = unit.uid "
                              . "WHERE unit.hid = ? "
                              . "AND unit.id_post = (SELECT MAX(id_post) "
                                                  . "FROM unit WHERE hid = ?)");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);  
        $sql->bindParam(2, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        
        foreach($row as $key){
            if($key['uid'] == $id_lab){
                $flag = TRUE;
            }
            if($key['title_boss'] == 'ИЗМИРАН'){
                $flag = TRUE;
            }
            if($key['uid_boss'] == $id_lab){
                $flag = TRUE;
            }
        }
        //if(empty($id_lab))
        return $flag;
       // return $row;
    }
    
    /*
     * Является ли пользователь, на чью страницу пробует зайти рукрводитель, 
     * подчиненным данного руководителя {TRUE/FALSE}
     */
    public static function YourColleague ($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT "
                              .        "unit.hid AS hid_lid, "
                              .        "un2.hid AS hid_u2, "
                              .        "un3.hid AS hid_u3, "
                              .        "un4.hid AS hid_u4 "
                              . "FROM users "
                              . "LEFT JOIN unit ON unit.uid = users.lid "
                              . "LEFT JOIN unit AS un2 ON un2.uid = unit.u2 "
                              . "LEFT JOIN unit AS un3 ON un3.uid = unit.u3 "
                              . "LEFT JOIN unit AS un4 ON un4.uid = unit.u4 "
                              . "WHERE users.uid = ?");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT); 
        $sql->execute();
        $row = $sql->fetchAll();    
        return $row;
    }
    
    /*
     * Доступ СЕКРЕТАРЯ ОТДЕЛЕНИЯ на страницу пользователя 
     * принадлежащего данному ОТДЕЛЕНИЮ
     */
    public static function GetIdSecretary ($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT "
                              .        "un4.id_sec AS id_sec "
                              . "FROM users "
                              . "LEFT JOIN unit ON unit.uid = users.lid "
                              . "LEFT JOIN unit AS un4 ON un4.uid = unit.u4 "
                              . "WHERE users.uid = ?");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT); 
        $sql->execute();
        $row = $sql->fetchAll();    
        return $row;
    }
    
    
    /*
     * Пользователь - СУПЕРЮЗЕР {TRUE/FALSE}
     * Пользователь является суперюзером - доступ к любому на страницу
     * $user_id - id залогиненого пользователя
     */
    
    public static function GetSuperAccess ($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid FROM unit "
                              . "WHERE hid = ? AND title = 'ИЗМИРАН' ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            return TRUE;
        }else{
           return FALSE;  
        }
    }
    
    /*
     * Пользователь - НАУКА2 {TRUE/FALSE}
     */
    public static function GetTypeUser ($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid FROM users "
                              . "WHERE uid = ? AND sci = '2' LIMIT 1 ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            return TRUE;
        }else{
           return FALSE;  
        }
    }
}
