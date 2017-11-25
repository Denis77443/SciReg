<?php


class departments {
    //put your code here
    public static function GetDepartment($id_deprt){
        $db_pdo = DB::connection();
        $sql=$db_pdo->prepare("SELECT "
                                . "unit.hid, "
                                . "concat(users.surname, ' ',"
                                . "concat(LEFT(users.name, 1),'.'), "
                                . "concat(LEFT(users.mname, 1),'.'),' "
                                . "[',post.post_title,']') AS name, "
                                . "un_in.title, "
                                . "un_in.uid "
                            . "FROM unit "
                            . "LEFT JOIN users ON users.uid = unit.hid "
                            . "LEFT JOIN post ON post.id_post = unit.id_post "
                            . "LEFT JOIN unit AS un_in ON un_in.u4 = unit.uid "
                            . "WHERE unit.uid = ? ");
        $sql->bindParam(1, $id_deprt, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetchAll();
        return $row;
    }
    
    public static function GetLaboratory($id_lab){
        $db_pdo = DB::connection();
        $sql=$db_pdo->prepare("SELECT "
                                . "unit.hid, "
                                . "concat(users.surname, ' ',"
                                . "concat(LEFT(users.name, 1),'.'), "
                                . "concat(LEFT(users.mname, 1),'.'),' "
                                . "[',post.post_title,']') AS name, "
                                . "un_lab.title, "
                                . "un_lab.uid, "
                                . "un_sec.title AS title_sec, "
                                . "un_sec.uid AS uid_s, "
                                . "unit.title AS main_title "
                            . "FROM unit "
                            . "LEFT JOIN users ON users.uid = unit.hid "
                            . "LEFT JOIN post ON post.id_post = unit.id_post "
                            . "LEFT JOIN unit AS un_lab ON un_lab.u3 = unit.uid "
                            . "LEFT JOIN unit AS un_sec ON un_sec.u2 = unit.uid "
                            . "WHERE unit.uid = ? ");
        $sql->bindParam(1, $id_lab, PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return FALSE;
        }    
    }
    
    public static function GetUsersInDivis($id_lab){
        $db_pdo = DB::connection();
     /*   $sql=$db_pdo->prepare("SELECT concat(users.surname, ' ', "
                                          . "users.name, ' ', "
                                          . "users.mname) AS name, "
                                          . "users.uid AS user_id, "
                            . "       concat(users_lead.surname,' ',"
                                          . "users_lead.name,' ',"
                                          . "users_lead.mname ,' "
                                          . "[',post.post_title,']') AS boss, "
                                          . "users_lead.uid AS boss_id, "
                                          . "unit.title, unit.uid "
                            . "FROM users "
                            . "LEFT JOIN unit ON unit.uid = users.lid "
                            . "LEFT JOIN users AS users_lead ON users_lead.uid = unit.hid "
                            . "LEFT JOIN post ON post.id_post = unit.id_post "
                            . "WHERE users.lid = ?  "
                            . "ORDER BY users.surname ");*/
        
        $sql=$db_pdo->prepare("SELECT  "
                            . "       concat(users.surname,' ',"
                                          . "users.name,' ',"
                                          . "users.mname ,' "
                                          . "[',post.post_title,']') AS boss, "
                                          . "users.uid AS boss_id, "
                                          . "unit.title, "
                                          . "unit.uid, "
                                          ."concat(users_in.surname, ' ', "
                                          . "users_in.name, ' ', "
                                          . "users_in.mname) AS name, "
                                          . "users_in.uid AS user_id "
                 
                           
                            . "FROM unit "
                            . "LEFT JOIN users AS users_in ON users_in.lid = unit.uid "
                                                       . "AND users_in.uid <> unit.hid "
                            . "LEFT JOIN users ON users.uid = unit.hid "
                            . "LEFT JOIN post ON post.id_post = unit.id_post "
                            . "WHERE unit.uid = ? ORDER BY users_in.surname ");
        
        $sql->bindParam(1, $id_lab, PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll();
            return $row;
         }else{
             return FALSE;
         }
    }
}
