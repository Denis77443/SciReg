<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuController1
 *
 * @author denis
 */
include_once ROOT_MENUU.'/models/menu.php';
include_once ROOT_MENUU.'/models/userpage.php';



class MenuController {
    //All public users Menu items
    
    protected    $home         = 'Home';   //Home
    protected    $page_name;               //Users name 
    protected    $service      = 'Сервис'; //Сервис 
    protected    $profile      = 'Профиль';//Профиль
    protected    $subject;   //Тема (если пользователь рук. темы)
    
    //Сервис    
    protected    $subject_list = 'Темы (просмотр)';  //Сервис->Темы (просмотр)
    protected    $deleteSubj; //Сервис->Темы (удаление)
    
    
    protected    $title_sub; //Темы где пользователь руководитель 
    protected    $arraySubUser; //Сотрудники и темы относящиеся к руководителю тем 





    protected    $subjectLeader; //Subject 
    
    public function ___construct(){
        
    }
    
    /*
     * Href 
     */
    public static function get_url(){
        if(filter_input(INPUT_SERVER, 'PHP_SELF') == '/'.DB::currientYear().'/index.php'){
            return $php_self = '';
        }else{
            return $php_self = '../';
        }
    }
    
    /*
     * Href for static parts of page
     */
    public static function GetUrl4Static(){
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }




    /*
     *  Id пользователя, чья страница активна
     */
    protected function user_id (){
        
        if(filter_input(INPUT_GET, 'zav_dep')){
            $user_id = filter_input(INPUT_GET, 'uid');
        }else{
           $user_id = $_SESSION['user_id'];
       }
       return $user_id;
    }
    
    
    /*
     * Пользователь - руководитель темы
     */
    protected function isUserSubjectLeader(){
        if(!empty(menu::subjectLeader())){
           $this->subjectLeader = 1;
           $this->subject = 'Тема';
           $this->deleteSubj = 'Темы (удаление)';
           
           $this->arraySubUser = menu::subjectLeader();
           
           foreach ($this->arraySubUser as $key){
               if(in_array($_SESSION['user_id'], $key)){
                   $this->title_sub[$key['id']] = $key['title'];
               }
           }
           //var_dump($this->arraySubUser);
           return true;
        }else{
            return false;
        }
        
    }

    



    /*
     * Имя пользователя для пункта меню
     */
    protected function name_user(){
       return menu::getNameUser();
    }
    
    /*
     * Фамилия Имя Отчество пользователя для 
     * блока Персональная информация 
     */
     protected function ShowSNMUser(){
       $var_name = userpage::GetSNMUser($this->user_id());
       $var_name = $var_name[0]['surname']." "
                  .$var_name[0]['name']." "
                  .$var_name[0]['mname'];
       
       return $var_name;
     }   
    
    /*
     * Должность пользователя(самая большая) для 
     * блока Персональная информация 
     */
     protected function ShowMaxPost(){
         $var_post = userpage::GetPostUser($this->user_id());
         $var_post = $var_post[0]['post_title'];
         return $var_post;
     }
     
     /*
     * Подразделение в которое входит пользователь
     * блока Персональная информация 
     */
     protected function ShowDepartment(){
         $name_deprt = '';
         $var_dep = userpage::GetDeptmUser($this->user_id());
         $var_dep = $var_dep[0];
         foreach ($var_dep as $value){
             if($value != NULL){
                $name_deprt .= '<div>'.$value.'</div>';   
             }
         }
         return $name_deprt;
     }







     /*
     * Action для пользователя 
     * с наследуемыми свойствами и методами
     */
    public function UserAction(){
      
       include_once ROOT_MENUU.'/views/MenuView.php';
       
       var_dump($this->ShowDepartment());
       include_once ROOT_MENUU.'/views/UserPersInfoView.php';
       //echo 'User Action';
       return true;
       
    }
    
    /*
     * Action для Выхода
     * 
     */
    public function LogoutAction(){
       
       unset($_SESSION['user_id']);
       unset($_SESSION['lidlevel']);
       header('Location:index.php?url=login'); 
       return true;
       
    }
    
    
    
}


class leader extends MenuController{
    
    //Сервис
    private $podrazd = 'Подразделение';
    protected $search  = 'АБВГД';
    protected $status  = 'Статус';
    
    


