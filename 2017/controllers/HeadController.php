<?php
ob_start();
//echo 'headController';
include_once ROOT_MENUU.'/controllers/LeaderController.php';

class HeadController extends LeaderController {
   // public   $podrazd;
    protected   $candelete = false;
    private     $caninsert = false;
    private     $canheader = false;
  //  protected   $sub_w_lead;
    
    protected   $insert_subj = 'Темы (добавление)';
    protected   $leadship;
    private   $user_name;
    protected   $deleteSubj = 'Темы (удаление)';
    





    public function ___construct() {
    
    }
    
    //
    //Переопределение метода AccessUserPage с учётом ДОСТУПА СЕКРЕТАРЯ !!!
    public function AccessUserPage($user_id){
        $result = false;
       
        
        if(LeaderController::AccessUserPage($user_id) == TRUE){
            // echo '<a style=color:red>Руководитель темы из HeadController()</a></br>';
             $result = true;
         }else{
            // var_dump(access::GetIdSecretary($user_id));
             if( (access::GetIdSecretary($user_id)[0]['id_sec'] == $_SESSION['user_id'])||
                 ($this->AccessPageCEO($_SESSION['user_id'], $user_id) === true) ){
                 
                // var_dump($this->disabled);
                 //echo  '<h2>Secretary</h2>';
                 $result = true;
             }
         }
        return $result;
    }
    
    /*
     * Исключение для секретаря EVN - доступ на страницу Директора 
     */
    private function AccessPageCEO($id_sec, $user_id){
        if( (userpage::GetSNMUser($id_sec)['uname'] === 'evn')&&
            (userpage::GetSNMUser($user_id)['uname'] === 'kvd') ) {
            $this->disabled = '';
            return true;
            
        } else {
            return false;
        }
    }




    /*
     * Сохранение ТЕМы в БД 
     * Редирект/перегрузка страницы после сохранения тем пользователя
     * для актуализации информации пользователя
     */
    
    public function RedirectAction(){
       
       // echo test::currientYear();
        
        $userpage = '';
        $upd_field = []; // Уже имеющиеся темы у пользователя
        
        
       $requiest = filter_input(INPUT_POST, 'subj');
       $user_id = filter_input(INPUT_POST, 'user_id'); //ID пользователя 
                                                      //которому задаются ТЕМы 
      // echo 'ID пользователя который добовляет'.$this->user_id().'<br>';
       //echo 'ID пользователя которому добовляют'.$user_id.'<br>';
       
       if($this->user_id() !== $user_id){
           $userpage = 'index.php?url=userpage&id='.$user_id;
       }else{
           $userpage = 'index.php';
       }
       
     //  if(isset($requiest)){echo 'ISSET';}else{echo 'NOT ISSET';}
       
      // var_dump($requiest);
       
      if(!isset($requiest)){
         // if(isset(filter_input(INPUT_POST, 'parent'))){
              $requiest = filter_input(INPUT_POST, 'parent');
         // }
      }
       
      if(isset($requiest)){
          include_once ROOT_MENUU.'/models/subject.php';
          if(empty(subject::GetUserSubjects($user_id))){
              echo '<h1>INSERT NEW records</h1>';
              
              if(subject::InsertSubject($user_id, $requiest) == TRUE){
                  header('Location: http://'.filter_input(INPUT_SERVER, 'SERVER_NAME').'/'.test::currientYear().'/'.$userpage);
              }
                      
          }else{
              //var_dump(subject::GetUserSubjects($user_id));
              echo '<h1>Update records</h1>';
            //  var_dump(subject::GetFieldSub($user_id));
              $upd_field = subject::GetFieldSub($user_id)[0]['sub'];
             // var_dump($upd_field);
              if($upd_field == ''){
                  if(subject::UpdateSubject($user_id, $requiest) == TRUE){
                      header('Location: http://'.filter_input(INPUT_SERVER, 'SERVER_NAME').'/'.test::currientYear().'/'.$userpage);
                  }
              }else{
                  
                  $requiest = $upd_field.','.$requiest;
               //   var_dump($requiest);
                  subject::UpdateSubject($user_id, $requiest);
                  header('Location: http://'.filter_input(INPUT_SERVER, 'SERVER_NAME').'/'.test::currientYear().'/'.$userpage);
              }
              //header('Location: http://'.filter_input(INPUT_SERVER, 'SERVER_NAME').'/'.test::currientYear().'/'.$userpage);
          }
          
      }
    }
    
        
    
