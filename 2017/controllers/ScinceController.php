<?php
//phpinfo();

ob_start();

include_once ROOT_MENUU.'/controllers/test.php';
include_once ROOT_MENUU.'/models/menu.php';
include_once ROOT_MENUU.'/controllers/MenuLoginController.php';
include_once ROOT_MENUU.'/models/userpage.php';
include_once ROOT_MENUU.'/models/status.php';

class ScinceController extends test{
    protected $titleNIR = 'Задание'; //Задание(для Наука2) или План НИР(для )
    //put your code here
    /*
     * Href for static parts of page
     */
    public static function GetUrl4Static(){
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
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
     * Имя пользователя для пункта меню
     */
    protected function name_user(){
       return menu::getNameUser();
    }
    
    
    /*
     * Action для Выхода
     * 
     */
    public function LogoutAction(){
       
       MenuLoginController::WriteToLog('logged out', $this->UserLogin());
       unset($_SESSION['user_id']);
       unset($_SESSION['lidlevel']);
       //var_dump($_REQUEST);
       ob_clean();
       header('Location:index.php?url=login', true);
       ob_end_flush();
       exit();
       return true;
       
    }
   
    
    /*
     *  Id пользователя, чья страница активна
     */
    protected function user_id (){
        
      $user_id = (filter_input(INPUT_GET, 'id')) ? filter_input(INPUT_GET, 'id') : $_SESSION['user_id'];

      return $user_id;
    }
    
    
    /*
     * Login пользователя
     */
    
    protected function UserLogin(){
       return menu::UserLogin($this->user_id());
    }

    
    
    /*
     * Фамилия Имя Отчество пользователя для 
     * блока Персональная информация 
     */
     protected function ShowSNMUser(){
         
       $var_name = userpage::GetSNMUser($this->user_id());
       $var_name = $var_name['surname']." ".$var_name['name']." ".$var_name['mname'];
       
       return $var_name;
     }   
    
    /*
     * Должность пользователя(самая большая) для 
     * блока Персональная информация 
     */
     protected function ShowMaxPost(){
         
         $var_post = userpage::GetPostUser($this->user_id())['post_title'];
         
         return $var_post;
     }
     
         
    /*
     * Подразделение в которое входит пользователь
     * блока Персональная информация 
     */
     protected function ShowDepartment(){
         $name_deprt = '';
         $var_post = '';
         $var_dep = userpage::GetDeptmUser($this->user_id());
        // var_dump($var_dep);
         
         foreach ($var_dep[0] as $key => $value){
             if($value != NULL){
                 
                if($var_dep[1][$key] != NULL){ 
                    $post = "(".$var_dep[1][$key].")"; 
                }else{
                    $post = '';
                }
                
                $name_deprt .= '<div style="clear:both;"><p>'.$value.' '.$post.'</p></div>';   
             }
         }
         return $name_deprt;
     }
     
     
     


      /*
      * Показывать ПЛАН и ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ в одноименных блоках 
      */
     protected function ShowPlanNIR(){
         if(userpage::IssetPlanNIR($this->user_id()) == FALSE){
             return FALSE;
         }else{
             $plan = userpage::IssetPlanNIR($this->user_id());
             return $plan[0]['plan'];
         }
         
     }
     
     /*
      * Показывать ОТЧЕТ в блоке ОТЧЕТ
      */
     protected function ShowReport(){
         if(userpage::IssetReport($this->user_id()) == FALSE){
             return FALSE;
         }else{
             $report = userpage::IssetReport($this->user_id());
             return $report[0]['report'];
         }
         
     }
     
 
    
     /*
      * Редактирование профиля пользователя - самим пользователем
      */
     public function ProfileAction(){
        // echo 'тут редактирование профиля';
         include_once ROOT_MENUU.'/views/UserProfileView.php';
         include_once $this->GetUrl4Static().'/Views/footer.php';
         return true;
     }
     
     
          
     /*
     * Фамилия Имя Отчество пользователя для 
     * блока Профиль 
     */
     protected function ShowUserName($name){ 
        return   userpage::GetSNMUser($this->user_id())[$name];
     }
    
     
     public function AjaxAction(){
         /*
         * Подключение контроллера сохранения поля ПРОФИЛЯ 
         * при редактировании пользователем собственных данных
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'save_profile')){
           include_once ROOT_MENUU.'/controllers/ProfileSaveAjaxController.php'; 
        }
        
        /*
         * Подключение контроллера сохранения поля ПРОФИЛЯ 
         * при редактировании пользователем собственных данных
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'get_profile_param')){
           include_once ROOT_MENUU.'/controllers/ProfileGetDataAjaxController.php'; 
        }
        
        /*
         * Подключение контроллера для получения "старых" параметров ПРОФИЛЯ
         *
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'get_old_param')){
           include_once ROOT_MENUU.'/controllers/ProfileGetOldDataController.php'; 
        }
        
        /*
         * Подключение контроллера сохранения "Отчёт" и 
         * блока "Результаты научной деятельности"
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'save_report')){
       // if((isset($_GET['param']))&&($_GET['param'] == 'save_plan')){
             include_once ROOT_MENUU.'/controllers/ReportRezSaveAjaxController.php';
        }
        
        /*
         * Загрузка файлов к ОТЧЁТУ  
         * 
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'file')){
            $ajaxAction = 'file';
            $this->CheckAction($ajaxAction);
        }
        
        /*
         * Удаление файлов
         * 
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'deletefile')){
            $ajaxAction = filter_input(INPUT_POST, 'action');
            $this->CheckAction($ajaxAction);
        }
        
        
        
     }
    
     private function CheckAction($action){
         ob_clean();
         $usrDir = ROOT_MENUU.'/files/'.$this->user_id();
         
         //Запись файла к ОТЧЕТУ
         if ($action === 'file') { 
 
             
             
             if (! is_dir($usrDir) ) {
                 mkdir( $usrDir, 0777 );
             }
             
             foreach ( $_FILES as $file ) {
               if( move_uploaded_file( $file['tmp_name'], $usrDir.'/'.$file['name'] ) ){
                   $files[] = realpath( $usrDir.'/'.$file['name'] );
                   MenuLoginController::WriteToLog('add file', $this->UserLogin(), $file['name']);
               } else {
                 $error = true;
               }
             }
                 
         } 
         
          if ($action === 'delete') { 
              $file = filter_input(INPUT_POST, 'filename');
              unlink($usrDir.'/'.$file);
              MenuLoginController::WriteToLog('remove file', $this->UserLogin(), $file);
          }
         
         ob_end_flush();
     }
     
     
 
    
    /*
     * Узнаем значение upload_max_filesize и upload_max_filesize из php.ini
     * для предупреждения о лимите объема загружамого файла
     */
    protected function MaxFileSize(){
       $post_max_size =  ini_get('post_max_size');
       $upload_max_filesize = ini_get('upload_max_filesize');
       
       if ( $upload_max_filesize < $post_max_size ) {
           return substr($upload_max_filesize, 0, -1);
       } else {
           return substr($post_max_size, 0, -1);
       }
    }


    public function ShowButtonUploadFiles(){
        if ($this->user_id() === $_SESSION['user_id']) {
            include ROOT_MENUU.'/views/ButtonUploadFiles.php';
        }else{
            return FALSE;
        }
     }
     
     
     
  public function OpenFileAction(){
      $error = 0;
      
      $error = (isset($_GET['url']) && isset($_GET['name']) 
                && isset($_GET['user_id'])) ? 0:1;
      
      
      if ($error == 0){
         $name = $_REQUEST['name'];
         $user_id = (isset($_REQUEST['user_id']) && ($_REQUEST['user_id'] !== 'undefined')) ? $_REQUEST['user_id'] : $this->user_id();   
         
         if ( $_SESSION['user_id'] == $user_id ){ 
             $error = 0; 
         } else {
         if($this->AccessUserPage($user_id) == FALSE){ $error = 1; }
         }
      }
      
      
      if ($error == 0){
        $fl = ROOT_MENUU.'/files/'.$user_id.'/' . $name;
       
        if(!file_exists($fl)){$error = 1;}
      }
      
      if($error == 0){
      
 
         
          $type = mime_content_type(ROOT_MENUU.'/files/'.$user_id.'/' . $name); 
          header('Content-type: '.$type);
          header('Content-disposition: inline; filename = ' . $name .'');
             
          readfile(ROOT_MENUU.'/files/'.$user_id.'/' . $name);
         
      } else { 
          
         $this->error = 'Ошибка доступа!';
         include ROOT_MENUU . '/views/Error.html';
         return false;  
      }
     }

     /*
      * Показывать СТАТУС выполнения (степень запонения страницы)
      */
    public function ShowStatus($user_id){
        
   
        $results = [];
 
        if(status::GetResultsForStatus($user_id) !== FALSE) {
            $plan = status::GetResultsForStatus($user_id)['plan'];
            $report = status::GetResultsForStatus($user_id)['report'];
        }else{
            $plan = NULL;
            $report = NULL;
        }
            
            if( ($plan != NULL)&&(strlen($plan)>40) ){
            $results['П'] =  'subject_stat_gr';
           
        }else{
            $results['П'] =  'subject_stat_rd';
        }
        
        if( ($report != NULL)&&(strlen($report)>80) ){
            $results['О'] =  'subject_stat_gr';
        }else{
            $results['О'] =  'subject_stat_rd';
        }
        
        
        
            
   //     }
        include_once ROOT_MENUU.'/views/UserStatusView.php';
      
        
        
      //  echo "тут статус";
    }
     
    /*
     * Доступ ТОЛЬКО на "свою" страницу пользователей НАУКА 2
     */
    protected function AccessUserPage($user_id){
        return ($user_id !== $_SESSION['user_id']) ? FALSE : TRUE;
    }
    
    public function UserAction(){
        echo "<div class='content_main'>";
        include_once ROOT_MENUU.'/views/UserPersInfoView.php';
        include_once ROOT_MENUU.'/views/UserPlanNIRView.php';
        include_once ROOT_MENUU.'/views/UserReportView.php';
        echo "</div>";
        include_once $this->GetUrl4Static().'/Views/footer.php';
    }
    
    
}