    public function LableaderAction(){
        
        $array_query = menu::getDepartment();
        $depar = array_column($array_query, 'title', 'uid');
        $office = array_column($array_query, 'u4_o', 'title_o');
        $id_otdel = array_column($array_query, 'uid_o','title_o');
        $otdel_hid = array_column($array_query, 'uid_o', 'hid_o');
        $isset_lab = array_column($array_query, 'u3_l', 'title_o');
        $laborat = array_column($array_query, 'u3_l', 'title_l');
        $laborat_hid = array_column($array_query, 'uid_l', 'hid_l');
        $id_laborat = array_column($array_query, 'uid_l', 'title_l');
        $sector = array_column($array_query, 'u2_s', 'title_s');
        $id_sector = array_column($array_query, 'uid_s', 'title_s');
        
        if(isset($otdel_hid[$_SESSION['user_id']])){
           // echo 'есть такое значение '.$otdel_hid[$_SESSION['user_id']];
            if(in_array($otdel_hid[$_SESSION['user_id']],$id_otdel)){ 
                    $name_ot = array_search($otdel_hid[$_SESSION['user_id']],$id_otdel);
                 // echo $name_ot;
                  if(isset($office[$name_ot])){
                      $office_tmp[$name_ot] = $office[$name_ot];
                      $office = $office_tmp;
                      $dep_tmp[$office[$name_ot]] = $depar[$office[$name_ot]];
                      $depar = $dep_tmp;
                  }
            }
          
        }
        
        include_once ROOT_MENUU.'/views/MenuView.php';
        var_dump($this->ShowDepartment());
        include_once ROOT_MENUU.'/views/UserPersInfoView.php';
        return true;
    }
    
    /*
     * Данные для формирования меню у 
     * зав. отделов
     */
        public function OtdelleaderAction(){
        
        
        $array_query = menu::getDepartment();
        
        foreach ($array_query as $key_array){
            if(in_array($_SESSION['user_id'], $key_array)){
           // 
             $depar     = array($key_array['uid']     => $key_array['title']);
             $office    = array($key_array['title_o'] => $key_array['uid']);
             $isset_lab = array($key_array['title_o'] => $key_array['u3_l']);
             $id_otdel  = array($key_array['title_o'] => $key_array['uid_o']);
             
             $laborat[$key_array['title_l']]    = $key_array['u3_l'];
             $id_laborat[$key_array['title_l']] = $key_array['uid_l'];
            }
        }
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
        }
        
        /*
     * Action for Head of Sector
     * or user who is Head of division with LEVEL 1
     * 
     */
    public function SectorleaderAction(){
        
       
        /*
         * Get menu item - DEPARTMENT
         */
        $array_query = menu::getDepartment();
        
        foreach ($array_query as $key_array){
            if(in_array($_SESSION['user_id'], $key_array)){
              $depar     = array($key_array['uid']     => $key_array['title']);
              $office    = array($key_array['title_o'] => $key_array['uid']);
              $isset_lab = array($key_array['title_o'] => $key_array['u3_l']);
              $id_otdel  = array($key_array['title_o'] => $key_array['uid_o']);
              $sector    = array($key_array['title_s'] => $key_array['u2_s']);
              $id_sector = array($key_array['title_s'] => $key_array['uid_s']);
            }
        }
        
   
     
      
        echo '<h1>Controller --- Leader SectorleaderAction</h1>';
        
        include_once ROOT_MENUU.'/views/MenuView.php';
        
        return true;
    }
    
    
    /*
     * Данные для формирования меню 
     * руководителей самого высокого статуса 
     */
    public function HighleaderAction(){
        
        $array_query = menu::getDepartment();
        
        $depar = array_column($array_query, 'title', 'uid');
        $office = array_column($array_query, 'u4_o', 'title_o');
        $id_otdel = array_column($array_query, 'uid_o','title_o');
        $isset_lab = array_column($array_query, 'u3_l', 'title_o');
        $laborat = array_column($array_query, 'u3_l', 'title_l');
        $id_laborat = array_column($array_query, 'uid_l', 'title_l');
        $sector = array_column($array_query, 'u2_s', 'title_s');
        $id_sector = array_column($array_query, 'uid_s', 'title_s');
                  
        
       
        
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
    }
    
        
}

class head extends leader{
    private   $podrazd;
    private   $candelete = false;
    private   $caninsert = false;
    private   $insert_subj;
    private   $leadship;
    private   $user_name;
    
    /*
     * Метод уточняет принадлежность 
     * сотрудника руководителю подразделения,
     * если сотрудники из одного подразделения - 
     * можно добавлять тему
     */
    private function caninsert(){
        if(menu::WhoseSession($this->user_id()) == TRUE){
            $this->caninsert  = true;
            $this->insert_subj = 'Темы (добавление)';
        }
        return $this->caninsert;
    }


