<?php
//header('Content-Type: application/json; charset=utf8', true);

class one{
    
    public function AjaxAction($post){
        //echo 'Заход в метод';
        $value['id'] = $post;
       // echo $value['id'];
        //var_dump($_REQUEST);
        return exit(json_encode($value));
    }
    
}
$var = new one();
$var->AjaxAction($_POST['id']);

