<?php
class DB {
    
    public static function currientYear(){
      $var_sel = filter_input(INPUT_SERVER,'SCRIPT_NAME');
      $str1 = preg_match("/\d\d\d\d/", $var_sel, $match);
      return $match[0];
    }
    
    public static function connection(){
      $opt = array(
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      );
      
     $db_pdo = new PDO("mysql:host=localhost;dbname=scireg".DB::currientYear().";"
                     . "charset=utf8","sruser", "Stone77", $opt);
     return $db_pdo;
    }
}
