<?php
ob_clean();

class ProfileSaveAjaxController {
    
    public function ProfileSaveAjax(){
        
        $error = '';
        $error_value = '';
     //   var_dump($_POST);
       if(null !== (filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING))){
           // echo 'Добавление будет!';
            $user_id   = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
       
       
          //  echo 'ДОбавление БУДЕТ!!!<br>';
          //  echo $user_id;
          //  $data[] = $_POST['data'];
            $data[] = filter_input(INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
           // var_dump($data);
            
            foreach($data as $key){
                foreach($key as $field_name => $field_value){
                    //echo 'Поле - '.$field_name.' Значение - '.$field_value.'<br>';
                    
                    if(preg_match('/[A-z0-9\s]/u',$field_value)){
                    //if(preg_match('@[A-z]\s@u',$field_value)){
                        //echo 'Ошибка';
                        $error = 1;
                        $error_value = $field_value;
                    //    echo 'Ошибка! Поле - '.$error_value.' содержит латинские символы';
                        break;
                    }else{
                        $error ='';
                        userpage::UpdateAnyRecord($user_id, 'users',$field_name, $field_value);
                        echo 'Поле - '.$field_name.'  измененно<br>';
                    }
                  //  
                    
                }
                
                
            }
            
            if(!empty($error)){
             
                echo 'Ошибка! Поле - '.$error_value.' содержит недопустимые символы';
            }
          
        }
        
    }
}

$var = new ProfileSaveAjaxController();
$var->ProfileSaveAjax();
