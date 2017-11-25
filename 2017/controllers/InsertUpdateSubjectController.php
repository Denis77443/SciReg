<?php
ob_clean();
include ROOT_MENUU.'/models/subject.php';

class InsertUpdateSubjectController {
    private $action;
    
    public function __construct(){
        $this->action = filter_input(INPUT_POST, 'action');
    }
    
    //put your code here
    public function InsertUpdateSubjectAction(){
        
        //
        //echo '<h1>'.$this->action.'</h1>';
        
        if($this->action == 'ShowSubject'){
            
            $options=array('options'=>array('min_range'=>1, 'max_range'=>3)); 
            $flag_subj = filter_input(INPUT_POST, 'flag_subj', FILTER_VALIDATE_INT, $options);
            $slave_id  = filter_input(INPUT_POST, 'slave_id');
            $sub_array = subject::GetSubjectByFlag($flag_subj);
            
            $sub_array = $this->doNotShowSameSubj($slave_id, $sub_array);
            include ROOT_MENUU.'/views/SelectParentSubjectView.php';
        }
        
        if($this->action == 'SelectSubject'){
           
            //echo '<h2>SelectSubject</h2>';
            $id_parent  = filter_input(INPUT_POST, 'id_subject'); // ID Parent темы
            $flag    = filter_input(INPUT_POST, 'flag'); // FLAG категории тем
            $user_id = filter_input(INPUT_POST, 'slave_id'); // ID пользователя
            // echo 'SELECT SUBJECT!!!!'.$id_parent;
            //var_dump(subject::GetSubjectByParent($flag, $id_parent));
            $sub_array = subject::GetSubjectByParent($flag, $id_parent);
          
            /*
             * Получаем список уже заданных у пользователя ТЕМ, чтобы их не 
             * показывать
             *  
             */
           $sub_array = $this->doNotShowSameSubj($user_id, $sub_array);
 
            include ROOT_MENUU.'/views/SelectSubjectView.php';

        }
        
        if($this->action == 'DeleteSubject'){
            $id_sub = filter_input(INPUT_POST, 'id_sub'); // ID темы для удаления
            $user_id = filter_input(INPUT_POST, 'user_id'); // ID пользователя
           // echo $id_sub.' AND  '.$user_id;
            var_dump(subject::GetFieldSubAndHid($user_id, $id_sub));
            $sub_array = explode(',',subject::GetFieldSubAndHid($user_id, $id_sub)[0]['sub']);
            
            //Поиск в массиве ТЕМ необходимой ТЕМы и удаление её
            foreach ($sub_array as $value=>$key){
                if($key == $id_sub){
                    unset($sub_array[''.$value.'']);
                    break;
                }
            }
            
            //Отделение тем через запятую
            $sub_array = implode(',', $sub_array);
            
            if(subject::UpdateSubject($user_id, $sub_array) == TRUE){
                echo 'Тема удалена!!!';
            }else{
                die('Невозможно удалить тему!!! Ошибка!!!');
            }
            
            //Удаляем Руководителя ТЕМЫ
            if(subject::GetFieldSubAndHid($user_id, $id_sub)[0]['hid'] !== NULL){
                if(subject::UpdateHidInSubp($id_sub, 'NULL') == TRUE){
                    echo 'Начальника удалили из SUBP';
                }else{
                    die('Начальник не удалился из SUBP!!! Что-то произошло!');
                }
            }else{
                echo '<br>Некого удалять, начальник другой!!!<br>';
            }
            
           
            
        }
        
    }
    
    /*
     * Получаем список уже заданных у пользователя ТЕМ, чтобы их не 
     * показывать
     *  
     */
   private function doNotShowSameSubj($user_id, $sub_array){
        if( (null !==(subject::GetFieldSub($user_id)))&&
                (!empty(subject::GetFieldSub($user_id))) ) {
                
                 $oldsubj = explode(',',subject::GetFieldSub($user_id)[0]['sub']);
                 
                 foreach($oldsubj as $key){
                    foreach($sub_array as $key_sub => $value){
                        if ($key === $value['id']) {
                          unset($sub_array[$key_sub]);
                        } 
                    } 
                 }
            }
           
       return $sub_array;
    }
    
}

$var = new InsertUpdateSubjectController();
$var->InsertUpdateSubjectAction();
