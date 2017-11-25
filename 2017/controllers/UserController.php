<?php
$start = microtime(true);
//header("Content-Type: application/json", true);
//include_once ROOT_MENUU.'/models/menu.php';
//include_once ROOT_MENUU.'/models/userpage.php';
include_once ROOT_MENUU.'/controllers/ScinceController.php';
//include_once ROOT_MENUU.'/controllers/test.php';



class UserController extends ScinceController{
    
    protected $service = 'Сервис'; //Сервис
    protected $titleNIR = 'План НИР';

                           
    //All public users Menu items
  /* 
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
    
    protected    $disabled = ' readonly';  //Свойство readonly по умолчанию 
                                           //для полей <texarea> ПЛАН и 
                                           //ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ 





    protected    $subjectLeader; //Subject 
  
    */
    
    public function ___construct(){
       
        //echo 'Time of USER_CONTR class  '.(microtime(true) - $this->start2);
        
    }
    
    /*
     * Переопределение метода с учётом дополнений
     */
    public function AjaxAction(){
        //var_dump($_REQUEST);
        ScinceController::AjaxAction();
        /*
         * Подключение контроллера сохранения "Плана НИР" и  "Ожидаемые результаты"
         */
     //   if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'save_plan')){
       
       //      include_once ROOT_MENUU.'/controllers/PlanSaveAjaxController.php';
       // }
        
        
        
        /*
         * Подключение контроллера списка тем {Сервис->Темы->....}
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'subjectlist')){
           include_once ROOT_MENUU.'/controllers/SubjectListajaxController.php'; 
        }
        
        
        /*
         * Подключение контроллера СТАТУСА тем {Сервис->Статус(по. темам)}
         */
        if((null !== filter_input(INPUT_POST, 'action'))&&(filter_input(INPUT_POST, 'action') == 'showsubjstatus')){
          // include_once ROOT_MENUU.'/controllers/AjaxController.php'; 
            $ajaxAction = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); 
            $this->CheckAction($ajaxAction);
        }
        
        /*
         * Подключение контроллера записи файлов
         */
        if((null !== filter_input(INPUT_POST, 'action'))&&(filter_input(INPUT_POST, 'action') == 'file')){
           include_once ROOT_MENUU.'/controllers/AjaxController.php'; 
            $ajaxAction = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); 
            