    /*
     * Показывать пункт меню { Сервис->Темы(добавление) } 
     * только на двух страницах, поскольку действия по добовлению
     * тем появляется только в блоке "Тема"
     */
    protected function ShowInsertItem(){
        
        $get = filter_input(INPUT_GET, 'url');
        
        if(!isset($get)){
            return TRUE;
        }else{
            if(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_SPECIAL_CHARS) == 'userpage'){
                include ROOT_MENUU.'/models/access.php';
                
                $hid_u4 = access::YourColleague($this->user_id());
                $hid_sec = access::GetIdSecretary($this->user_id());
                
                //Добовлять темы 
                //руководителю  или секретарю можно 
                //только пользователям из "своего" отделения
                if(($hid_u4[0]['hid_u4'] == $_SESSION['user_id'])||
                   ($hid_sec[0]['id_sec'] == $_SESSION['user_id'])){
                    return TRUE;
                } 
                
            }
        }
    }


    /* Переопределенный метод SetPlanDisabledOrNot() с учетом 
     * того что, в классе HeadController пользователи 
     * имеют право добовлять и изменять ПЛАН и ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ 
     * всем в своем подразделении!!! 
     */
    protected function SetPlanDisabledOrNot($userpage){
        
      //  include ROOT_MENUU.'/models/access.php';
        if($userpage == TRUE){
            include ROOT_MENUU.'/models/access.php';
        }
        
            $hid_u4 = access::YourColleague($this->user_id()); 
            $hid_sec = access::GetIdSecretary($this->user_id());
             //   var_dump($hid_u4);
            if(($hid_u4[0]['hid_u4'] == $_SESSION['user_id'])||
               ($hid_u4[0]['hid_lid'] == $_SESSION['user_id'])||
               ($hid_sec[0]['id_sec'] == $_SESSION['user_id'])){
                $this->disabled = '';
              //  $this->autosave ='  onblur="Auto_Save_Plan(event, user_id = '.$this->user_id().')"';
                
            }    
      
        //$this->disabled = '';
        
       UserController::SetPlanDisabledOrNot($userpage);
     } 
    
    
    
    
    
    
    
    /*  
     * Переопределение метода SubjectcatAction()
     * с целью вывода пункта меню ПОДРАЗДЕЛЕНИЕ 
     * Показывать категории тем
     */ 
     public function SubjectcatAction(){
         $this->podrazd = 'Подразделение_Head28';
         $this->ShowItemMenuPodrazd($_SESSION['lidlevel']);
        // include_once ROOT_MENUU.'/views/MenuView.php';
         include_once ROOT_MENUU.'/views/UserSubjectcatView.php';
         include_once $this->GetUrl4Static().'/Views/footer.php';
         return true;
     }
    
       
    
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
    protected function candelete(){
        if(menu::WhoseSession($this->user_id()) == TRUE){
            $del = menu::DeleteAllSubjects($this->user_id());
            if($del != FALSE){
                $this->candelete = true;
                $this->deleteSubj = 'Темы (удаление)';
            }
        }
        return $this->candelete;
    }

    /*
     *  Метод возвращает список тем, для удаления
     */
    protected function ListOfSubj(){
       $del = menu::DeleteAllSubjects($this->user_id());
       foreach ($del as $key){
          $array_list[$key['id']] = $key['title'];
       }
       return $array_list;
    }
    
   /*
    *  Показывать пункт меню Сервис->Темы (Руководство)
    */
    public function leadship(){
        if(menu::WhoseSession($this->user_id()) == TRUE){
           if(menu::SubjectWithoutLeader($this->user_id()) !== FALSE){ 
             $this->leadship = 'Темы (руководство)';
             $sub_w_lead = menu::SubjectWithoutLeader($this->user_id());
             return $sub_w_lead;
           }
        }
    }
    
    /*
     * Назначение руководителя ТЕМЫ
     */
    public function SetsubjAction(){
        include ROOT_MENUU.'/models/subject.php';
        ob_clean();
      
       // var_dump($_POST);
       // $id = explode('+', filter_input(INPUT_POST, 'id'));
        
       // var_dump($id);
        
      //  $id_sub = $id[0];
      //  $id_user = $id[1];
        $id_sub = filter_input(INPUT_POST,'idSubj', FILTER_VALIDATE_INT);
        $id_user = filter_input(INPUT_POST,'idUser', FILTER_VALIDATE_INT);
        
        if(subject::UpdateHidInSubp($id_sub, $id_user) == TRUE){
            echo 'Назначен руководитель темы';
        }
    }
    
}