    /*
     *  Метод возвращает TRUE, 
     *  если у пользователя есть 
     *  назначенные темы, которые необходимо удалить
     */
    private function candelete(){
        if(menu::WhoseSession($this->user_id()) == TRUE){
            $del = menu::DeleteAllSubjects($this->user_id());
            if($del != FALSE){
                $this->candelete = true;
                $this->deleteSubj = 'Темы (удаление) ALL';
            }
        }
        return $this->candelete;
    }

    /*
     *  Метод возвращает список тем, для удаления
     */
    private function ListOfSubj(){
       $del = menu::DeleteAllSubjects($this->user_id());
       foreach ($del as $key){
          $array_list[$key['id']] = $key['title'];
       }
       return $array_list;
    }



    /*
     * Данные для формирования меню у 
     * зав. отделений
     */
    public function DepheadAction(){
        $this->podrazd = 'Подразделение';
        
        $sub_w_lead = menu::SubjectWithoutLeader($this->user_id());
        
        if(!empty($sub_w_lead)){
           $this->leadship = 'Темы (руководство)';
           $this->user_name = menu::UserName($this->user_id());
        }
      //  $this->candelete = true;
        
        // var_dump($this->deleteSubj);
        //var_dump(menu::subjectDelete($this->user_id()));
        var_dump(menu::DeleteAllSubjects($this->user_id()));
        var_dump($sub_w_lead);
        var_dump(menu::UserName($this->user_id()));
        //var_dump(menu::SubjectWithoutLeader($this->user_id()));
        //var_dump(menu::WhoseSession($this->user_id()));
        echo '_____________________________________________';
        
       // var_dump(parent::isUserSubjectLeader());
        //var_dump(menu::WhoCanDeleteSubj());
        //parent::isUserSubjectLeader() = 0;
        
         echo '_____________________________________________';
        //var_dump(menu::subjectDelete($this->user_id()));
        
        //$insertSubj = 'Темы (добавление)';
        
        $array_query = menu::getDepartment();
        
        $depar = array_column($array_query, 'title', 'uid');
        $depar_hid = array_column($array_query, 'uid', 'hid');
        $office = array_column($array_query, 'u4_o', 'title_o');
        $id_otdel = array_column($array_query, 'uid_o', 'title_o');
        $isset_lab = array_column($array_query, 'u3_l', 'title_o');
        $laborat = array_column($array_query, 'u3_l', 'title_l');
        $id_laborat = array_column($array_query, 'uid_l', 'title_l');
    
      foreach ($depar_hid as $key=>$value){
          if($key == $_SESSION['user_id']){
              $rr[$value] = $depar[$value];
          }
      }
      $depar = $rr;
      
      
        //$this->deleteSubj = 'Удаление всего';
        
        echo '<h1>class - HeadController action DepheadAction</h1>';
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
    }
    
    /*
     * Данные для формирования меню у 
     * директора и зав. отделения одновременно!!!
     */
    public function HighheadAction1(){
        $this->podrazd = 'Подразделение';
        
        //var_dump(menu::DeleteAllSubjects($this->user_id()));
        var_dump(menu::WhoseSession($this->user_id()));
       
        $sub_w_lead = menu::SubjectWithoutLeader($this->user_id());
        
        if(!empty($sub_w_lead)){
           $this->leadship = 'Темы (руководство)';
           $this->user_name = menu::UserName($this->user_id());
        }
        
        
        $array_query = menu::getDepartment();
        
        $depar = array_column($array_query, 'title', 'uid');
        $office = array_column($array_query, 'u4_o', 'title_o');
        $id_otdel = array_column($array_query, 'uid_o','title_o');
        $isset_lab = array_column($array_query, 'u3_l', 'title_o');
        $laborat = array_column($array_query, 'u3_l', 'title_l');
        $id_laborat = array_column($array_query, 'uid_l', 'title_l');
        $sector = array_column($array_query, 'u2_s', 'title_s');
        $id_sector = array_column($array_query, 'uid_s', 'title_s');
        
        
        echo '<h1>class - HeadController action HighheadAction</h1>';
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
    }
    
    
    
    /*
     * Данные для формирования меню у 
     * секретаря
     */
    public function UserheadAction(){
       // $this->candelete = true;
       // $this->podrazd = ''
        var_dump(menu::DeleteAllSubjects($this->user_id()));
        var_dump(menu::WhoseSession($this->user_id()));
        echo '<br />Run user_head_action';
        
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
    }
}