            $this->CheckAction($ajaxAction);
            //echo '<h1>FILE</h1>';
        }
        
        
        /*
         * Подключение контроллера для сохранения ТЕМ в БД
         *
         */
       
        
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'select_subject')){
           include_once ROOT_MENUU.'/controllers/InsertUpdateSubjectController.php'; 
        }
        
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'delete_subject')){
           include_once ROOT_MENUU.'/controllers/InsertUpdateSubjectController.php'; 
        }
        
       
        
    }
    
     

 /*
     * Метод распределения Ajax запросов
     */
    protected function CheckAction($ajaxAction){
        /*
         * Action - показ СТАТУСА ТЕМ
         */
        if($ajaxAction == 'showsubjstatus'){
            $subject_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $this->ShowSubjectStatus($subject_id, $title);
        }
        
        if($ajaxAction == 'file'){
           
            ob_clean();
             var_dump($_FILES);
            echo 'FILE RECORD';
            
            ob_end_flush();
        }

    }    
 
    
      /*
     * Метод - показ СТАТУСА ТЕМ
     */
    public function ShowSubjectStatus($subject_id, $title){
       // echo 'ShowSubjectStatus($subject_id, $title)'.$subject_id.'  '.$title;
        $users_id = status::ShowSubjectUsers($subject_id);
        include (ROOT_MENUU.'/views/SubjectStatusView.php');
    }
  
    /*
     * Показывать Ф.И.О. пользователей в блоке СТАТУС
     */
    public function ShowNameAndStat($user_id){
        return status::ShowNameUsers($user_id);
    }
    
    
    /*
     * Показывать графическое выполнение работы пользователей в блоке СТАТУС
     */
    
    public static function ShowStatus1($user_id){
        $res = [];
        
        $sub = status::ShowStatUsers($user_id)['sub'];
        $plan = status::ShowStatUsers($user_id)['plan'];
        $exp = status::ShowStatUsers($user_id)['expected_result'];
        $report = status::ShowStatUsers($user_id)['report'];
        
        
        $res['Т'] = ( ($sub != 0)||($sub != false) ) ? 'subject_stat_gr':'subject_stat_rd';
        $res['П'] = ( ($plan != NULL)||(strlen($plan) > 40) ) ? 'subject_stat_gr':'subject_stat_rd';
        $res['Р'] = ( ($exp != NULL)||(strlen($exp) > 40) ) ? 'subject_stat_gr':'subject_stat_rd';
        $res['О'] = ( ($report != NULL)||(strlen($report) > 80) ) ? 'subject_stat_gr':'subject_stat_rd';
       
        
        if(((trim(status::ShowStatUsers($user_id)['air']))     != null)||
          ((trim(status::ShowStatUsers($user_id)['aif']))     != null)||
          ((trim(status::ShowStatUsers($user_id)['mono']))    != null)||
          ((trim(status::ShowStatUsers($user_id)['conf']))    != null)||
          ((trim(status::ShowStatUsers($user_id)['course']))  != null)||
          ((trim(status::ShowStatUsers($user_id)['patents'])) != null)||
          ((trim(status::ShowStatUsers($user_id)['leader']))  != null)||
          ((trim(status::ShowStatUsers($user_id)['other']))   != null)){
            $res['С'] = 'subject_stat_gr';
          }else{
            $res['С'] = 'subject_stat_rd';
          }
        
        return $res;
    }
    
    
    
    
    
    
    
     
     /*
      * Показать тему, которой руководит пользователь 
      * в блоке Персональная информация
      */
     protected function ShowSubjectHead(){
         $subject = userpage::GetSubjectHead($this->user_id());
         if($subject != FALSE){
             return $subject;
         }else{
             return $subject = '';
         }  
     }

     /*
      * Показывать темы в блоке {ТЕМЫ:}
      */
     protected function ShowIssetSubject(){
                  
         if(userpage::IssetSubject($this->user_id()) == FALSE){
            return "<a style='color:red'>Тема отсутствует (должна быть задана руководителем "
                     . "отделения/секретарем секции)</a>";
         }else{
             $subj_output = '';
             include_once ROOT_MENUU.'/models/subject.php';
             $progs = include(ROOT_MENUU.'/config/JSON_progs.php');
             
             $subject = subject::GetUserSubjects($this->user_id());
             if($subject[0]['title'] != NULL){
                foreach ($subject as $sub_val){
                    $subj_output .= "<fieldset>";
                    $subj_output .= "<legend><a style='font-weight:bold;'>".$progs[$sub_val['flag']]."</a></legend>";
                    $subj_output .= "<div>".$sub_val['title_p']."</div>";
                    $subj_output .= "<div><a style='padding-left:40px;'>".$sub_val['title']."</a></div>";
                    if($sub_val['subj_boss'] != NULL){
                       $subj_output .= "<div><a style='font-weight: bold; font-size: 10pt;'>".$sub_val['subj_boss']."</a></div>"; 
                    }
                    $subj_output .= '</fieldset>';
                }
             }else{
                 $subj_output = '';
             }
             return $subj_output;
             //return var_dump($subject);
            
         }   
     }
     
     /*
      * Вывод тем пользователя, либо ...... сообщения об отсутствии тем
      */
      protected function IssetSubjectOrNot(){
          
           if(userpage::IssetSubject($this->user_id()) == FALSE){
               $this->message = 'Тема отсутствует (должна быть задана руководителем '
                              . 'отделения/секретарем секции)';
               return FALSE;
           }else{
               return TRUE;
           }
      }
     
     /*
      * Вывод темы + руководителя темы
      */
      protected function ShowSubjectAndHead(){
          include_once ROOT_MENUU.'/models/subject.php';
          $progs = include(ROOT_MENUU.'/config/JSON_progs.php');
          $subject = subject::GetUserSubjects($this->user_id());
        
          $i = '';
          
          if($subject[0]['title'] !== NULL){
              
          foreach($subject as $key_sbj){
              
              
              
              
              $var_temp ='';
              
              
              
              foreach($key_sbj as $key => $value){
                 
                  if($key == 'flag'){
  
                    $var_temp = 'subj_output'.$value;  
                      
                      if(($value !== $i)&&($i !== '')){
                         
                        $this->$var_temp .= '</fieldset>';
                        $this->$var_temp .= '<fieldset>';
                        $this->$var_temp .= "<legend><a style='font-weight:bold;'>".$progs[$value]."</a></legend>";
                        $this->$var_temp .= '<div>'.$key_sbj['title_p'].'</div>';
                        $this->$var_temp .= "<div style ='padding-left:40px;' >".$key_sbj['title']."</div>";
                        if($key_sbj['subj_boss'] != NULL){
                            $this->$var_temp .= "<div><a style='font-weight: bold; font-size: 10pt;'>".$key_sbj['subj_boss']."</a></div>"; 
                        }
                      
                          
                      }else{
                          
                          if($value !== $i){
                            $this->$var_temp .= '<fieldset>';
                            $this->$var_temp .= "<legend><a style='font-weight:bold;'>".$progs[$value]."</a></legend>";
                          }
                            $this->$var_temp .= '<div>'.$key_sbj['title_p'].'</div>';    
                           
                            $this->$var_temp .= "<div style ='padding-left:40px;' >".$key_sbj['title']."</div>";
                            if($key_sbj['subj_boss'] != NULL){
                                $this->$var_temp .= "<div><a style='font-weight: bold; font-size: 10pt;'>".$key_sbj['subj_boss']."</a></div>"; 
                            }
                       
                      }
                      
                      $i = $value;
                  }
                  
                
              }
              
              if($key_sbj == end($subject)){   //Конец массива 
                $this->$var_temp .= '</fieldset>'; 
              }
              }
          }
      }


     
     /*
      * Показывать ПЛАН и ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ в одноименных блоках 
      */
     protected function ShowExpResults(){
         if(userpage::IssetExpResults($this->user_id()) == FALSE){
             return FALSE;
         }else{
             $exp_r = userpage::IssetExpResults($this->user_id());
             return $exp_r[0]['expected_result'];
         }
         
     }
     
 
     
     
     /*
      * Показывать РЕЗУЛЬТАТЫ НАУЧНОЙ ДЕЯТЕЛЬНОСТИ в блоке РЕЗ
      */
     protected function ShowResultsOfSA(){
         if(userpage::IssetResultsOfSA($this->user_id()) == FALSE){
             return FALSE;
         }else{
             $results = userpage::IssetResultsOfSA($this->user_id());
             return array('air'    => $results[0]['articles_in_russ'], 
                          'aif'    => $results[0]['articles_in_foreign'],
                          'mon'    => $results[0]['monograph'], 
                          'rac'    => $results[0]['reports_at_conf'],
                          'lc'     => $results[0]['lecture_course'],
                          'patents'=> $results[0]['patents'],
                          'lead'   => $results[0]['leadership'],
                          'other'  => $results[0]['other'] );
         }
         
     }
     
     /*
      * Показывать категории тем
      */
     public function SubjectcatAction(){
         
         //include_once ROOT_MENUU.'/views/MenuView.php';
         include_once ROOT_MENUU.'/views/UserSubjectcatView.php';
         include_once $this->GetUrl4Static().'/Views/footer.php';
         return true;
     }

     /*
      * Action выводит список всех ТЕМ в зависимости 
      * от выбранных категорий:{Темы Программ ФНИ ГАН, 
      * Темы Президиума и ОФН РАН, Темы Программ ФЦП развития УНУ}
      */
     public function SubjectListajaxAction(){
        // var_dump($_POST['id']);
        header('Content-Type: application/json; charset=utf8', true);  
        $n = array("Min" => '1', "Sec" => '22'); 
        $t['min'] = $_POST['id'];
        exit(json_encode($n)); 
     }
     
     
     
     
     
     /*
      * Возвращаем значение переменной  $this->disabled_rep,
      * т.е. можно ли зашедшему на страницу к пользователю
      * добовлять и изменять ПЛАН и ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ
      * 
      */
     protected function SetPlanDisabledOrNot($userpage){
         
       //  echo "<a style='color:blue;'>DISABLED === ".$this->disabled."</a>";
       //  var_dump($this->isUserSubjectLeader());
        // var_dump($userpage);
         
         
         // Руководитель темы, который находится у себя на странице
         // может редактировать поля ПЛАН и ОЖМДАЕМЫЕ РЕЗУЛЬТАТЫ
         if(($this->isUserSubjectLeader() == TRUE)AND($userpage == TRUE)){
             echo "<a style='color:red;'>РУКОВОДИТЕЛЬ ТЕМЫ + У СЕБЯ НА СТРАНИЦЕ</a>";
             return $this->disabled = '';
         }
         
         
         return $this->disabled;
           //тутт должно быть значение $this->disabled
         
     }
     
     /*
      * Action (РУК. ТЕМЫ) руководитель зашёл на чужую старницу
      */
     public function UserpageAction(){
         
           // var_dump($this->AccessUserPage($this->user_id()));
            
         if($this->AccessUserPage($this->user_id()) == TRUE){
           
             
             //Если пользователь зашел к себе на страницу - возврат на index
            if($this->user_id() == $_SESSION['user_id']){
                $this->disabled_rep = '';
                header("Location: index.php");
            }else{
                echo "<div class= 'content_other_user'>";
                $this->disabled_rep = ' readonly';
            }
            
            $this->SetPlanDisabledOrNot(FALSE);
           // var_dump($this->SetPlanDisabledOrNot(FALSE));
             
             
             
             
             
             if(method_exists(get_called_class(), 'ScinceOrScince2')){
                // var_dump($this->ScinceOrScince2($this->user_id()));
                 if($this->ScinceOrScince2($this->user_id()) == TRUE){
                     echo 'Наука 2';
                     $this->titleNIR = 'Задание';
                     //ScinceController::UserAction();
                     echo "<div style='min-height:600px;'>";
                     include_once ROOT_MENUU.'/views/UserPersInfoView.php';
                     include_once ROOT_MENUU.'/views/UserPlanNIRView.php';
                     include_once ROOT_MENUU.'/views/UserReportView.php';
                     echo "</div>";
                 //    var_dump($this->titleNIR);
                 }else{
                     $this->titleNIR = 'План НИР';
                     echo 'Наука просто';
                     
                     include_once ROOT_MENUU.'/views/UserPersInfoView.php';
                     include_once ROOT_MENUU.'/views/UserSubjectView.php';
                     include_once ROOT_MENUU.'/views/UserPlanNIRView.php';
                     include_once ROOT_MENUU.'/views/UserExpResultView.php';
                     include_once ROOT_MENUU.'/views/UserReportView.php';
                     include_once ROOT_MENUU.'/views/UserResultsView.php';
                     
                 }
                 
             }
            
            
            
             
             
             
             
         }else{
             $this->error = 'Доступ к странице пользователя запрещен';
             include ROOT_MENUU.'/views/Error.html';
           // echo 'Доступ к данной странице пользователя запрещен'; 
         }
        // var_dump($this->disabled);
         include_once $this->GetUrl4Static().'/Views/footer.php';
         return true;
     }

