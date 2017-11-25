<?php

class search {
    //put your code here
    public static function NumberOfUsers(){
        $db_pdo = DB::connection();
        $sql=$db_pdo->prepare("SELECT COUNT(uid) AS count FROM users");
        $sql->execute();
        if($sql->rowCount() > 0){
            $row = $sql->fetchAll();
            return $row;
        }else{
            return FALSE;
        }    
    }
    
    public static function GetFoundUsers($letter, $lidlevel){
        $db_pdo = DB::connection();
        
        if(($lidlevel == 'highleader')||($lidlevel == 'highhead')){
            $sql=$db_pdo->prepare("SELECT uid, sci, "
                                . "concat(surname,' ',name,' ',mname) AS name "
                                . "FROM users WHERE surname LIKE concat(?,'%') "
                                . "ORDER BY surname ");
            $sql->bindParam(1, $letter, PDO::PARAM_STR);
            
        }
        
         if($lidlevel == 'otdelleader'){
            $sql=$db_pdo->prepare("SELECT users.uid, users.sci, "
                                        . "concat(users.surname,' ',users.name,' ',users.mname) AS name "
                                . "FROM unit "
                                . "LEFT JOIN unit AS unit_otd ON unit_otd.u3 = unit.uid "
                                . "LEFT JOIN users ON users.lid = unit_otd.uid "
                                                . "AND users.surname LIKE concat(?,'%') "
                                . "WHERE unit.hid = ? "
                                . "AND unit.id_post = (SELECT MAX(id_post) "
                                                    . "FROM unit WHERE unit.hid = ? ) "); 
            $sql->bindParam(1, $letter, PDO::PARAM_STR);
            $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
            $sql->bindParam(3, $_SESSION['user_id'], PDO::PARAM_INT);
            
         }
         
         if($lidlevel == 'lableader'){
            $sql=$db_pdo->prepare("SELECT users.uid, users.sci, "
                                        . "concat(users.surname,' ',users.name,' ',users.mname) AS name, "
                                . "users_lid.uid AS uid2, users_lid.sci AS sci2, "
                                . "concat(users_lid.surname,' ',users_lid.name,' ',users_lid.mname) AS name2 "        
                                . "FROM unit "
                                . "LEFT JOIN unit AS unit_otd ON unit_otd.u2 = unit.uid "
                                . "LEFT JOIN users ON users.lid = unit_otd.uid "
                                                . "AND users.surname LIKE concat(?,'%') "
                                . "LEFT JOIN users AS users_lid ON users_lid.lid = unit.uid "
                                                . "AND users_lid.surname LIKE concat(?,'%') "
                                . "WHERE unit.hid = ? "
                                . "AND unit.id_post = (SELECT MAX(id_post) "
                                                    . "FROM unit WHERE unit.hid = ? ) "); 
            $sql->bindParam(1, $letter, PDO::PARAM_STR);
            $sql->bindParam(2, $letter, PDO::PARAM_STR);
            $sql->bindParam(3, $_SESSION['user_id'], PDO::PARAM_INT);
            $sql->bindParam(4, $_SESSION['user_id'], PDO::PARAM_INT);
            
         }
         
         if($lidlevel == 'dephead'){
             $sql=$db_pdo->prepare("SELECT users.uid, users.sci,"
                                        . "concat(users.surname,' ',users.name,' ',users.mname) AS name "
                                . "FROM unit "
                                . "LEFT JOIN unit AS unit_otd ON unit_otd.u4 = unit.uid "
                                . "LEFT JOIN users ON users.lid = unit_otd.uid "
                                                . "AND users.surname LIKE concat(?,'%') "
                                . "WHERE unit.hid = ? "
                                . "AND unit.id_post = (SELECT MAX(id_post) "
                                                    . "FROM unit WHERE unit.hid = ? ) "); 
            $sql->bindParam(1, $letter, PDO::PARAM_STR);
            $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
            $sql->bindParam(3, $_SESSION['user_id'], PDO::PARAM_INT);
         }
        
         if($lidlevel == 'secretaryhead'){
             $sql=$db_pdo->prepare("SELECT users.uid, users.sci,"
                                 .        "concat(users.surname,' ',users.name,' ',users.mname) AS name "
                                 ." FROM unit "
                                 . "LEFT JOIN unit AS unit_otd ON unit_otd.u4 = unit.uid "
                                 . "LEFT JOIN users ON users.lid = unit_otd.uid "
                                                    . "AND users.surname LIKE concat(?,'%') "
                                 . " WHERE unit.id_sec = ? ORDER BY users.surname");
             
             $sql->bindParam(1, $letter, PDO::PARAM_STR);
             $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
         }
         
         if($lidlevel == 'sectorleader'){
             $sql=$db_pdo->prepare("SELECT users.uid, users.sci,"
                                        . "concat(users.surname,' ',users.name,' ',users.mname) AS name "
                               
                                . "FROM unit "
                                //. "LEFT JOIN unit AS unit_otd ON unit_otd.u2 = unit.uid "
                                . "LEFT JOIN users ON users.lid = unit.uid "
                                                . "AND users.surname LIKE concat(?,'%') "
                                
                                . "WHERE unit.hid = ? "
                                . "AND unit.id_post = (SELECT MAX(id_post) "
                                                    . "FROM unit WHERE unit.hid = ? ) "); 
             $sql->bindParam(1, $letter, PDO::PARAM_STR);
             $sql->bindParam(2, $_SESSION['user_id'], PDO::PARAM_INT);
             $sql->bindParam(3, $_SESSION['user_id'], PDO::PARAM_INT);
         }
         
        
        $sql->execute();
        $row = $sql->fetchAll();
        //var_dump($row);
        return $row;
    }
}
