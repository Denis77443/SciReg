<?php
ob_clean();

class ProfileGetDataAjaxController {
    //put your code here
    public function ProfileGetDataAjaxAction(){
        $data = get_object_vars(json_decode(file_get_contents('php://input')));
        
        var_dump($data);
       // var_dump(get_object_vars($data));
        $user_id = $data['user_id'];
       // $result = [];
        
      //  var_dump($user_id);
        //var_dump($ggg);
        
        foreach($data as $field_name => $value){
            
            if($field_name !== 'user_id'){
          //  var_dump($key);
           // echo "<h2>".$field_name." ".$value."</h2>";
           // userpage::UpdateRepField($user_id, $field_name, $value);
           userpage::UpdateAnyRecord($user_id, 'users', $field_name, $value); 
           //$result += [$field_name];
            }
        }
        
       // var_dump($result);
        /*
        $results ='';
        
        if(null !== (filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING))){
            $user_id   = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $field_name = filter_input(INPUT_POST, 'field_name', FILTER_SANITIZE_STRING);
            $results = userpage::SelectAnyRecord($user_id,  $field_name, 'users');
          
            
        }
        echo $results[0][$field_name];*/
      // echo $data['field_name'];
       // echo json_encode($data);
    }
}

$var = new ProfileGetDataAjaxController();
$var->ProfileGetDataAjaxAction();