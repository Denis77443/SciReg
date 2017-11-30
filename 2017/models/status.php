<?php

class status {
    //put your code here
    public static function GetUsersInDepartment($user_id, $lidlevel){
         $db_pdo = DB::connection();
         
         if($lidlevel == 'lableader'){
         $sql = $db_pdo->prepare("SELECT users.uid, users_otd.uid AS uid_otd, users_otd.sci, users.sci AS sci2,"
                               . "concat(users.surname, ' ', "
                               .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                               .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                               . "concat(users_otd.surname, ' ', "
                               .        "concat(LEFT(users_otd.name, LENGTH(1)),'.'), ' ', "
                               .        "concat(LEFT(users_otd.mname, LENGTH(1)),'.')  ) AS name_otd, "
                               .        "results.report, "
                                    .        "results.expected_result AS exp, "
                                    .        "results.plan, "
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "unit_dep.title, unit_dep.uid AS uid_dep, "
                                    . "unit_otd.title AS title2, "
                                    . "unit_otd.uid AS uid_dep2, "
                                    . "results.other AS other, "
                               . "user_sub.sub "
                               . "FROM unit "
                               . "LEFT JOIN unit AS unit_otd ON unit_otd.u2 = unit.uid "
                               . "LEFT JOIN users AS users_otd ON users_otd.lid = unit_otd.uid "//Yanke
                               . "LEFT JOIN users ON users.lid = unit.uid "//Hegai
                               . "LEFT JOIN results ON results.uid = users_otd.uid OR results.uid = users.uid "
                               . "LEFT JOIN user_sub ON user_sub.uid = users_otd.uid OR user_sub.uid = users.uid "
                               . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = users.lid "
                            //   . "LEFT JOIN unit AS unit_u2 ON unit_u2.u2 = unit.uid "       //LISIN
                               . "WHERE unit.hid = ? "
                               . "AND unit.id_post = (SELECT MAX(unit.id_post) "
                                                   . "FROM unit WHERE unit.hid = ?) "
                              . "ORDER BY unit_dep.title, users.surname ");
         $sql->bindParam(1, $user_id, PDO::PARAM_INT);
         $sql->bindParam(2, $user_id, PDO::PARAM_INT);
         }
         
          if(($lidlevel == 'highleader')||($lidlevel == 'highhead')){
              $sql = $db_pdo->prepare("SELECT users.uid, users.sci, "
                                    . "concat(users.surname, ' ', "
                                    .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                                    .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                                    .        "results.report, "
                                    .        "results.expected_result AS exp, "
                                    .        "results.plan, "
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "results.other AS other, "
                                    . "unit_dep.title, unit_dep.uid AS uid_dep, "
                                    .        "user_sub.sub "
                                    . "FROM users "
                                    . "LEFT JOIN results ON results.uid = users.uid "
                                    . "LEFT JOIN user_sub ON user_sub.uid = users.uid "
                                    . "LEFT JOIN unit ON unit.uid = users.lid "
                                    . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = unit.u4 "
                                    . "ORDER BY unit_dep.title DESC, users.surname ASC");
                      
          }
         
          if ($lidlevel == 'sectorleader'){
              
              $sql = $db_pdo->prepare("SELECT users.uid, users.sci, "
                                    . "concat(users.surname, ' ', "
                                    .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                                    .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                                    .        "results.report, "
                                    .        "results.expected_result AS exp, "
                                    .        "results.plan, "
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "results.other AS other, "
                                    . "unit.title, unit_dep.uid AS uid_dep,  "
                                    .        "user_sub.sub "
                                    . "FROM unit "
                                    . "LEFT JOIN users ON users.lid = unit.uid "
                                    . "LEFT JOIN results ON results.uid = users.uid "
                                    . "LEFT JOIN user_sub ON user_sub.uid = users.uid "
                                    . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = users.lid "
                                    . "WHERE unit.hid = ? "
                                    . "AND unit.id_post = (SELECT MAX(unit.id_post) "
                                                        . "FROM unit WHERE unit.hid = ?) "
                                    . "ORDER BY users.surname ");
              
              $sql->bindParam(1, $user_id, PDO::PARAM_INT);
              $sql->bindParam(2, $user_id, PDO::PARAM_INT);
              
          }
          
          if($lidlevel == 'otdelleader'){
              $sql = $db_pdo->prepare("SELECT users.uid, users.sci, "
                                    . "concat(users.surname, ' ', "
                                    .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                                    .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                                    .        "results.report, "
                                    .        "results.expected_result AS exp, "
                                    .        "results.plan, "
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "results.other AS other, "
                                    . "unit_dep.title, unit_dep.uid AS uid_dep, "
                                    .        "user_sub.sub "
                                    . "FROM unit "
                                    . "LEFT JOIN unit AS unit_otd ON unit_otd.u3 = unit.uid "
                                    . "LEFT JOIN users ON users.lid = unit_otd.uid "
                                    . "LEFT JOIN results ON results.uid = users.uid "
                                    . "LEFT JOIN user_sub ON user_sub.uid = users.uid "
                                    . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = users.lid "
                                    . "WHERE unit.hid = ? "
                                    . "AND unit.id_post = (SELECT MAX(unit.id_post) "
                                                        . "FROM unit WHERE unit.hid = ?) "
                                    . "ORDER BY unit_dep.title, users.surname ");
              $sql->bindParam(1, $user_id, PDO::PARAM_INT);
              $sql->bindParam(2, $user_id, PDO::PARAM_INT);
          }
          
          if($lidlevel == 'dephead'){
             $sql = $db_pdo->prepare("SELECT users.uid, users.sci, "
                                    . "concat(users.surname, ' ', "
                                    .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                                    .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                                    . "results.report, "
                                    . "results.expected_result AS exp, "
                                    . "results.plan, "
                                   /* . "TRIM(results.articles_in_russ) AS air, "
                                    . "TRIM(results.articles_in_foreign) AS aif, "
                                    . "TRIM(results.monograph) AS mono, "
                                    . "TRIM(results.reports_at_conf) AS conf, "
                                    . "TRIM(results.lecture_course) AS course, "
                                    . "TRIM(results.patents) AS patents, "
                                    . "TRIM(results.leadership) AS leader, "
                                    . "TRIM(results.other) AS other, "*/
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "results.other AS other, "
                                    . "unit_dep.title, unit_dep.uid AS uid_dep, "
                                    . "user_sub.sub "
                                    . "FROM unit "
                                    . "LEFT JOIN unit AS unit_otd ON unit_otd.u4 = unit.uid "
                                    . "LEFT JOIN users ON users.lid = unit_otd.uid "
                                    . "LEFT JOIN results ON results.uid = users.uid "
                                    . "LEFT JOIN user_sub ON user_sub.uid = users.uid "
                                    . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = users.lid "
                                    . "WHERE unit.hid = ? "
                                    . "AND unit.id_post = (SELECT MAX(unit.id_post) "
                                                        . "FROM unit WHERE unit.hid = ?) "
                                    . "AND users.uid IS NOT NULL "
                                    . "ORDER BY unit_dep.title DESC, users.surname ASC", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
              $sql->bindParam(1, $user_id, PDO::PARAM_INT);
              $sql->bindParam(2, $user_id, PDO::PARAM_INT); 
              
          }
          
          if($lidlevel == 'secretaryhead'){
                $sql = $db_pdo->prepare("SELECT  unit.uid AS id_dep, unit.title AS title_dep, users.sci, "
                                    . "users.uid, "
                                    . "concat(users.surname, ' ', "
                                    .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                                    .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                                    . "results.report, "
                                    . "results.expected_result AS exp, "
                                    . "results.plan, "
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "results.other AS other, "
                                    . "unit_dep.title, unit_dep.uid AS uid_dep, "
                                    . "user_sub.sub "
                                      . "FROM unit "
                                      . "LEFT JOIN unit AS un_sec ON un_sec.u4 = unit.uid "
                                      . "LEFT JOIN users ON users.lid = un_sec.uid "
                                      . "LEFT JOIN results ON results.uid = users.uid "
                                      . "LEFT JOIN user_sub ON user_sub.uid = users.uid "
                                      . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = users.lid "
                                      . "WHERE unit.id_sec = ? "
                                      . "ORDER BY unit_dep.uid, users.surname ");
                $sql->bindParam(1, $user_id, PDO::PARAM_INT);
          }
          
          
          
         $sql->execute();
         $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        // $row = $sql->fetch();
        //var_dump($row);
         return $row;
        
    }
    
    //
    //28.10.2017
    //Общее количество пользователей в Отделении
    //
    public static function CountOfDep($name_dep, $sci, $lidlevel){
        $db_pdo = DB::connection();
        if(($lidlevel == 'highleader')||($lidlevel == 'highhead')){
        $sql = $db_pdo->prepare("SELECT COUNT(users.uid) AS count FROM unit "
                              . "LEFT JOIN unit AS unit_lab ON unit_lab.u4 = unit.uid "
                              . "LEFT JOIN users ON users.lid = unit_lab.uid AND users.sci = ? "
                              . "WHERE unit.title = ? ");
        }
        if( ($lidlevel == 'dephead')||($lidlevel == 'otdelleader')||
            ($lidlevel == 'lableader')||($lidlevel == 'secretaryhead')||
            ($lidlevel == 'sectorleader') ){
        $sql = $db_pdo->prepare("SELECT COUNT(users.uid) AS count FROM unit "
                            //  . "LEFT JOIN unit AS unit_lab ON unit_lab.u2 = unit.uid "
                              . "LEFT JOIN users ON users.lid = unit.uid AND users.sci = ? "
                              . "WHERE unit.title = ? ");
        }
      
        
        $sql->bindParam(1, $sci, PDO::PARAM_INT);
        $sql->bindParam(2, $name_dep, PDO::PARAM_STR);
        
        $sql->execute();
        $row = $sql->fetchAll();
       // var_dump($name_dep);
     //   var_dump($row);
        return $row[0];
        
    }
    
    /*
     * 29.10.2017
     * 
     * Вывод ОТДЕЛЕНИЙ в "легенду" в пункте СТАТУС для 
     * категории пользователей - СЕКРЕТАРЬ 
     * 
     */
    public static function GetDepForSecretary($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid, title FROM unit WHERE id_sec = ? ");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        
        //$row = $sql->fetchAll();
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        
        foreach ($row as $key){
            $result[$key['uid']] = $key['title'];
           // echo '<h2>'.$key['uid'].'</h2>';
            //$result[$value];
        }
        
        return $result;
    }
    
    /*
     * Вывод "СТАТУСа" результатов пользователя для графического представления выполнения 
     */
    public static function GetResultsForStatus($user_id){
         $db_pdo = DB::connection();
         $sql = $db_pdo->prepare("SELECT * FROM results WHERE uid = ? LIMIT 1");
         $sql->bindParam(1, $user_id, PDO::PARAM_INT);
         $sql->execute();
         
         $row = $sql->fetchAll(PDO::FETCH_ASSOC);
         
         //var_dump($row);
         if($sql->rowCount()>0){
            
            return $row[0];
         }else{
             return false;
         }
        
    }
    
    /*
     * Установлены ли темы у пользователей
     */
    public static function GetSubjectsForStatus($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT * FROM user_sub WHERE uid = ? LIMIT 1");
        
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
         
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        if($sql->rowCount()>0){
            
            return true;
         }else{
             return false;
         }
    }
    
    /*
     * Показать пользователей участвующих в заданной теме
     */
    public static function ShowSubjectUsers($id_subject){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT uid FROM user_sub "
                              . "WHERE find_in_set(".$id_subject.", user_sub.sub) ");
        
      //  $sql->bindParam(1, $id_subject, PDO::PARAM_INT);
        $sql->execute();
        
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
   /*
    * Вернуть Фамилию И.О. пользователя
    */
    public static function ShowNameUsers($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT concat(users.surname, ' ', "
                               .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                               .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name "
                               . "FROM users WHERE users.uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->execute();
        
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['name'];
    }
    
    /*
    * Вернуть СТАТУС выполнения пользователя
    */
    public static function ShowStatUsers($user_id){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT results.plan, "
                                      ."results.expected_result, "
                                      ."results.report, "
                                      ."results.articles_in_russ AS air, "
                                      ."results.articles_in_foreign AS aif, "
                                      ."results.monograph AS mono, "
                                      ."results.reports_at_conf AS conf, "
                                      ."results.lecture_course AS course, "
                                      ."results.patents AS patents, "
                                      ."results.leadership AS leader, "
                                      ."results.other AS other, "
                              . "       user_sub.sub "
                              . "FROM users "
                              . "LEFT JOIN results ON results.uid = ? "
                              ." LEFT JOIN user_sub ON user_sub.uid = ? "
                              . "WHERE users.uid = ? LIMIT 1");
        $sql->bindParam(1, $user_id, PDO::PARAM_INT);
        $sql->bindParam(2, $user_id, PDO::PARAM_INT);
        $sql->bindParam(3, $user_id, PDO::PARAM_INT);
        $sql->execute();
        
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($row);
        return $row[0];
    }
    
    /*
     * Вернуть статистику выполнения отчёта Директора
     */
    public static function GetCEOInfo($id_post){
        $db_pdo = DB::connection();
        $sql = $db_pdo->prepare("SELECT  unit.uid AS id_dep, unit.title AS title_dep, users.sci, "
                                    . "users.uid, "
                                    . "concat(users.surname, ' ', "
                                    .        "concat(LEFT(users.name, LENGTH(1)),'.'), ' ', "
                                    .        "concat(LEFT(users.mname, LENGTH(1)),'.') ) AS name, "
                                    . "results.report, "
                                    . "results.expected_result AS exp, "
                                    . "results.plan, "
                                    . "results.articles_in_russ AS air, "
                                    . "results.articles_in_foreign AS aif, "
                                    . "results.monograph AS mono, "
                                    . "results.reports_at_conf AS conf, "
                                    . "results.lecture_course AS course, "
                                    . "results.patents AS patents, "
                                    . "results.leadership AS leader, "
                                    . "results.other AS other, "
                                    . "unit_dep.title, unit_dep.uid AS uid_dep, "
                                    . "user_sub.sub "
                                      . "FROM unit "
                                      . "LEFT JOIN unit AS un_sec ON un_sec.u4 = unit.uid "
                                      . "LEFT JOIN users ON users.id_post = unit.id_post "
                                      . "LEFT JOIN results ON results.uid = users.uid "
                                      . "LEFT JOIN user_sub ON user_sub.uid = users.uid "
                                      . "LEFT JOIN unit AS unit_dep ON unit_dep.uid = users.lid "
                                      . "WHERE unit.id_post = ? "
                                      . "ORDER BY unit_dep.uid, users.surname ");
                $sql->bindParam(1, $id_post, PDO::PARAM_INT);
        
        $sql->execute();
         $row = $sql->fetchAll(PDO::FETCH_ASSOC);
       
         return $row;
        
        
    }
    
}
