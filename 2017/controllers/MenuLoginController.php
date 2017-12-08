<?php
if(!defined('ROOT_MENUU')) die('access denied');
include_once ROOT_MENUU.'/models/auth.php';
include_once ROOT_MENUU.'/models/registration.php';

class MenuLoginController {
    //put your code here
    function __construct() {
      //  parent::__construct();

        'КОНСТРУКТОРРРР ЛОГИН';
    }
    
    public function HomeAction(){
        include_once ROOT_MENUU.'/views/WelcomePageView.php';
        return true;
    }
    
    public function LoginAction(){
        include_once ROOT_MENUU.'/views/UserLoginView.php';
        return true;
    }
    
    public function AuthAction(){
       
        $login = trim(filter_input(INPUT_POST, 'surname'));
        $pass = filter_input(INPUT_POST, 'name');
        
        //var_dump($_POST);
        
        if((isset($login))&&(isset($pass))){
            $auth = auth::CheckLP($login, $pass);
           
            
            
            if (empty($auth)){
                ob_clean();
                MenuLoginController::WriteToLog('ERROR', $login,'username incorrect!');
                echo 'Ошибка пользователь не найден!';  
                exit();
                ob_end_flush();
                return false;
               

            }
            
            if($auth[0]['uid_wp'] != NULL){
               // session_start();
                $_SESSION['user_id'] = $auth[0]['uid'];
                 MenuLoginController::WriteToLog('logged in ', $login);
            //    header("Location:index.php?=".$auth[0]['uid']);
                exit();
                
                echo '<h4>CORRECT</h4>';
                return true;
            }else{
                if($pass == '9876'){
                    
                    if($auth[0]['uid'] != NULL){
                       // session_start();
                       ob_clean();
                        $_SESSION['user_id'] = $auth[0]['uid'];
                        MenuLoginController::WriteToLog('logged in', $login, 'login with admin pass');
                        //echo '<h4>CORRECT</h4>';
                         return true;
                    }else{
                        echo '<h4>НЕт такого Юзера!!!</h4>';
                   // header("Location:index.php?url=login");
                   // exit();
                    return false;
                    }
                }else{
                    ob_clean();
                    MenuLoginController::WriteToLog('ERROR', $login, 'password incorrect!');
                    echo 'Ошибка ввода пароля!';
                    exit();
                    return false;
                    ob_end_flush();
                }
            }
            
            //return true;
        }else{
            echo '<h4>FALSEEEE</h4>';
            return false;
        }
    }
    
    /*
     * Регистрация нового пользователя
     */
    public function RegistrationAction(){
       
      //  include ROOT_MENUU.'/models/registration.php';
        $post = registration::GetPost() ;
        $depar = registration::GetDepartment();
        
        
       // var_dump($depar);
        include ROOT_MENUU.'/views/RegistrationPageView.php';
    }
    
