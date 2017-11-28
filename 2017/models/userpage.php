<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of userpage
 *
 * @author denis
 */
class userpage {
    //put your code here
    public static function GetSNMUser($user_id){
       $db_pdo = DB::connection();
       $sql = $db_pdo->prepare("SELECT name, surname, mname, email, phone, mobile, uname "
                             . "FROM users WHERE uid = ? LIMIT 1");
       $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
       $sql->execute();
       $row = $sql->fetch(PDO::FETCH_ASSOC);
       return $row;
    }
    
    //Users MAX post
    public static function GetPostUser($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT post.post_title "
                              . "FROM users "
                              . "LEFT JOIN post ON post.id_post = users.id_post "
                              . "WHERE users.uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row;
        
    }
    
    
    /*
     * Подразделения в которых находится сотрудник + 
     * если он является руководителем - должности
     */
    public static function GetDeptmUser($user_id){
        
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT unit.title AS title1, "
                               ."       un2.title AS title2, "
                               ."       un3.title AS title3, "
                               ."       un4.title AS title4, "
                               . "      post1.post_title AS p_title1, "
                               . "      post2.post_title AS p_title2, "
                               . "      post3.post_title AS p_title3, "
                               . "      post4.post_title AS p_title4 "
                               ."FROM users "
                               ."LEFT JOIN unit ON unit.uid = users.lid "
                               ."LEFT JOIN unit AS un2 ON un2.uid = unit.u2 "
                               ."LEFT JOIN unit AS un3 ON un3.uid = unit.u3 "
                               ."LEFT JOIN unit AS un4 ON un4.uid = unit.u4 "
                               ."LEFT JOIN post AS post1 ON post1.id_post = unit.id_post AND unit.hid = ? "
                               ."LEFT JOIN post AS post2 ON post2.id_post = un2.id_post AND un2.hid = ? "
                               ."LEFT JOIN post AS post3 ON post3.id_post = un3.id_post AND un3.hid = ? "
                               ."LEFT JOIN post AS post4 ON post4.id_post = un4.id_post AND un4.hid = ? "
                               ."WHERE users.uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(2, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(3, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(4, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(5, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        
        $row = array_chunk($row, 4);
        
        return $row;
    }
    
    /*
     * Название темы, в том случае, если пользователь руководитель
     */
    public static function GetSubjectHead($user_id){
         $db_pdo = DB::connection();
         $sql = $db_pdo->prepare("SELECT title FROM subp WHERE hid = ? ");
         $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
         $sql->execute();
         $row = $sql->fetchAll(PDO::FETCH_ASSOC);
         return $row;
    }
    
