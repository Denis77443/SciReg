<?php
ob_clean();

class ReportRezSaveAjaxController {
    
    public function ReportRezSaveAjaxAction1(){
        //var_dump($_POST);
        if(null !== filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT)){
            //echo 'Можно заносить данные!<br />';
            
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            
            //Отчет
            $text_report = filter_input(INPUT_POST, 'text_report', FILTER_SANITIZE_STRING);
            
            //Результаты научной деятельности
            $art_in_russ = filter_input(INPUT_POST, 'articles_in_russ', FILTER_SANITIZE_MAGIC_QUOTES);
            $art_in_for  = filter_input(INPUT_POST, 'articles_in_foreign', FILTER_SANITIZE_MAGIC_QUOTES);
            $monograph   = filter_input(INPUT_POST, 'monograph', FILTER_SANITIZE_MAGIC_QUOTES);
            $rep_at_conf = filter_input(INPUT_POST, 'reports_at_conf', FILTER_SANITIZE_MAGIC_QUOTES);
            $lecture     = filter_input(INPUT_POST, 'lecture_course', FILTER_SANITIZE_MAGIC_QUOTES);
            $patents     = filter_input(INPUT_POST, 'patents', FILTER_SANITIZE_MAGIC_QUOTES);
            $leadership  = filter_input(INPUT_POST, 'leadership', FILTER_SANITIZE_MAGIC_QUOTES);
            $other       = filter_input(INPUT_POST, 'other', FILTER_SANITIZE_MAGIC_QUOTES);
            
            if((userpage::IssetUserInReport($user_id)) !== FALSE){
               // echo 'Обновление данных';
                userpage::UpdateRepRez($user_id, $text_report, $art_in_russ, 
                                       $art_in_for, $monograph, $rep_at_conf, 
                                       $lecture, $patents, $leadership, $other);
            }else{
                // Новая запись в Reports
                userpage::InsertRepRez($user_id, $text_report, $art_in_russ, 
                                       $art_in_for, $monograph, $rep_at_conf, 
                                       $lecture, $patents, $leadership, $other);
            }
            
        }else{
            echo 'Обновлять ничего нельзя!';
        }
    }
    
    public function ReportRezSaveAjaxAction(){
      //  var_dump($_POST);
        if(null !== filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT)){
            
          $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
          $field_name = filter_input(INPUT_POST, 'field_name', FILTER_SANITIZE_STRING);
        //  $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_MAGIC_QUOTES);
          $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
          
           if((userpage::IssetUserInReport($user_id)) !== FALSE){
               // 'Обновление данных';
               userpage::UpdateRepField($user_id, $field_name, $value);
           }else{
               // Вставка новой записи в БД
               userpage::InsertRepField($user_id, $field_name, $value);
           }
            
        }else{
            echo 'Обновлять ничего нельзя!';
        }
    }
}

$var = new ReportRezSaveAjaxController();
$var->ReportRezSaveAjaxAction();