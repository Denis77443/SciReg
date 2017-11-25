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
        if(filter_input(INPUT_SERVER, 'PHP_SELF') == '/2016/index.php'){
            return $php_self = '';
        }else{
            return $php_self = '../';
        }
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
     * Action для пользователя 
     * с наследуемыми свойствами и методами
     */
    public function UserAction(){
      
       include_once ROOT_MENUU.'/views/MenuView.php';
       return true;
       
    }
}


class leader extends MenuController{
    
    //Сервис
    private   $podrazd = 'Подразделение';
    protected $search  = 'АБВГД';
    protected $status  = 'Статус';

    /*
     * Метод возвращает из Модели пункты меню ПОДРАЗДЕЛЕНИЕ
     */
    protected function array_menu(){
        return menu::getDepartment();
    }
    
    /*
     * Инициализация подпунктов меню пункта ПОДРАЗДЕЛЕНИЕ
     */
    protected function array_menu_items(){
        $this->depar = array_column(menu::getDepartment(), 'title', 'uid');
        $this->depar_hid = array_column(menu::getDepartment(), 'uid', 'hid');
        $this->office = array_column(menu::getDepartment(), 'u4_o', 'title_o');
        $this->id_otdel = array_column(menu::getDepartment(), 'uid_o','title_o');
        $this->otdel_hid = array_column(menu::getDepartment(), 'uid_o', 'hid_o');
        $this->isset_lab = array_column(menu::getDepartment(), 'u3_l', 'title_o');
        $this->laborat = array_column(menu::getDepartment(), 'u3_l', 'title_l');
       // $this->laborat_hid = array_column(menu::getDepartment(), 'uid_l', 'hid_l');
        $this->id_laborat = array_column(menu::getDepartment(), 'uid_l', 'title_l');
        $this->sector = array_column(menu::getDepartment(), 'u2_s', 'title_s');
        $this->id_sector = array_column(menu::getDepartment(), 'uid_s', 'title_s');
       // return $this->depar;
    }

    public function LableaderAction(){
        
        echo 'зав. лаб';
        $this->array_menu_items();
       
        
        if(isset($this->otdel_hid[$_SESSION['user_id']])){
           // echo 'есть такое значение '.$otdel_hid[$_SESSION['user_id']];
            if(in_array($this->otdel_hid[$_SESSION['user_id']],$this->id_otdel)){ 
                    $name_ot = array_search($this->otdel_hid[$_SESSION['user_id']],$this->id_otdel);
                 // echo $name_ot;
                  if(isset($this->office[$name_ot])){
                      $office_tmp[$name_ot] = $this->office[$name_ot];
                      $this->office = $office_tmp;
                      $dep_tmp[$this->office[$name_ot]] = $this->depar[$this->office[$name_ot]];
                      $this->depar = $dep_tmp;
                  }
            }
          
        }
        
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
    }
    
    /*
     * Данные для формирования меню у 
     * зав. отделов
     */
        public function OtdelleaderAction(){
        echo 'зав. отдел';
        
      //  $array_query = menu::getDepartment();
        
        foreach ($this->array_menu() as $key_array){
            if(in_array($_SESSION['user_id'], $key_array)){
           // 
             $this->depar     = array($key_array['uid']     => $key_array['title']);
             $this->office    = array($key_array['title_o'] => $key_array['uid']);
             $this->isset_lab = array($key_array['title_o'] => $key_array['u3_l']);
             $this->id_otdel  = array($key_array['title_o'] => $key_array['uid_o']);
             
             $this->laborat[$key_array['title_l']]    = $key_array['u3_l'];
             $this->id_laborat[$key_array['title_l']] = $key_array['uid_l'];
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
        //$array_query = menu::getDepartment();
        echo 'зав. сектор';
        foreach ($this->array_menu() as $key_array){
            if(in_array($_SESSION['user_id'], $key_array)){
              $this->depar     = array($key_array['uid']     => $key_array['title']);
              $this->office    = array($key_array['title_o'] => $key_array['uid']);
              $this->isset_lab = array($key_array['title_o'] => $key_array['u3_l']);
              $this->id_otdel  = array($key_array['title_o'] => $key_array['uid_o']);
              $this->sector    = array($key_array['title_s'] => $key_array['u2_s']);
              $this->id_sector = array($key_array['title_s'] => $key_array['uid_s']);
            }
        }
        
   
     
      
        //echo '<h1>Controller --- Leader SectorleaderAction</h1>';
        
        include_once ROOT_MENUU.'/views/MenuView.php';
        
        return true;
    }
    
    
    /*
     * Данные для формирования меню 
     * руководителей самого высокого статуса 
     */
    public function HighleaderAction(){
        
        $this->array_menu_items();
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
              
        $this->array_menu_items();
        
        foreach ($this->depar_hid as $key=>$value){
            if($key == $_SESSION['user_id']){
                $rr[$value] = $this->depar[$value];
            }
        }
        
        $this->depar = $rr;
      
      
        //$this->deleteSubj = 'Удаление всего';
        
        echo '<h1>class - HeadController action DepheadAction</h1>';
        include_once ROOT_MENUU.'/views/MenuView.php';
        return true;
    }
    
    /*
     * Данные для формирования меню у 
     * директора и зав. отделения одновременно!!!
     */
    public function HighheadAction(){
        $this->podrazd = 'Подразделение';
        
        
        var_dump(menu::WhoseSession($this->user_id()));
       
        $sub_w_lead = menu::SubjectWithoutLeader($this->user_id());
        
        if(!empty($sub_w_lead)){
           $this->leadship = 'Темы (руководство)';
           $this->user_name = menu::UserName($this->user_id());
        }
        
        $this->array_menu_items();
        
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