    /*
     * Установлены ли темы у пользователя.....{TRUE/FALSE}
     */
    public static function IssetSubject($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT id FROM user_sub WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }else{
            return false;
        }
        
    }
    
    /*
     * Задан ли ПЛАН у пользователя.....{TRUE/FALSE}
     */
    public static function IssetPlanNIR($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT plan, report "
                              . "FROM results WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return false;
        }  
    }
    
    /*
     * Заданы ли ОЖИДАЕМЫЕ РЕЗ у пользователя.....{TRUE/FALSE}
     */
    public static function IssetExpResults($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT expected_result "
                              . "FROM results WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return false;
        }  
    }
    
    /*
     * Отчёт пользователя.....{TRUE/FALSE}
     */
    public static function IssetReport($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT report "
                              . "FROM results WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return false;
        }  
    }
    
    /*
     * Результаты научной деятельности пользователя.....{TRUE/FALSE}
     */
    public static function IssetResultsOfSA($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT articles_in_russ, articles_in_foreign , "
                              . "monograph, reports_at_conf, lecture_course, "
                              . "patents, leadership, other "  
                              . "FROM results WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return false;
        }  
    }
    
    /*
     * Занесен ли пользователь в таблицу RESULTS....{TRUE/FALSE}
     */
    public static function IssetUserInReport($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid "
                              . "FROM results WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return false;
        }  
    }
    
    /*
     * Update Плана НИР и Ожидаемых результатов у пользователя
     */
    public static function UpdatePlanExpRes($user_id, $plan, $expres){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("UPDATE results SET plan = ?, expected_result = ? "
                              . "WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $plan ,PDO::PARAM_STR);
        $sql->bindParam(2, $expres ,PDO::PARAM_STR);
        $sql->bindParam(3, $user_id ,PDO::PARAM_INT);
        $sql->execute();
       // $row = $sql->rowCount();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
         
    }
    
    /*
     * Update записи 
     */
    public static function UpdateRepField($user_id, $field_name, $value){
        $db_pdo = DB::connection();
        
       // var_dump($field_name);
       // var_dump($db_pdo->quote($value));
        $sql = $db_pdo->prepare("UPDATE results SET ".$field_name." = ? WHERE uid = ? ");
        //var_dump($sql);
       // $sql->bindParam(1, $field_name ,PDO::PARAM_STR);
        $sql->bindParam(1, $value, PDO::PARAM_STR);
        $sql->bindParam(2, $user_id, PDO::PARAM_INT);
        $sql->execute();
        
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
        
    }
    
    /*
     * Insert записи
     */
    public static function InsertRepField($user_id, $field_name, $value){
        $db_pdo = DB::connection();
        
        $sql = $db_pdo->prepare("INSERT INTO results (uid, ".$field_name.") VALUES (?,?)");
       // $sql->bindParam(1, $field_name ,PDO::PARAM_STR);
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(2, $value ,PDO::PARAM_STR);
        
        $sql->execute();
        
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
        
    }
    
    /*
     * Update "Отчет" и "Результат научной деятельности" у пользователя
     */
    public static function UpdateRepRez($user_id, $text_report, $art_in_russ, 
                                        $art_in_for, $monograph, $rep_at_conf, 
                                        $lecture, $patents, $leadership, $other){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("UPDATE results "
                              . "SET report = ?, "
                              . "    articles_in_russ = ?, "
                              . "    articles_in_foreign = ?, "
                              . "    monograph = ?, "
                              . "    reports_at_conf = ?, "
                              . "    lecture_course = ?, "
                              . "    patents = ?, "
                              . "    leadership = ?, "
                              . "    other = ? "
                              . "WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $text_report ,PDO::PARAM_STR);
        $sql->bindParam(2, $art_in_russ ,PDO::PARAM_STR);
        $sql->bindParam(3, $art_in_for ,PDO::PARAM_STR);
        $sql->bindParam(4, $monograph ,PDO::PARAM_STR);
        $sql->bindParam(5, $rep_at_conf ,PDO::PARAM_STR);
        $sql->bindParam(6, $lecture ,PDO::PARAM_STR);
        $sql->bindParam(7, $patents ,PDO::PARAM_STR);
        $sql->bindParam(8, $leadership ,PDO::PARAM_STR);
        $sql->bindParam(9, $other ,PDO::PARAM_STR);
        $sql->bindParam(10, $user_id ,PDO::PARAM_INT);
        $sql->execute();
       // $row = $sql->rowCount();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
         
    }
    
    
    /*
     * Insert "Отчет" и "Результат научной деятельности" у пользователя
     */
    public static function InsertRepRez($user_id, $text_report, $art_in_russ, 
                                        $art_in_for, $monograph, $rep_at_conf, 
                                        $lecture, $patents, $leadership, $other){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("INSERT INTO results (uid, report, "
                                                   . "articles_in_russ, "
                                                   . "articles_in_foreign, "
                                                   . "monograph, "
                                                   . "reports_at_conf, "
                                                   . "lecture_course, "
                                                   . "patents, leadership, "
                                                   . "other) "
                               . "VALUES (?,?,?,?,?,?,?,?,?,?)");
         
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(2, $text_report ,PDO::PARAM_STR);
        $sql->bindParam(3, $art_in_russ ,PDO::PARAM_STR);
        $sql->bindParam(4, $art_in_for ,PDO::PARAM_STR);
        $sql->bindParam(5, $monograph ,PDO::PARAM_STR);
        $sql->bindParam(6, $rep_at_conf ,PDO::PARAM_STR);
        $sql->bindParam(7, $lecture ,PDO::PARAM_STR);
        $sql->bindParam(8, $patents ,PDO::PARAM_STR);
        $sql->bindParam(9, $leadership ,PDO::PARAM_STR);
        $sql->bindParam(10, $other ,PDO::PARAM_STR);
        
        $sql->execute();
       // $row = $sql->rowCount();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
         
    }
    
    
    
    /*
     * Insert Плана НИР и Ожидаемых результатов в таблицу RESULTS
     */
    public static function InsertPlanExpRes($user_id, $plan, $expres){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("INSERT INTO results (uid, plan, expected_result) "
                              . "VALUES (?,?,?) ");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(2, $plan ,PDO::PARAM_STR);
        $sql->bindParam(3, $expres ,PDO::PARAM_STR);
        
        $sql->execute();
       // $row = $sql->rowCount();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
         
    }
    /*
     * Update любую запись в таблице Results
     */
    public static function UpdateAnyRecord($user_id, $table_name, $field_name, $field_value){
        $db_pdo = DB::connection();
        
        if($field_name == 'password'){
           $sql = $db_pdo->prepare("UPDATE ".$table_name." SET ".$field_name." = PASSWORD(?) "
                                . "WHERE uid = ? LIMIT 1");  
        } else { 
           $sql = $db_pdo->prepare("UPDATE ".$table_name." SET ".$field_name." = ? "
                                . "WHERE uid = ? LIMIT 1");
        }
        
      //  $sql->bindParam(1, $field_name ,PDO::PARAM_STR);
        $sql->bindParam(1, $field_value ,PDO::PARAM_STR);
        $sql->bindParam(2, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    /*
     * Insert любую запись
     */
    public static function InsertAnyRecord($table, $field_names, $field_values){
        $db_pdo = DB::connection();
        
        
        $sql = $db_pdo->prepare("INSERT INTO ".$table." (".$field_names.") "
                              . "VALUES (".$field_values.") ");
       // $sql->bindParam(1, $field_values, PDO::PARAM_STR);
       
        $sql->execute();
        
        if ($sql->rowCount() == 0){ return FALSE; }else{ return TRUE; }
    }
    
    
    public static function InsertRecord($user_id, $table, $field_name, $field_value){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("INSERT INTO ".$table." (uid, ".$field_name.") "
                              . "VALUES (?,?) ");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(2, $field_value ,PDO::PARAM_STR);
       
        
        $sql->execute();
       // $row = $sql->rowCount();
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
        
    }
    
    
    
    /*
     * Select любую запись в таблиц
     */
    public static function SelectAnyRecord($user_id, $field_name, $table_name){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT ".$field_name." FROM ".$table_name." "
                              . "WHERE uid = ? LIMIT 1");
      //  $sql->bindParam(1, $field_name ,PDO::PARAM_STR);
        
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->execute();
        
        if($sql->rowCount() == 0){
            return FALSE;
        }else{
            $row = $sql->fetchAll();
            return $row;
        }
    }
    
    /*
     * Update таблицы unit HID установка руководителя для подразделения
     */
    public static function UpdateHid($uid, $user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("UPDATE unit SET hid = ? WHERE uid = ? ");
        $sql->bindParam(1, $user_id ,PDO::PARAM_INT);
        $sql->bindParam(2, $uid ,PDO::PARAM_INT);
        $sql->execute();
    }
    
}