//27.10.2017----------------------START------------------------------------------

         /*
      * Доступ на страницу пользователя {TRUE/FALSE}
      */
     
     public function AccessUserPage($user_id){
         //echo '<h2>ACCESSUSERPAGE FROM USERcontroller</h2>';
         
         $result = FALSE;
         
         if($this->IsUserInSameSubj($user_id) == TRUE){
             $result = TRUE;
         }
             return $result;
         
     }
     
     
     /*
      * Если пользователь РУКОВОДИТЕЛЬ ТЕМЫ, то он имеет право заходить 
      * и изменять ПЛАН и ОЖИД.РЕЗ только у пользователей входящих в данную тему
      * 
      */
     private function IsUserInSameSubj($user_id){
         
         if($this->isUserSubjectLeader() == FALSE){
             return false;
         }else{
            // echo 'ID свой - '.$_SESSION['user_id'].' ID - чужой - '.$user_id;
            // var_dump(menu::subjectLeader());
             $query = menu::subjectLeader();
             foreach($query as $key){
               // echo $key['uid'].'<br>'; 
                 if($key['uid'] == $user_id){
                     $this->disabled = '';
                     return true;
                 }
             }
             //return true;
         }
     }
     
     
         
    /*
     * Пользователь - руководитель темы
     */
    protected function isUserSubjectLeader(){
       //  echo "<h2><a style = 'color:brown;' >Руководитель темы".(microtime(true) - INDEX_START)."</a></h2>";
        if(!empty(menu::subjectLeader())){
           $this->subjectLeader = 1;
           $this->subject = 'Тема';
          // $this->deleteSubj = 'Темы (удаление)';
           
           //var_dump($this->user_id());
           
           
           $this->arraySubUser = menu::subjectLeader();
           //var_dump($this->arraySubUser);
           
           foreach ($this->arraySubUser as $key){
               if(in_array($_SESSION['user_id'], $key)){
                   $this->title_sub[$key['id']] = $key['title']; 
               }
               
               if(in_array($this->user_id(), $key)){
                   $this->deleteSubj = 'Темы (удаление)';
               }
               
           }
        /*   $this->disabled = '';  Руководитель темы может редактировать 
                                  * "План НИР" и "Ожидаемые результаты" 
                                  */ 
           
          // $this->autosave ='  onblur="Auto_Save_Plan(event, user_id = '.$this->user_id().')"';
           return true;
           
        }else{
            return false;
        }
        
    }

     
