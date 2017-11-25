<?php
ob_clean();
//header('Content-Type: application/json; charset=utf8', true);
//require (ROOT_MENUU.'/components/DB.php');
//if(!defined('ROOT_MENUU')) die('access denied');

class SubjectListajaxController{
    
    public function SubjectListajaxAction($subjectPath){
       
       
     
        $json = json_decode(file_get_contents('php://input'));
        
       
        $flag = get_object_vars($json)['id'];
      
      //  $flag = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        //var_dump($flag);
      //  $flag = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
       // var_dump($flag);
        
        $value['id'] = $subjectPath[$flag];
        $value['title'] = '';
        $i = ' ';
        $k = ' ';
        $name_header = '';
        
        $subjectPath = include(ROOT_MENUU.'/config/JSON_progs.php');
       // include(ROOT_MENUU.'/components/DB.php');
        include(ROOT_MENUU.'/models/subject.php');
        $tt = subject::GetAllSubjectsByCat($flag);
        
        //var_dump($tt);
        foreach($tt as $key){
            if($key['flag'] == null){
                
                if((!empty($i))&&($i != $key['title'])){
                    $i = $key['title'];
                    $value['title'] .= "<a style='font-weight:bold;' >".$key['title']."</a><br>"; //Title 
                }
                
                if($k != $key['title01']){
                    
                    $k = $key['title01'];
            
                    //Исключение для темы Программ ФЦП развития УНУ
                    if($key['flagg'] > '2'){
                        if(($key['hid_01'] == NULL)||($key['hid_01'] == '0')){
                            $value['title'] .= "<p><a style= 'margin-left:20px;color:red;'>".$key['title01']."</a>";
                        }else{
                            $value['title'] .= "<p><a style= 'margin-left:20px;color:black;'>".$key['title01']."</a>";
                            $name_header .= ' Рук. - '.$key['surname'].$key['name'].$key['mname'];
                            "<p><a style= 'margin-left:20px;'>".$key['title01'].
                            $value['title'] .= "<span style='font:bold italic 100% serif;white-space: nowrap;'>".$name_header."</span></a><br>";
                        } 
                 
                    }else{
                        $value['title'] .= "<p><a style= 'margin-left:20px;'>".$key['title01']."</a><br>"; 
                    }
                    
                    $sub_subj = subject::GetSubSubject($key['id_01'], $flag);
                    
                    foreach($sub_subj as $key_sub){
                        $name_header = '';
                        $href = '';
                        $style = '';
                        
                        if($key_sub['surname'] != NULL){
                    $name_header .= 'Рук. - '.$key_sub['surname'].$key_sub['name'].$key_sub['mname'];
                  //  $href = "href='".$php_self."Views/user_page.php?zav_dep=1&uid=".$row_02['hid']."'";
                    $style = "style='margin-left:40px;'";
                }else{
                    $name_header .= '';
                    $style = "style='margin-left:40px;color:red;'";
                }
                
                $value['title'] .= "<p><a ".$style.">".$key_sub['title'].
                        " <span style='font:bold italic 100% serif;white-space: nowrap;'>"
                        .$name_header."</span></a><br></p>";
                        
                    }
                    
                }
                
            }
        
        }
        
        exit(json_encode($value));
    }
} 
//echo ROOT_MENUU;
$subjectPath = include(ROOT_MENUU.'/config/JSON_progs.php');
//var_dump($subjectPath);
$var = new SubjectListajaxController();
$var->SubjectListajaxAction($subjectPath);