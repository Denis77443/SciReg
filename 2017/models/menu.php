<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu
 *
 * @author denis
 */
class menu {
    
    public static function getNameUser(){
        $db_pdo = DB::connection();
        
        $sql = $db_pdo->prepare("SELECT id_post, surname "
                              . "FROM users "
                              . "WHERE uid = ? LIMIT 1");
        $sql->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_LAZY);
       // echo '<br /> ID_POST in users table - '.$row['id_post'];
        return $row['surname'];
    }
    
    public static function getDepartment(){
        $otdel = "OR";
        //$this->db_pdo;
       $db_pdo = DB::connection();
        //$department = array();
        $level = 4;
        if(($_SESSION['lidlevel'] == 'highleader')OR
           ($_SESSION['lidlevel'] == 'dephead')OR($_SESSION['lidlevel'] == 'highhead')){
            $otdel = "AND";
        }
        
        $sql = $db_pdo->prepare("SELECT unit.title AS title, "
                              . "       unit.uid AS uid, "
                              . "       unit.hid AS hid, "
                              . "       unit_o.u4 AS u4_o, "
                              . "       unit_l.hid AS hid_l, "
                              . "       unit_l.title AS title_l, "
                              . "       unit_l.u3 AS u3_l, " // u3 in lab
                              . "       unit_l.uid AS uid_l, "// id of laboratories
                              . "       unit_s.title AS title_s, "
                              . "       unit_s.uid AS uid_s, "
                              . "       unit_s.u2 AS u2_s, "
                              . "       unit_s.hid AS hid_s, "
                              . "       unit_o.hid AS hid_o, "
                              . "       unit_o.title AS title_o, "
                              . "       unit_o.uid AS uid_o "
                              . "FROM unit "
                              . "LEFT JOIN unit AS unit_o ON unit_o.u4 = unit.uid "
                                                      . "AND unit_o.u2 = 0 "
                                                      . $otdel." unit_o.u3 = 0  "
                              . "LEFT JOIN unit AS unit_l ON unit_l.u3 = unit_o.uid "
                                                      . "AND unit_l.u4 = unit.uid "
                              . "LEFT JOIN unit AS unit_s ON unit_s.u2 = unit_o.uid "                        
                              . "WHERE unit.level = ? "
                              . "ORDER BY unit.uid ", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        $sql->bindParam(1, $level, PDO::PARAM_INT);
        $sql->execute();
     
        $result = $sql->fetchAll();
    //var_dump($result);
        return $result;
     
    }
    
    /*
     * Получение всех подразделений в которые входит подразделение 
     * с самым низшим уровнем (т.е. то подразделение, которое в себя 
     * не включает никакое подразделение)
     */
    public static function getDepartmentsLowLevel(){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT unit.title AS title_1, "
                              .        "unit.uid AS uid_1, "
                              . "       un_u2.title AS title_2, "
                              . "       un_u2.uid AS uid_2, "
                              . "       un_u3.title AS title_3, "
                              . "       un_u3.uid AS uid_3, "
                              . "       un_u4.title AS title_4, "
                              . "       un_u4.uid AS uid_4 "
                              . "FROM unit "
                              . "LEFT JOIN unit AS un_u2 ON un_u2.uid = unit.u2 "
                              . "LEFT JOIN unit AS un_u3 ON un_u3.uid = unit.u3 "
                              . "LEFT JOIN unit AS un_u4 ON un_u4.uid = unit.u4 "
                              . "WHERE unit.hid = ? ");
        $sql->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }
    
    
    public static function subjectLeader (){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp.hid, "
                                     . "concat(LEFT(subp.title, LENGTH(subp.title)/3),' [...]') AS title, "
                                     . "subp.id, "
                                     . "users.surname, "
                                     . "users.name, "
                                     . "users.mname, "
                                     . "user_sub.uid, "
                                     . "concat(users.surname,' '"
                                     .       ",LEFT(users.name, 1), '.'"
                                     . ",LEFT(users.mname, 1),'.') AS short_name "
                              . "FROM subp "
                           /*   . "LEFT JOIN user_sub ON user_sub.sub LIKE concat(subp.id,',%') "
                              . "                   OR user_sub.sub LIKE concat('%,',subp.id) "
                              . "                   OR user_sub.sub LIKE concat('%',subp.id) "
                              . "                   OR user_sub.sub LIKE concat('%,',subp.id,',%') "*/
                              . "LEFT JOIN user_sub ON find_in_set(subp.id, user_sub.sub) "
                              . "LEFT JOIN users ON users.uid = user_sub.uid "
                              . "WHERE hid = ?");
        $sql->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $sql->execute();
        
        $result = $sql->fetchAll();
    
        //var_dump($result);
        return $result;
        
        
    }
    
    public static function subjectDelete($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT subp.id, "
                              . "       concat(LEFT(subp.title, LENGTH(subp.title)/3),' [...]') AS title, "
                              . "       subp.hid, "
                              . "       user_sub.sub, "
                              . "       user_sub.uid "
                              . "FROM subp "
                              . "LEFT JOIN user_sub ON user_sub.uid = ? "
                              . "WHERE subp.hid = ? LIMIT 1");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetchAll();
        
        return $result;
    }
    
    public static function WhoCanDeleteSubj(){
       // echo 'Checking the BOSS';
       
        
       $db_pdo = DB::connection();
       $sql = $db_pdo->prepare("SELECT * FROM unit "
                             . "WHERE  (level = 4 AND hid = ?) OR id_sec = ? ");
       
       $sql->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
       $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
       $sql->execute();
       $row = $sql->rowCount();
       if($row > 0){
           return TRUE;
       }else{
           return FALSE;
       }
    }
    
    public static function WhoseSession($user_id){
       $i = '';
       
       $db_pdo = DB::connection();
       $sql = $db_pdo->prepare("SELECT unit.hid, "
                                    . "unit.uid, "
                                    . "unit.id_sec, "
                                    . "unit.title, "
                                    . "users.uid AS user_id, "
                                    . "unit_lid.u4 AS u4_lid, "
                                    . "users.lid "
                             . "FROM unit "
                             . "LEFT JOIN users ON users.uid = ? "
                             . "LEFT JOIN unit AS unit_lid ON unit_lid.uid = users.lid "
                             . "WHERE  (unit.level = 4 AND unit.hid = ?) "
                             . "OR unit.id_sec = ? ");
       
       $sql->bindParam(1, $user_id, PDO::PARAM_INT);
       $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
       $sql->bindParam(3, $_SESSION['user_id'], PDO::PARAM_INT);
       $sql->execute();
       $row = $sql->rowCount();
       
       if($row > 0){
           if($user_id == $_SESSION['user_id']){
                return TRUE;
           }else{
               while($row_query = $sql->fetch(PDO::FETCH_LAZY)){
                   // var_dump($row_query);
                    if($row_query['uid'] == $row_query['u4_lid']){
                        return TRUE;
                        $i = 1;
                    }
               }
               
               if(empty($i)){
                   return FALSE;
               }
               
           }
       }else{
           return FALSE;
       }        
    }
    
    public static function SubjectLeadship(){
       $db_pdo = DB::connection();
       $sql = $db_pdo->prepare("SELECT * FROM unit "
                             . "WHERE level = 4 AND hid = ? ");
       
       $sql->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
       $sql->execute();
       $row = $sql->rowCount();
       if($row > 0){
           return TRUE;
       }else{
           return FALSE;
       }
       
    }
    
    public static function DeleteAllSubjects($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT user_sub.sub, "
                              . "concat(LEFT(subp.title, LENGTH(subp.title)/3),' [...]') AS title, "
                                    . "subp.id "
                              . "FROM user_sub "
                              . "LEFT JOIN subp ON find_in_set(subp.id, user_sub.sub) "
                              . "WHERE user_sub.uid = ? AND user_sub.sub <> '' ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        //var_dump($sql);
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return FALSE;
        }
        
    }
    
    public static function SubjectWithoutLeader($user_id){
        $db_pdo = DB::connection();
        $var = menu::DeleteAllSubjects($user_id);
        if($var == FALSE){
            return FALSE;
        }else{
        
        foreach($var as $key){
            $subj[] = $key['id'];
        }
        
         $subj = implode(',', $subj);
         $sql = $db_pdo->prepare("SELECT id, "
                               . "concat(LEFT(title, LENGTH(title)/3), ' [...]') AS title "
                               . "FROM subp "
                               . "WHERE id IN (".$subj.") "
                               . "AND (hid IS NULL OR hid = 0)");
         $sql->execute();
         if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            foreach($row as $key){
                $result[$key['id']] = $key['title'];
            }
            return $result;
         }else{
             return FALSE;
         }
        }
    }
    
    public static function UserName($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT surname, name, mname "
                              . "FROM users WHERE uid = ? ");
        
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_LAZY);
       
        return $row['surname']." ".$row['name']." ".$row['mname'];
       
    }
    
    public static function UserLogin($user_id){
       $db_pdo = DB::connection(); 
       $sql = $db_pdo->prepare("SELECT uname "
                              . "FROM users WHERE uid = ? ");
        
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_LAZY);
       
        return $row['uname'];
    }
    
}
