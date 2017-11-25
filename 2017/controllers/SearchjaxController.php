<?php
ob_clean();
include ROOT_MENUU.'/models/search.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchjaxController
 *
 * @author denis
 */
class SearchjaxController {
    //put your code here
    
    private function UserSearchList($key){
        if((isset($key['uid']))&&($key['uid'] !== NULL)){
             return "<a href='index.php?url=userpage&id=".$key['uid']."'>".$key['name']."</a><br>";
        }

        if((isset($key['uid2']))&&($key['uid2'] !== NULL)){
            return "<a href='index.php?url=userpage&id=".$key['uid2']."'>".$key['name2']."</a><br>";
        }
    }


    public function SearchjaxAction(){
        
        $letter = filter_input(INPUT_POST, 'id');
        //$letter = $_POST['id'];
        $lidlevel = $_SESSION['lidlevel'];
        //echo $lidlevel;
        $user_get = search::GetFoundUsers($letter, $lidlevel);
      // var_dump($user_get);
        $user_find = '';
        $user_Sc2 = '';
        foreach($user_get as $key){
            if ($key['sci'] == '1'){
                $user_find .= $this->UserSearchList($key);
            }else{
               if((isset($key['sci2']))&&($key['sci2'] == '1')){
                  $user_find .= $this->UserSearchList($key); 
               } 
            }
            
            if ($key['sci'] == '2'){   
                $user_Sc2 .= $this->UserSearchList($key);
            }else{
                if((isset($key['sci2']))&&($key['sci2'] == '2')){
                  $user_Sc2 .= $this->UserSearchList($key); 
               }
            }
        }
        
        echo json_encode(array( 'scince' => $user_find, 'scince2' => $user_Sc2  ));
       // echo json_encode($user_find);
      
    }
}

$var = new SearchjaxController();
$var->SearchjaxAction();