<?php
ob_clean();

class ProfileGetOldDataController {
    //put your code here
    public function ProfileGetOldDataAction(){
        $user_id   = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
       //var_dump(userpage::GetSNMUser($user_id));
       $array = userpage::GetSNMUser($user_id);
       echo json_encode($array);
       //var_dump($array);
      
    }
}

$var = new ProfileGetOldDataController();
$var->ProfileGetOldDataAction();