<?php
ob_start();

abstract class test {

    // public $start;
    private $home = 'Home';   //Home
    protected $page_name;               //Users name 
   // private $service = 'Сервис'; //Сервис 
    protected $profile = 'Профиль'; //Профиль
    protected $subject;   //Тема (если пользователь рук. темы)
    //Сервис    
    protected $subject_list = 'Темы (просмотр)';  //Сервис->Темы (просмотр)
    protected $deleteSubj; //Сервис->Темы (удаление)
    protected $title_sub; //Темы где пользователь руководитель 
    protected $arraySubUser; //Сотрудники и темы относящиеся к руководителю тем 
    protected $disabled = ' readonly';  //Свойство readonly по умолчанию 
    //для полей <texarea> ПЛАН и 
    //ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ 
    protected $autosave = ''; // Переменная зависимая от $disabled
    // если $disabled != readonly, то 
    // $autosave =  сохраняет в БД содержимое 
    // поля  textarea  
    protected $subjectLeader; //Subject
    protected $disabled_rep = ''; // Свойство readonly в блоках ОТЧЕТ 
    // и РЕЗУЛЬТАТЫ НАУЧНОЙ ДЕЯТЕЛЬНОСТИ
    // в том случае если страницу смотрит 
    // какой-либо руководитель
    protected $message = ''; //Сообщение о том что у пользователя 
    //не установлены темы  
    protected $subj_output1 = '';
    protected $subj_output2 = '';
    protected $subj_output3 = '';
    private $token = '';
    private $get_url;
    protected $error;

    public function __construct() {

        

        /* Исключения для секретарей
         * у секретарей нет пункта меню ПОДРАЗДЕЛЕНИЕ
         */

        if ($_SESSION['lidlevel'] === 'secretaryhead') {
            $this->podrazd = '';
        }


        if (!empty($this->podrazd)) {
            $this->ShowItemMenuPodrazd($_SESSION['lidlevel']);
        }

        $this->ShowMenu();
        
    }
    
    
    public function HelpAction(){
        MenuLoginController::HelpAction();
    }


    /*
     * Активная папка программы -2016- -2017-
     * Текущий год
     */

    public static function currientYear() {
        $var_sel = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
        $str1 = preg_match("/\d\d\d\d/", $var_sel, $match);
        $str = $match[0];
        return $str;
    }

    /*
     *  Вывод на экран ПЛАНа или ОТЧЁТа в формате PRE или HTML
     */

    public function PreviewAction($param) {
        /*
         * Проверка доступа к методу
         */
        if ( ($this->AccessUserPage($this->user_id()) == FALSE) &&
             ($this->user_id() !== $_SESSION['user_id']) ) {

            //echo 'Ошибка доступа!!!!';
            $this->error = 'Ошибка доступа!';
            include ROOT_MENUU . '/views/Error.html';
            return false;
            
        } else {
            
            // $key_param   - формат вывода PRE или HTML
            // $value_param - данные ПЛАН или ОТЧЁТ  
            
            foreach ($param as $key_param => $value_param) {

                // Получаем данные из БД {План или Отчет в зависимости от $value_param}   
                $pre_output = userpage::IssetPlanNIR($this->user_id())[0][$value_param];

                if ($key_param == 'pre') {
                    include ROOT_MENUU . '/views/OutputPreView.php';
                }

                if ($key_param == 'html') {
                    include ROOT_MENUU . '/views/OutputHtmlView.php';
                }
            } 
       }
    }
    
    
    /*
      * Вывод списка дополнительных файлов к ОТЧЕТУ
      */
     public function ShowFiles(){
          
          $result = [];
                  
          $usrDir = ROOT_MENUU.'/files/'.$this->user_id();
         if(! is_dir($usrDir)){
          
         }else{
            
            
             $filesShow = array_diff(scandir($usrDir,1),array('..', '.'));
                                   
             
             foreach($filesShow as $key =>$value){
                 
                 $result[$key]['name'] = $value;
                 $result[$key]['size'] = $this->ShowSize(filesize($usrDir."/".$value));
                 if ($this->user_id() === $_SESSION['user_id']) {
                     $result[$key]['del'] = 1;
                 }else{
                     $result[$key]['del'] = 0;
                 }
               
             }
             
         }
         return $result;
         
     }
     
         
    /*
     * Размер файла
     */ 
    private function ShowSize($file) {
        if($file < 1000){
            return "(".$file." B)";
        }
        
        if($file > 1000 && $file < 1000000){
            return "(".round(($file/1000),1)." KB)";
        } else {
        return "(".round(($file/1000000),1)." МB)";
        }     
    } 
    
    
    
    
    
    
    
    /*
     * Latex
     * Вывод на экран ОТЧЁТа в формате Latex
     */

