<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lidlevel
 * Class return level of leadship
 *
 * @author denis
 */
class lidlevel {
    //put your code here
    static function get_lid ($db_pdo, $session_id){
        return new lidlevel($db_pdo, $session_id);
    }
    
   
    private function __construct($db_pdo, $session_id) {
        $this->db_pdo     = $db_pdo;
        $this->session_id = $session_id;
    }

     public function nomber_level(){
         //$this->level = 0;
         $sql = $this->db_pdo->prepare("SELECT unit.title, "
                                     . "       unit.level, "
                                     . "       unit.u2, "
                                     . "       unit.u3, "
                                     . "       unit.hid, "
                                     . "       unit.id_sec, "
                                     . "       unit.id_post, "
                                     . "       un.hid AS hid_u4 "// Директор и зав. отделения
                                     . "FROM unit "
                                     . "LEFT JOIN unit AS un ON un.hid = ? AND un.level = 4 "   
                                     . "WHERE unit.hid = ? "
                                     . "AND unit.id_post = (SELECT MAX(id_post) "
                                               . "FROM unit WHERE hid = ? ) "
                                     . "OR unit.id_sec = ? LIMIT 2");
         
         $sql->bindParam(1,$this->session_id, PDO::PARAM_INT);
         $sql->bindParam(2,$this->session_id, PDO::PARAM_INT);
         $sql->bindParam(3,$this->session_id, PDO::PARAM_INT);
         $sql->bindParam(4,$this->session_id, PDO::PARAM_INT);
         $sql->execute();
         
         
        // $cols = $sql->rowCount();
         
         
         if($sql->rowCount()>0){
         
         while($row = $sql->fetch(PDO::FETCH_LAZY)){
             
             /*
              * Level leader of sector
              */
             if(($row['level'] == 1)&&($row['u2'] != 0)){
                 $this->level = 2; 
                 static::$user_type = 'sectorleader';
             }
             /*
              * Level leader of department
              */
              if($row['level'] == 4){
                  $this->level = 4;
                  static::$user_type = 'dephead';
              }
             
              /*
               * Level leader of laboratory
               */
             if(($row['level'] == 2)&&
                ($row['u2'] == 0)&&
                ($row['u3'] == 0)){
                 $this->level = 0;  
                 static::$user_type = 'lableader';
             }
             
             if(($row['level'] == 2)&&
                ($row['u2']    == 0)&&
                ($row['u3']    != 0)){
                 $this->level = 0; 
                 static::$user_type = 'lableader';
             }
               
             if(($row['level'] == 3)&&
                ($row['u2']    == 0)&&
                ($row['u3']    == 0)){
                 $this->level = 3;  
                 static::$user_type = 'otdelleader';
             }
             
             /*
              * Hight level rights {only 3 users}
              */
             if($row['title'] == 'ИЗМИРАН'){
                 $this->level = 1;
                 if($row['hid_u4'] != NULL){
                    static::$user_type = 'highhead'; // 
                 }else{
                    static::$user_type = 'highleader';
                 }
             }
             
             /*
              * Secretary
              */
             if($this->session_id == $row['id_sec']){
                static::$user_type = 'secretaryhead';  
             }
             
         }
         
         }else{
             
             $sql2 = $this->db_pdo->prepare("SELECT uid FROM users "
                                         . "WHERE sci = 2 AND uid = ?");
             
             $sql2->bindParam(1,$this->session_id, PDO::PARAM_INT);
             $sql2->execute();
             
             if($sql2->rowCount()>0){
                 static::$user_type = 'scince';  
             } else {
                static::$user_type = 'user';  
             }   
         } 
         // var_dump($this->level);
         return $this->level;
        // return $this->user_type;
        
     }


    public  $level;
    private $db_pdo;
    private $session_id;
    public static $user_type;
}