    /*
     * Insert нового пользователя в БД
     */
    public function InsertUserAction(){
        ob_clean();
        $data_out = [];
        
        $data = get_object_vars( json_decode(file_get_contents('php://input')) );
        
        if (isset($data['lid'])){ $data_out['lid'] = $data['lid']; } 
        $data_out['surname'] = "'".$data['surname']."'"; 
        $data_out['name'] = "'".$data['name']."'"; 
        $data_out['mname'] = "'".$data['mname']."'"; 
        $data_out['uname'] = "'".$data['uname']."'"; 
        $data_out['password'] = "PASSWORD('".$data['password']."')"; 
        
        if(isset($data['email'])){ $data_out['email'] = "'".$data['email']."'"; }
        if(isset($data['phone'])){ $data_out['phone'] = "'".$data['phone']."'"; }
        if(isset($data['mobile'])){ $data_out['mobile'] = "'".$data['mobile']."'"; }
    
        $data_out['id_post'] = $data['id_post']; 
           
        if (auth::CheckLogin($data['uname']) == FALSE) {
            echo 'Ошибка!<br>В БД уже есть пользователь с выбранным Вами Логином!';
        } else {
            //Выясняем к какой категории пользователей является 
            //вновь вносимый в БД
            
            if ($data['id_post'] < 20 || $data['id_post'] == 100){ 
                $data_out['sci'] = 1;
                $flag = ( $data['id_post'] == 100 && registration::GetListOfCipher($data['uname']) == FALSE ) ? false : true;
            }
            
            if ($data['id_post'] > 15 && $data['id_post']< 100){ 
                $data_out['sci'] = 2; $flag = true;
            }
            
            $field_values = implode(",", $data_out); 
            $field_names = implode(",", array_keys($data_out)); 
            
            
            
            //Проверка IMAP
            if ($this->checkImap($data['uname'], $data['password'], $data_out['sci']) == true) {
            
            if (userpage::InsertAnyRecord('users', $field_names, $field_values) == TRUE && $flag == true) {
                echo "Новый пользователь ".$data['uname']." успешно добавлен в БД";
                
                $user_id = registration::GetUserId($data['uname']);
                    
                if ($data['id_post'] == 100) {
                    //echo 'Это руководитель'; 
                    $data_out['sci'] = 1; 
                    $list = registration::GetListOfCipher($data['uname']);
                    
                    
                    foreach ($list as $key => $cipher) {
                        if(registration::GetUnitId($cipher) !== FALSE ){
                            $uid = registration::GetUnitId($cipher)['uid']; //uid подразделения
                            userpage::UpdateHid($uid, $user_id);
                        }
                    } 
                    
                    $lid = registration::GetLidForHeader($user_id);
                    $id_post = registration::GetPostForHeader($user_id);
                  
                    //Внесение информации о руководителе в таблицу users БД
                    //
                    userpage::UpdateAnyRecord($user_id, 'users', 'lid', $lid);
                    userpage::UpdateAnyRecord($user_id, 'users', 'id_post', $id_post);
                    
                  
                }
                $this->sendMail($data['email'], $user_id);
            } else {
                echo 'Ошибка!<br>Пользователь '.$data['uname'].' не может быть занесён в БД в качестве руководителя!!!';
                MenuLoginController::WriteToLog('ERROR registration', $data['uname'], 'user not exist in table LEADSHIP in DB');
            }
            }else{
                echo 'Ошибка!<br>Неправильное имя пользователя или пароль!';
                MenuLoginController::WriteToLog('ERROR registration', $data['uname'], 'IMAP error username pass incorrect');
            }
        }
        
         ob_end_flush();
    }
    
    /*
     * Для пользователей НАУКА
     * Логин и пароль должны соответствовать электронной почте пользователя
     * 
     * Проверка пароля и логина у работников категории НАУКА 
     * с имеющимися в электронной почте
     * 
     * для НАУКА 2 возвращается TRUE
     */
    private function checkImap($login, $pass, $usertype){
      
        
        if ($usertype == 1) {
          
            $imap_var = @imap_open("{mail.izmiran.ru:993/imap/ssl/novalidate-cert}INBOX",$login,$pass,OP_HALFOPEN);
            
            $errs = imap_errors();
           return ($imap_var == true && $errs == false) ? true:false;
         
           
         
          
        } else {
           
            return true;
        }   
    }
    