//27.10.2017----------------------END-----------------------------------------     
     
    /*
     * Переопределение метода ShowStatus() для работников Наука
     */
    public function ShowStatus($user_id){
       
        if(method_exists(get_called_class(), 'ScinceOrScince2') && $this->ScinceOrScince2($user_id) == TRUE){
            ScinceController::ShowStatus($user_id);
        }else{
        
         $results = [];
         
         if(status::GetResultsForStatus($user_id) !== FALSE) {
            $subject = status::GetResultsForStatus($user_id);
            $plan = status::GetResultsForStatus($user_id)['plan'];
            $exp = status::GetResultsForStatus($user_id)['expected_result'];
            $report = status::GetResultsForStatus($user_id)['report'];
        }else{
            $subject = NULL;
            $plan = NULL;
            $exp = NULL;
            $report = NULL;
        }
         
         if( ($subject != NULL)||($subject !== FALSE) ){
                $results['Т'] =  'subject_stat_gr';   
         }else{
            $results['Т'] =  'subject_stat_rd';
         }
        
        
        
            if( ($plan != NULL)&&(strlen($plan)>40) ){
            $results['П'] =  'subject_stat_gr';
           
        }else{
            $results['П'] =  'subject_stat_rd';
        }
        
        if( ($exp != NULL)&&(strlen($exp)>40) ){
            $results['Р'] =  'subject_stat_gr';
           
        }else{
            $results['Р'] =  'subject_stat_rd';
        }
        
        if( ($report != NULL)&&(strlen($report)>80) ){
            $results['О'] =  'subject_stat_gr';
        }else{
            $results['О'] =  'subject_stat_rd';
        }
        
        if( ((trim(status::GetResultsForStatus($user_id)['articles_in_russ']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['articles_in_foreign']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['monograph']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['reports_at_conf']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['lecture_course']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['patents']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['leadership']))!= null)||
          ((trim(status::GetResultsForStatus($user_id)['other']))!= null) ){
            $results['С'] =  'subject_stat_gr';
          }else{
             $results['С'] =  'subject_stat_rd';
          }
        
        
        
            
   //     }
        include_once ROOT_MENUU.'/views/UserStatusView.php';
        }
    }
    
    
     
     /*
     * Action для пользователя 
     * с наследуемыми свойствами и методами
     */
    public function UserAction(){
        
        //var_dump(get_class());
      // include_once ROOT_MENUU.'/views/MenuView.php';
        $this->SetPlanDisabledOrNot(TRUE);
        
       include_once ROOT_MENUU.'/views/UserPersInfoView.php';
       include_once ROOT_MENUU.'/views/UserSubjectView.php';
       include_once ROOT_MENUU.'/views/UserPlanNIRView.php';
       include_once ROOT_MENUU.'/views/UserExpResultView.php';
       include_once ROOT_MENUU.'/views/UserReportView.php';
       include_once ROOT_MENUU.'/views/UserResultsView.php';
       include_once $this->GetUrl4Static().'/Views/footer.php';
       //echo 'User Action';
       return true;
       
    }
    
    
    
    
    
}


