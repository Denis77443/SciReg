<?php
//Latex Model

class latex {
    public static function GetDataForLatex($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT "
                      . "       user_sub.sub AS sub, "
                      . "       concat(users.surname,' ',users.name,' "
                      .             "',users.mname,',  ', post.post_title) AS name_post, "
                      . "       users.uname AS uname, "
                      . "       unit.title AS title_first, "
                      . "       unit_2.title AS title_2, "
                      . "       unit_3.title AS title_3, "
                      . "       unit_4.title AS title_4, "
                      . "subp.title, "
                      . "       results.report AS report "
                      . "FROM user_sub "
                      . "LEFT JOIN users ON users.uid = user_sub.uid "
                      . "LEFT JOIN unit ON unit.uid = users.lid "
                      . "LEFT JOIN unit AS unit_2 ON unit_2.uid = unit.u2 "
                      . "LEFT JOIN unit AS unit_3 ON unit_3.uid = unit.u3 "
                      . "LEFT JOIN unit AS unit_4 ON unit_4.uid = unit.u4 "
                      . "LEFT JOIN post ON post.id_post = users.id_post "
                      . "LEFT JOIN results ON results.uid = user_sub.uid "
                      . "LEFT JOIN subp ON find_in_set(subp.id, user_sub.sub) "
                      . "WHERE user_sub.uid = ? LIMIT 1");

        $sql->bindParam(1,$user_id, \PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount()>0){
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }else{
            //return false;
            
            $sql = $db_pdo->prepare("SELECT "
                                  . "concat(users.surname,' ',users.name,' "
                                  . " ',users.mname,',  ', post.post_title) AS name_post, "
                                  . " users.uname AS uname, "
                                  . "       unit.title AS title_first, "
                                  . "       unit_2.title AS title_2, "
                                  . "       unit_3.title AS title_3, "
                                  . "       unit_4.title AS title_4, "
                                  . "       results.report AS report "
                                  . "FROM users "
                                  . "LEFT JOIN unit ON unit.uid = users.lid "
                                  . "LEFT JOIN unit AS unit_2 ON unit_2.uid = unit.u2 "
                                  . "LEFT JOIN unit AS unit_3 ON unit_3.uid = unit.u3 "
                                  . "LEFT JOIN unit AS unit_4 ON unit_4.uid = unit.u4 "
                                  . "LEFT JOIN post ON post.id_post = users.id_post "
                                  . "LEFT JOIN results ON results.uid = users.uid "
                                  . "WHERE users.uid = ? ");
            
            $sql->bindParam(1,$user_id, \PDO::PARAM_INT);
            $sql->execute();
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $row;
            
        }
    }
}
