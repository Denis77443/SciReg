<?php
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
       
       MenuLoginController::WriteToLog('User logged out', $this->UserLogin());
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
            
       // if((isset($_GET['param']))&&($_GET['param'] == 'save_plan')){
            // include_once ROOT_MENUU.'/controllers/ReportRezSaveAjaxController.php';
           // echo '<h1>FILES</h1>';
           // ob_clean();
            
       /*     
            $uploaddir = './files/';
                if( ! is_dir( $uploaddir ) ){ 
                    echo 'нет папки!!!'; }else{
                        echo 'есть папка FILES!!!';
                        
                    var_dump(ROOT_MENUU.'/files/'.$this->user_id());
                    
                    $usrDir = ROOT_MENUU.'/files/'.$this->user_id();
                    
                    if( ! is_dir( $usrDir ) ){
                        echo 'папки пользователя нет!!! создавать?';
                        mkdir( $usrDir, 0777 );
                    }else{
                        //echo 'папка пользоватеоля ЕСТЬ!!!';
                    //   var_dump($_POST); 
                      //  var_dump($_FILES);
                        
                     /*   foreach( $_FILES as $file ){
                            if( move_uploaded_file( $file['tmp_name'], $usrDir.'/'.basename($file['name']) ) ){
                                $files[] = realpath( $usrDir.'/'.$file['name'] );
                            }
                            else{
                                $error = true;
                            }
                            
                        }*/
                        
                        // var_dump($_POST);
                     /*  $fp = fopen($usrDir.'/'.$filename, "w");
                        fwrite($fp, file_get_contents('php://input'));
                        fclose($fp);*/
                        //$req = ob_get_contents();
                        //var_dump($req);
                      //  var_dump(file_put_contents($filename, (file_get_contents('php://input'))));
                   // }
                    
                  //  }
           // file_put_contents($filename, (file_get_contents('php://input')));
            //var_dump($_POST);
           // file_put_contents($filename, (file_get_contents('php://input')));
           // $rrr = file_get_contents('php://input');
           // var_dump($rrr);
            
           // getallheaders();
         //   var_dump($_SERVER['HTTP_X_FILE_NAME']);
            //var_dump($_FILES);
            
           // ob_end_flush();
            $ajaxAction = 'file';
           // $ajaxAction = filter_input(INPUT_FILE, 'file', FILTER_SANITIZE_STRING);
            $this->CheckAction($ajaxAction);
            //echo $ajaxAction;
        }
        
     }
    
     private function CheckAction($action){
         ob_clean();
       //  var_dump($_FILES);
         //Запись файла к ОТЧЕТУ
         if ($action === 'file') { 
 
             $usrDir = ROOT_MENUU.'/files/'.$this->user_id();
             
             if (! is_dir($usrDir) ) {
                 mkdir( $usrDir, 0777 );
             } 
             
             foreach ( $_FILES as $file ) {
               if( move_uploaded_file( $file['tmp_name'], $usrDir.'/'.$file['name'] ) ){
                   $files[] = realpath( $usrDir.'/'.$file['name'] );
               } else {
                 $error = true;
               }
             }
                 
         } 
         
         ob_end_flush();
     }
     
     
     /*
      * Вывод списка дополнительных файлов к ОТЧЕТУ
      */
     public function ShowFiles(){
         $usrDir = ROOT_MENUU.'/files/'.$this->user_id();
         //var_dump($usrDir);
         if(! is_dir($usrDir)){
           //echo 'NOthing else';  
         }else{
            
             $filesShow = array_diff(scandir($usrDir,1),array('..', '.'));
            // echo ROOT_MENUU;
             
             foreach($filesShow as $key){
                 echo "<div name='fl' style='text-decoration: underline; cursor:pointer; clear:both; float:left'>".$key."</div>";
             }
             //var_dump($files1);
         }
     }
     
     public function OpenFileAction(){
     //    var_dump($_REQUEST);
         
         $name = $_REQUEST['name'];
         $type = mime_content_type(ROOT_MENUU.'/files/'.$this->user_id().'/' . $name);
         
     //    echo $this->user_id();
         
          header('Content-type: '.$type);
                header('Content-disposition: inline; filename = ' . $name .'');
             // echo $type;
               readfile(ROOT_MENUU.'/files/'.$this->user_id().'/' . $name);
         
         
         
         //header('Content-type: application/pdf');
         //header('Content-disposition: inline; filename = ' . $uname . '.pdf');
         
      //   echo 'OpenFileAction';
         
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