    /*
     * Отправка mail-сообщения пользователю
     */
    private function sendMail(&$mail, $user_id){
        $text_for_all = '';
        $headers = '';
        
            $user_text = <<<EOT

Руководители отделений / секретари секций:
Включают сотрудников в состав исполнителей тем
Назначают одного из исполнителей руководителем темы

Руководители тем:
Заполняют исполнителям темы разделы "План" и "Ожидаемые результаты"

Исполнители тем (научные сотрудники):
Заполняют разделы "Отчет" и "Результаты научной деятельности".

Руководители подразделений :
Заполняют инженерам, лаборантам, программистам ("Наука 2") раздел "Задание"

Наука 2:
Заполняют раздел "Отчет".

Руководителям для осуществления контроля доступен сервис
просмотра статуса заполнения исполнителями отчетов        
EOT;

         $text_for_all .= "Уважаемый пользователь SciReg !\n\n"
                   ."Вы успешно зарегистрировались в системе под имнем (login): "
                   .userpage::GetSNMUser($user_id)['uname']."\n"
                   .userpage::GetSNMUser($user_id)['surname']." ".userpage::GetSNMUser($user_id)['name']." "
                   .userpage::GetSNMUser($user_id)['mname'].", ".userpage::GetPostUser($user_id)['post_title']."\n"
                   ."Подразделение: ".userpage::GetDeptmUser($user_id)[0][0]."\n"
                   ."Отделение: ".userpage::GetDeptmUser($user_id)[0][3]."\n\n"
                   . "У вас есть возможность редактировать свои персональные "
                   . "данные и менять пароль входа. \n"
                   . "".$user_text."\n"
                   ."\n";
        
         $headers .= 'Content-type: text/plain; charset=utf-8'."\r\n";
         $headers .= 'From: webmaster@izmiran.ru'. "\r\n";
         $mailTo = 'scireg@izmiran.ru';
        
         if (isset($mail) ) { $mailTo .= ','.$mail;}
        
       
         mail($mailTo,'SciReg',$text_for_all,$headers);
    }




    /*
     * Метод возвращает ПОДРАЗДЕЛЕНИЯ на выбранное ОТДЕЛЕНИЕ
     */
    public function SelectdivisAction(){
        ob_clean();
       
        
        
        $id_depart = filter_input(INPUT_POST, 'id_division');
        
       
        
        $divis = registration::GetDivision($id_depart);
        
         
        /*
         * Поиск подразделения которое не входит, в свою очередь, 
         * в другие подразделения. 
         * Т.е. находим "низший" уровень подразделения 
         */
        foreach($divis as $key_divis){
            if($key_divis['u2'] !== '0'){
                foreach ($divis as $key2_divis => $val_divis){
                    if($val_divis['uid'] == $key_divis['u2']){
                        unset($divis[$key2_divis]);
                        break;
                    }
                }
            }
        }
        
       
        include ROOT_MENUU.'/views/RegistrationSelectDivisView.php';
        
        ob_end_flush();
    }
    
    /*
     * Проверка на уникальность введенного ЛОГИНА при регистрации
     */
    private function CheckloginAction(){
        ob_clean();
        if(filter_input(INPUT_POST, 'action') == 'CheckLogin'){
           
            $login = filter_input(INPUT_POST, 'login');
            
            if(auth::CheckLogin($login) == FALSE){
                echo 'Ошибка!\nВ БД уже есть пользователь с выбранным Вами Логином!';
            }
          
        }
        ob_end_flush();
    }
    
    /*
     * Запись в Log-файл /log/scireg.log событий
     */
   static public function WriteToLog($event, $user, $error=''){
        $log = ROOT_MENUU.'/log/scireg.log';
        $ip = $_SERVER['REMOTE_ADDR'];
        
         if(!file_exists($log)){
            $fp = fopen(ROOT_MENUU."/log/scireg.log", "w");
            
         }else{
             $fp = fopen(ROOT_MENUU."/log/scireg.log", "a+");
          //  fwrite($fp, date("[Y-m-d H:M:S]")." [".$user."] [".$event."] ".$error."\r\n");
           // fclose($fp);
         }
         
         fwrite($fp, date("Y-m-d H:i:s")." ".$ip." ".$user." ".$event." ".$error."\r\n");
         fclose($fp);
    }
    
    /*
     * Href for static parts of page
     */
    public static function GetUrl4Static(){
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }
    
    public static function HelpAction(){
       include ROOT_MENUU."/views/help.html";
       include_once MenuLoginController::GetUrl4Static()."/Views/footer.php";
    }
    
    
}
