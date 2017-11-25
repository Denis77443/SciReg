<?php
ob_clean();
//include ROOT_MENUU.'/models/userpage.php';

class PlanSaveAjaxController {
    
    public function PlanSaveAjaxAction(){
        echo "PlanSaveAjaxAction";
       // print_r($_POST);
        
        if(null !== (filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING))){
           // echo 'Добавление будет!';
            $user_id   = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $field_name = filter_input(INPUT_POST, 'field_name', FILTER_SANITIZE_STRING);
            $field_value = filter_input(INPUT_POST, 'field_value', FILTER_SANITIZE_STRING);
            
            if(userpage::UpdateAnyRecord($user_id, 'results',$field_name, $field_value) !== FALSE){
                echo 'ALLGOOD!!!!';
            }else{
                echo 'NEED ADD USER!!!!';
                userpage::InsertRecord($user_id, 'results', $field_name, $field_value) or die('Ошибка записи');
            }
            
        }else{
            echo 'Ничего не будет!';
        }
        
    }
}

$var = new PlanSaveAjaxController();
$var->PlanSaveAjaxAction();