    public function LatexAction() {
        $reqest = file_get_contents('php://input');
       

        /* Проверка доступа - 
         /* доступ к формированию отчета Latex возможен
         /* ТОЛЬКО в случае доступа на страницу пользователя
         * 
         */

        if (($this->AccessUserPage($this->user_id()) == FALSE) &&
                ($this->user_id() !== $_SESSION['user_id'])) {

            //echo 'Ошибка доступа!!!!';
            $this->error = 'Ошибка доступа!';
            include ROOT_MENUU . '/views/Error.html';
            return false;
        } else {

            $title_latex = 'Отчет о выполнении государственных заданий за ' . $this->currientYear() . ' г.';

            include ROOT_MENUU . '/models/latex.php';
            include ROOT_MENUU . '/models/subject.php';

            $data = latex::GetDataForLatex($this->user_id())[0];

            $uname = $data['uname'];

            $text = "\\documentclass[a4paper,12pt]{article}\n"
                    . "\usepackage[utf8]{inputenc}\n"
                    . "\usepackage[russian]{babel}\n"
                    . "\usepackage[margin=1in]{geometry}\n"
                    . "\usepackage{amssymb}\n"
                    . "\DeclareUnicodeCharacter{00AB}{<<}\n"
                    . "\DeclareUnicodeCharacter{00BB}{>>}\n"
                    . "\DeclareUnicodeCharacter{00B0}{\$^\circ\$}\n"
                    . "\DeclareUnicodeCharacter{00BA}{\$^\circ\$}\n"
                    . "\DeclareUnicodeCharacter{02DA}{\$^\circ\$}\n"
                    . "\DeclareUnicodeCharacter{00B1}{\$\pm\$}\n"
                    . "\DeclareUnicodeCharacter{00D7}{\$\\times\$}\n"
                    . "\DeclareUnicodeCharacter{2264}{\$\leqslant\$}\n"
                    . "\DeclareUnicodeCharacter{2265}{\$\geqslant\$}\n"
                    . "\DeclareUnicodeCharacter{3C1}{\$\\rho\$}\n"
                    . "\DeclareUnicodeCharacter{3C3}{\$\sigma\$}\n"
                    . "\\begin{document}";

            $text .= "\centerline{\Large " . $title_latex . "}\n \\vskip 1cm";
            $text .= "{\\flushleft";
            $text .= "{\large\bf " . $data['name_post'] . " }\\\\";

            $text .= $data['title_first'] . "\\\\"; //First depart.

            for ($i = 2; $i < 5; $i++) {
                if ($data['title_' . $i] != '') {
                    $text .= $data['title_' . $i] . "\\\\";  //Other depart.
                }
            }

            /*
             * Установлены ли темы у пользователя 
             * Если темы никогда не были установлены, 
             * то выборка только из таблицы USERS
             */

            if ((array_key_exists('sub', $data) != FALSE) &&
                    ($data['sub'] != 0) && (!empty($data['sub']))) {

                $text .= "\\vskip 1cm";
                $text .= "{\large\bf Темы:}\\\\";
                $data_subject = subject::GetUserSubjects($this->user_id());

                foreach ($data_subject as $key_subject) {
                    $text .= "{" . $key_subject['title_p'] . "}\\\\";
                    $text .= "\hspace{1.5cm}{" . $key_subject['title'] . "}\\\\";
                }

                $text .= "}";
                $text .= "\\vskip 1cm";
              //  $text .= 'dkkdjkd';
                $text .= $reqest;
                
                if(!empty($this->ShowFiles())){
                    $text .= "\\vskip 1cm";
                    $text .= '{\large\bf Файлы:}\\\\';
                    
                    foreach ($this->ShowFiles() as $key => $val){
                       $text .= str_replace('_','\_',$val['name']).'\\\\'; 
                    }  
                }
                
                $text .= "\\end{document}";
            } else {
                $text .= "\\vskip 1cm";
                $text .= "темы не заданы\\\\";
                $text .= "}";
                $text .= "\\vskip 1cm";
                $text .= "\\end{document}";
            }
            
            if((!empty($reqest))||(empty($reqest)&&(!file_exists("/tmp/" . $uname . ".pdf")))){
           //   MenuLoginController::WriteToLog('Жуткая проверка', 'LatexAction');
            $fp = fopen("/tmp/" . $uname . ".tex", "w");
            fwrite($fp, $text);
            fclose($fp);

            putenv('TEXMFVAR=/tmp/.texmf-var');
            $PATH = getenv('PATH');
            $TEXBIN = '/usr/share/texmf/bin';
            putenv("PATH=$PATH:$TEXBIN");
            
            echo exec("pdflatex -output-directory /tmp " . $uname . ".tex");
            }
            $filename = "/tmp/" . $uname . ".pdf";
            
            $this->LatexFatalError($uname);
            
            if (file_exists($filename)) {
                 
                header('Content-type: application/pdf');
                header('Content-disposition: inline; filename = ' . $uname . '.pdf');
              
               readfile('/tmp/' . $uname . '.pdf');
              
             
                if(empty($reqest)){
                unlink('/tmp/' . $uname . '.pdf');}
                
            } else {
                ob_clean();
                
                $handle = fopen('/tmp/' . $uname . '.log', 'r');
                        
                readfile('/tmp/' . $uname . '.log');
                
                $buffer = fgets($handle, 40);
                
                MenuLoginController::WriteToLog('ERROR LATEX', $uname);
                ob_end_flush();
               
            }
            return true;
        }
    }
    
    /*
     *  Проверка log-файла на строку Fatal error occurred
     *  тот случай, когда Latex все-равно создаёт pdf-файл, 
     *  но он повреждён и не считывается
     */
    private function LatexFatalError($uname){
        $result = explode("\n", file_get_contents('/tmp/' . $uname . '.log'));
        
        foreach ($result as $key => $value) {
            if ( strpos($value, 'Fatal error occurred') ) {
              //Если pdf готов, то удаляем его => он повреждён
                if (file_exists('/tmp/' . $uname . '.pdf')) {
                   
                    MenuLoginController::WriteToLog('LATEX FATAL ERROR', $uname);
                    unlink('/tmp/' . $uname . '.pdf');
                }
            }    
        }
    }


    public function Show_GetUrl($get_url) {
        $this->get_url = $get_url;
        return $this->get_url;
    }

    /*
     * Показать меню
     */

    public function ShowMenu() {

        $url = $this->Show_GetUrl(filter_input(INPUT_GET, 'url'));

        /*
         * Нет меню когда идет вывод на экран в формате PRE/HTML 
         */
        if (($url !== 'preview') && ($url !== 'latex') && ($url !== 'openfile')) {
            include_once ROOT_MENUU . '/views/MenuView.php';
        }
    }

    
    
    
}
