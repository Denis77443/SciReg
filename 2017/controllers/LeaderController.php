<?php
//ob_start();
include_once ROOT_MENUU.'/controllers/UserController.php';
//include_once ROOT_MENUU.'/controllers/AjaxController.php';



class LeaderController extends UserController{
    
    //Сервис
    public $podrazd = 'Подразделение';
    protected $search  = 'АБВГД';
    protected $status  = 'Статус (по подразд.)';
    protected $number;  // Количество зарегестрированных пользователей
    
   
    
    public function ___construct() {
       
        // parent::__construct();
        echo 'Конструктор Leader contr';
   //   include_once ROOT_MENUU.'/views/MenuView.php';
    }
    
    /*
     * Переопределение метода 
     */
   public function AjaxAction(){
       //echo 'AjaxAction  LeaderController';
       UserController::AjaxAction();
       //var_dump(UserController::AjaxAction());
       /*
         * Подключение контроллера поиска пользователей по алфавиту {АБВГД}
         */
        if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'search')){
       // if((isset($_GET['param']))&&($_GET['param'] == 'search')){
            include_once ROOT_MENUU.'/controllers/SearchjaxController.php';
        }
        
         if((null !== filter_input(INPUT_GET, 'param'))&&(filter_input(INPUT_GET, 'param') == 'insert_subject')){
           include_once ROOT_MENUU.'/controllers/InsertUpdateSubjectController.php'; 
        }
        
        if((null !== filter_input(INPUT_POST, 'action'))&&(filter_input(INPUT_POST, 'action') == 'showstatus')){
            $ajaxAction = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); 
           
            $this->CheckAction($ajaxAction);
           
        }
        
        
   }
   
   protected function CheckAction($ajaxAction){
       ob_clean();
       UserController::CheckAction($ajaxAction);
       //echo '<h2>'.$ajaxAction.'</h2>';
       if($ajaxAction == 'showstatus'){
           $this->ShowListUserStatus($this->user_id());
       }
       ob_end_flush();
   }
   
   
   
   /*
    * Пользователь к которому зашёл руководитель на страницу 
    * в категории Наука2/Наука {TRUE/FALSE}. Определяем категорию 
    * пользователя (Наука2/Наука)
    */
   protected function ScinceOrScince2($user_id){
       include_once ROOT_MENUU.'/models/access.php';
      // echo '<h2>ScinceOrScince2()</h2>';
       return access::GetTypeUser($user_id);
   }
   
   
   /*
    * Руководитель подразделения может устанавливать 
    * Задания для работников из категории Наука 2
    * 
    */
   protected function SetPlanDisabledOrNot($userpage){
       if($userpage == FALSE){
         //  var_dump($this->AccessUserPage($this->user_id()));
        $this->AccessUserPage($this->user_id());   
           
       }
   }
   
   /*
    * Список пользователей для пункта => СТАТУС
    */
   private function PrintListOfUsers($key, &$name_dep1, $lidlevel){
      
       if(($name_dep1 == '')&&($key['uid_dep'] != NULL)){
            $name_dep1 = $key['uid_dep'];      
               return array ("<fieldset style='clear:both'><legend>".$this->depar[$name_dep1].' ['.status::CountOfDep($this->depar[$name_dep1], $key['sci'], $lidlevel)['count'].']</legend>', $name_dep1);
       }else{
            if(($key['uid_dep'] !== $name_dep1)&&($key['uid_dep'] != NULL)){           
               $name_dep1 = $key['uid_dep'];
               return array('</fieldset><fieldset><legend>'.$this->depar[$name_dep1].' ['.status::CountOfDep($this->depar[$name_dep1], $key['sci'], $lidlevel)['count'].']</legend>', $name_dep1);
            }
       }
    } 
    
    private function PrintListOfUsers1($key, &$name_dep1, $lidlevel){
        
        $title = ($key['title'] == NULL) ? 'title2' : 'title';
        $uid_dep = ($key['uid_dep'] == NULL) ? 'uid_dep2' : 'uid_dep';
        $sci = ($key['sci'] !== NULL) ? 'sci' : 'sci2';
        
       if(($name_dep1 == '')&&(isset($key[$uid_dep]))&&($key[$uid_dep] != NULL)){
            $name_dep1 = $key[$uid_dep];  
            return array ("<fieldset style='clear:both'><legend>".$key[$title].' ['.status::CountOfDep($key[$title], $key[$sci], $lidlevel)['count'].']</legend>', $name_dep1);
           
       }else{
            if( (isset($key[$uid_dep]))&&($key[$uid_dep] !== $name_dep1)&&($key[$uid_dep] != NULL) ){           
               $name_dep1 = $key[$uid_dep];
               return array('</fieldset><fieldset><legend>'.$key[$title].' ['.status::CountOfDep($key[$title], $key[$sci], $lidlevel)['count'].']</legend>', $name_dep1);
            
            }
       }
       
    } 
    
  
   
   /*
     * Показывать пользователей со статусом выполнения работы
     * @param 
     * @return 
     */
    public function ShowListUserStatus(){
        $name = ''; // Научные работники
        $nameSc2 = ''; // Работники НАУКА 2
        $name_dep = '';
        $name_dep2 = '';
        
        $countScince = 0;
        $countScince2 = 0;
        
       
        $lidlevel = $_SESSION['lidlevel'];
       // $st = microtime(true);
        $output = status::GetUsersInDepartment($this->user_id(), $lidlevel);
        
     //  var_dump($output);
       // var_dump($lidlevel);
      //  var_dump($this->depar);
        
        if($lidlevel == 'secretaryhead'){
            $this->depar = status::GetDepForSecretary($this->user_id());
        }
        
      //  var_dump(status::GetDepForSecretary($this->user_id()));
       foreach ($output as $key){
          
           /*
            * Разбиение пользователей по подразделениям,
            * если формируется СТАТУС для HIGHLEADER
            */
           if(($lidlevel == 'highleader')||($lidlevel == 'highhead')){
               if($key['sci'] == '1'){
                  $name .= $this->PrintListOfUsers($key, $name_dep, $lidlevel)[0];
               }else{
                  $nameSc2 .= $this->PrintListOfUsers($key, $name_dep2, $lidlevel)[0];
               }
           }
           
           if( ($lidlevel == 'otdelleader')||($lidlevel == 'dephead')||
               ($lidlevel == 'secretaryhead')||($lidlevel == 'sectorleader')||
               ($lidlevel == 'lableader') ){
               
               $sci = ( (isset($key['sci2']))&&($key['sci2'] !== NULL) ) ? 'sci2':'sci';
                       
               if($key[$sci] == '1'){
                  $name .= $this->PrintListOfUsers1($key, $name_dep, $lidlevel)[0];
                  
               }
               
               if($key[$sci] == '2'){
                  $nameSc2 .= $this->PrintListOfUsers1($key, $name_dep2, $lidlevel)[0];
                   
               }
           
           }
   
           
           
          
           
           
           
           if((isset($key['uid_otd']))&&($key['uid_otd'] !== NULL)){
               if($key['sci'] == '1'){
                    $name .= "<div class='fio_stat'><a href='index.php?url=userpage&id=".$key['uid_otd']."'>".$key['name_otd']."</a></div>";
               }
               if($key['sci'] == '2'){
                    $nameSc2 .= "<div class='fio_stat'><a href='index.php?url=userpage&id=".$key['uid_otd']."'>".$key['name_otd']."</a></div>";
               }
           }
           
           if($key['uid'] !== NULL){
              if( (isset($key['sci2']))&&($key['sci2'] !== NUll) ){ 
               if($key['sci2'] == '1'){
                    $name .= "<div class='fio_stat'><a href='index.php?url=userpage&id=".$key['uid']."'>".$key['name']."</a></div>";
               }
               
               if($key['sci2'] == '2'){
                    $nameSc2 .= "<div class='fio_stat'><a href='index.php?url=userpage&id=".$key['uid']."'>".$key['name']."</a></div>";
               }
              }else{
                 if($key['sci'] == '1'){
                    $name .= "<div class='fio_stat'><a href='index.php?url=userpage&id=".$key['uid']."'>".$key['name']."</a></div>";
               }
               
               if($key['sci'] == '2'){
                    $nameSc2 .= "<div class='fio_stat'><a href='index.php?url=userpage&id=".$key['uid']."'>".$key['name']."</a></div>";
               } 
              }
           }
          
         /*  if($key['name'] !== NULL){
              // $name .= '<div>IIIII</div>';
              $name .= ScinceController::ShowStatus($key['uid']);
              
           }*/
        /*   
           foreach($this->ShowStatus1($key['uid']) as $key2 => $value2){
               $name .= "<div class= '".$value2."'>".$key2."</div>";
           }
          */ 
          // var_dump(AjaxController::ShowStatus($key['uid']));
        //   $name .= '<div>'.AjaxController::ShowStatus($key['uid']).'</div>';
          // $name .= '<div>ssss</div>';
         //  var_dump($this->ShowStatus1($key['uid']));
           
           if($key['name'] !== NULL){
           
           //Print subject
    
            if(($key['sub'] != 0)||($key['sub'] != false)){
                
                if($key['sci'] !== NULL){
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_gr'>Т</div>";
                    }
                }else{
                    if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_gr'>Т</div>";
                    }
                }
                
            }else{ 
                if($key['sci'] !== NULL){
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_rd'>Т</div>";
                    }
                }else{
                    if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_rd'>Т</div>";
                    }
                }
                
            }
           
            //Print plan
            if(($key['plan'] != NULL)&&(strlen($key['plan'])>40)){
                if($key['sci'] !== NULL){
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_gr'>П</div>";
                    }
                    if($key['sci'] == '2'){
                        $nameSc2 .= "<div class='subject_stat_gr'>П</div>";
                    }
                }else{
                    if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_gr'>П</div>";
                    }
                    if($key['sci2'] == '2'){
                        $nameSc2 .= "<div class='subject_stat_gr'>П</div>";
                    }
                }
            }else{  
                if($key['sci'] !== NULL){
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_rd'>П</div>";
                    }
                    if($key['sci'] == '2'){
                        $nameSc2 .= "<div class='subject_stat_rd'>П</div>";
                    }
                }else{
                   if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_rd'>П</div>";
                    }
                    if($key['sci2'] == '2'){
                        $nameSc2 .= "<div class='subject_stat_rd'>П</div>";
                    } 
                }
            }
            
            //Expected result
      
            if(($key['exp'] != NULL)&&(strlen($key['exp'])>40)){
                if($key['sci'] !== NULL){
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_gr'>Р</div>";
                    }
                }else{
                    if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_gr'>Р</div>";
                    }
                }
                
            }else{
                if($key['sci'] !== NULL){
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_rd'>Р</div>";
                    }
                }else{
                    if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_rd'>Р</div>";
                    }
                }
            }
       
            //Report
      
            if(($key['report'] != NULL)&&(strlen($key['report'])>80)){
               if($key['sci'] !== NULL){ 
                if($key['sci'] == '1'){
                    $name .= "<div class='subject_stat_gr'>О</div>";
                }
                if($key['sci'] == '2'){
                    $nameSc2 .= "<div class='subject_stat_gr'>О</div>";
                }
               }else{
                  if($key['sci2'] == '1'){
                    $name .= "<div class='subject_stat_gr'>О</div>";
                }
                if($key['sci2'] == '2'){
                    $nameSc2 .= "<div class='subject_stat_gr'>О</div>";
                } 
               }
            }else{
                if($key['sci'] !== NULL){ 
                    if($key['sci'] == '1'){
                        $name .= "<div class='subject_stat_rd'>О</div>";
                    }
                    if($key['sci'] == '2'){
                        $nameSc2 .= "<div class='subject_stat_rd'>О</div>";
                    }
                }else{
                   if($key['sci2'] == '1'){
                        $name .= "<div class='subject_stat_rd'>О</div>";
                    }
                    if($key['sci2'] == '2'){
                        $nameSc2 .= "<div class='subject_stat_rd'>О</div>";
                    } 
                }
            }
      
            
            //Other
       if(((trim($key['air']))     != null)||
          ((trim($key['aif']))     != null)||
          ((trim($key['mono']))    != null)||
          ((trim($key['conf']))    != null)||
          ((trim($key['course']))  != null)||
          ((trim($key['patents'])) != null)||
          ((trim($key['leader']))  != null)||
          ((trim($key['other']))   != null)){
           if($key['sci'] !== NULL){ 
             if($key['sci'] == '1'){
                $name .= "<div class='subject_stat_gr'>С</div>";
             }
           }else{
             if($key['sci2'] == '1'){
                $name .= "<div class='subject_stat_gr'>С</div>";
             }  
           }    
       }else{
          if($key['sci'] !== NULL){ 
             if($key['sci'] == '1'){
                $name .= "<div class='subject_stat_rd'>С</div>";
             }
           }else{
             if($key['sci2'] == '1'){
                $name .= "<div class='subject_stat_rd'>С</div>";
             }  
           } 
                
       }
           }
           if($key['sci'] !== NULL){
            if($key['sci'] == '1'){$countScince++;}
            if($key['sci'] == '2'){$countScince2++;}
           }else{
               if(isset($key['sci2'])){
                 if($key['sci2'] == '1'){$countScince++;}
                 if($key['sci2'] == '2'){$countScince2++;}
               }
           }
       }
     
        include_once ROOT_MENUU.'/views/LeaderStatusView.php';
        
        
    }
    /*
     * Статус по подразделению
     */
    public function StatusAction(){
       
        include_once ROOT_MENUU.'/models/status.php';
      
        var_dump($_REQUEST);
       $this->ShowListUserStatus();
        
        
        include_once $this->GetUrl4Static().'/Views/footer.php';
        return true;
    }
    
    
    /*
     * Показывать пользователей найденных через 
     * ПОИСК ПО АЛФАВИТУ
     */
    public function ShowListOfUsers(){
        include_once ROOT_MENUU.'/models/search.php';
        
        $this->number = search::NumberOfUsers();
        
        $abc = array();
        $outputletters = '';
        
        foreach (range(chr(0xC0), chr(0xDF)) as $b)
        $abc[] = iconv('CP1251', 'UTF-8', $b);
        
        foreach($abc as $value => $key){
            
          if(($key != 'Ъ')&&
             ($key != 'Й')&&
             ($key != 'Ь')&&
             ($key != 'Ы')){ 
            
            $outputletters .= "<a class='click2' name='click1'  id='".$key."' style='padding-right: 15px; padding-left: 15 px;' href=''>".$key."</a>";
                if($value == '15'){
                     $outputletters .= "<br><br>";
                }
          }
        }
        
        include_once ROOT_MENUU.'/views/LeaderSearchView.php';
       
        include_once $this->GetUrl4Static().'/Views/footer.php';
    }


    /*
     * Поиск по афавиту
     */
    
    public function SearchAction(){
        //$this->test = 'SVINNNNNN';
       // var_dump($this->test);
     //   $this->ShowItemMenuPodrazd($_SESSION['lidlevel']);
      //  include_once ROOT_MENUU.'/views/MenuView.php';
        $this->ShowListOfUsers();
    }
    
    
    /*  Переопределение метода SubjectcatAction()
     *  с целью вывода пункта меню ПОДРАЗДЕЛЕНИЕ 
     *  Показывать категории тем
     */ 
     public function SubjectcatAction(){
         $this->ShowItemMenuPodrazd($_SESSION['lidlevel']);
       //  include_once ROOT_MENUU.'/views/MenuView.php';
         include_once ROOT_MENUU.'/views/UserSubjectcatView.php';
         include_once $this->GetUrl4Static().'/Views/footer.php';
         return true;
     }
    
    
     
     public function AccessUserPage($user_id){
      //  echo '<h2>AccessUserPage in LeaderController</h2>';
         $result = false;
         
         
         if(UserController::AccessUserPage($user_id) == TRUE){
            // echo '<a style=color:red>Руководитель темы из LeaderController()</a></br>';
             $result = true;
         }else{
             include_once ROOT_MENUU.'/models/access.php';
             $hid_array = access::YourColleague($user_id);
        //  var_dump($hid_array);
        //  var_dump($user_id);
             foreach($hid_array as $key){
                 foreach($key as $key_hid => $value_hid){
                     
                     if($value_hid == $_SESSION['user_id']){
                         $result = true;
                      //   echo 'Руководитель подразделения, для данного пользователя LeaderController()<br>';
                         
                         /*
                          * Если руководитель подразделения 
                          * является руководителем для работника НАУКА 2, 
                          * то он имеет право устанавнивать ЗАДАНИЕ(ПЛАН НИР)
                          * работнику Наука 2
                          */
                         if($this->ScinceOrScince2($this->user_id()) == TRUE){
                            //echo "<h2>Это РАБОТНИК НАУКА 2</h2>";
                            $this->disabled = '';
                          }
                         
                         break;
                     }
                 }
             }
             
             // Суперпользователь, доступ к любому на страницу
             if(access::GetSuperAccess($_SESSION['user_id']) == TRUE){
                 $result = true;
             }
            
         }
           return $result;  
         }
       
     
     
     
     
     
     
   
     
    
    /*
     * Action выводит список пользователей
     * находящихся в выбранном подразделении
     */
    public function UsersindivisAction(){
       // $id_lab;
        $this->ShowItemMenuPodrazd($_SESSION['lidlevel']);
        //include_once ROOT_MENUU.'/views/MenuView.php';
        
        $id_lab = ($this->id_otdel == filter_input(INPUT_GET, 'lid')) ? $this->id_otdel : filter_input(INPUT_GET, 'lid');
     
        include_once ROOT_MENUU.'/models/departments.php';
       
           include_once ROOT_MENUU.'/models/access.php'; 
          // var_dump(access::GetAccess($_SESSION['user_id'], $id_lab));
           if((access::GetAccess($_SESSION['user_id'], $id_lab) == FALSE)||
                   (empty($id_lab))||
                   (!isset($id_lab))){
               $this->error = 'Ошибка доступа';
               include ROOT_MENUU.'/views/Error.html';
           }else{
       
               
        $this->title_lab = array ('title' => departments::GetUsersInDivis($id_lab)[0]['title'], 
                                  'uid' => $id_lab);
        
        $this->head_labs = array ('name' => departments::GetUsersInDivis($id_lab)[0]['boss'], 
                                  'id' =>departments::GetUsersInDivis($id_lab)[0]['boss_id'] );
        
        $this->users = departments::GetUsersInDivis($id_lab);
        
        if($this->users == FALSE){
            $this->error = 'Ошибка доступа';
            include_once ROOT_MENUU.'/views/Error.html';
        }else{
        
        include_once ROOT_MENUU.'/views/LeaderUsersInDivisView.php';
        }
           }
        include_once $this->GetUrl4Static().'/Views/footer.php';
       return true;
        
    }






    /*
     * Action выводит список отделов, центров 
     * в которые включены - лаборатории и сектора
     * т.е. не последнее подразделение входящее в ОТДЕЛЕНИЕ
     */
    public function LaboratoryAction(){
        
        $id_lab;
        
        if($this->id_otdel == filter_input(INPUT_GET, 'lid')){
           $id_lab = $this->id_otdel; 
        }else{
            $id_lab = filter_input(INPUT_GET, 'lid');
        }
        
        include_once ROOT_MENUU.'/models/departments.php';
        
      // var_dump($this->isset_lab);
      // var_dump($this->isset_sec);
       //var_dump($this->isset_sec);
       //var_dump($this->office);
       //var_dump($this->id_otdel);
        
        if(/*($this->isset_sec[$key_of] == $id_lab)||
           ($this->isset_lab[$key_of] == $id_lab )||*/
            (in_array($id_lab, $this->isset_lab))||
            (in_array($id_lab, $this->isset_sec))){
        
        
       if(empty($id_lab)||
          departments::GetLaboratory($id_lab)[0]['main_title'] == NULL){
            $this->error = 'Ошибка доступа';
            include_once ROOT_MENUU.'/views/Error.html';
        }else{
        
          
            
        $this->title_lab = array ('title' => departments::GetLaboratory($id_lab)[0]['main_title'], 'uid' => $id_lab);
        
        
        $this->head_labs = departments::GetLaboratory($id_lab);
        
        $this->labs = array_column(departments::GetLaboratory($id_lab), 'title', 'uid');
        $this->sec = array_column(departments::GetLaboratory($id_lab), 'title_sec', 'uid_s');
        
        
            include_once ROOT_MENUU.'/views/LeaderLaboratoryView.php';
        
        }
           }else{
               $this->error = 'Ошибка доступа';
               include_once ROOT_MENUU.'/views/Error.html';
           }
        include_once $this->GetUrl4Static().'/Views/footer.php';
       return true;
        
    }
    
    
    public function DepartmentAction(){
        
       
        
        
       
        $id_deprt = filter_input(INPUT_GET, 'did');
        include_once ROOT_MENUU.'/models/departments.php';
        
        //var_dump(departments::GetDepartment($id_deprt));
        
        $this->head_depar = departments::GetDepartment($id_deprt);
       
        $this->labs = array_column(departments::GetDepartment($id_deprt), 'title', 'uid');
        
       // var_dump($this->depar[$id_deprt]);
       // var_dump($id_deprt);
        
        if(!isset($this->depar[$id_deprt])){
            $this->error = 'Ошибка доступа';
            include_once ROOT_MENUU.'/views/Error.html';
            
        }else{
           
            include_once ROOT_MENUU.'/views/LeaderDepartmentView.php';
        }
            include_once $this->GetUrl4Static().'/Views/footer.php';
        
       return true;
        
    }


    /*
     * Метод возвращает из Модели(models/menu.php) 
     * ВСЕ пункты меню ПОДРАЗДЕЛЕНИЕ
     */
    protected function array_menu(){
        return menu::getDepartment();
    }
    
    /*
     * Инициализация подпунктов меню пункта ПОДРАЗДЕЛЕНИЕ
     */
    protected function array_menu_items(){
        
       // echo "<h2><a style = 'color:Gold;' >ARRAY_M START: ".(microtime(true) - INDEX_START)."</a></h2>";
       /* 
        $this->depar = array_column(menu::getDepartment(), 'title', 'uid');
        //echo "<h2><a style = 'color:Peru;' >DEPAR: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        
        
        $this->depar_hid = array_column(menu::getDepartment(), 'uid', 'hid');
        //echo "<h2><a style = 'color:PeachPuff;' >DEPAR-HID: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        $this->office = array_column(menu::getDepartment(), 'u4_o', 'title_o');
        //echo "<h2><a style = 'color:DarkGoldenRod;' >OFFICE: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        $this->id_otdel = array_column(menu::getDepartment(), 'uid_o','title_o');
        //echo "<h2><a style = 'color:MediumVioletRed;' >ID_OTDEL: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        $this->otdel_hid = array_column(menu::getDepartment(), 'uid_o', 'hid_o');
        //echo "<h2><a style = 'color:Pink;' >TDEL_HID: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        $this->isset_lab = array_column(menu::getDepartment(), 'u3_l', 'title_o');
       // echo "<h2><a style = 'color:LightSalmon;' >ISSET_LAB: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        $this->laborat = array_column(menu::getDepartment(), 'u3_l', 'title_l');
        
        
        $this->isset_sec = array_column(menu::getDepartment(), 'u2_s', 'title_o');
       // $this->laborat_hid = array_column(menu::getDepartment(), 'uid_l', 'hid_l');
        $this->id_laborat = array_column(menu::getDepartment(), 'uid_l', 'title_l');
        
        $this->sector = array_column(menu::getDepartment(), 'u2_s', 'title_s');
        
        $this->id_sector = array_column(menu::getDepartment(), 'uid_s', 'title_s');
        //echo "<h2><a style = 'color:DarkOliveGreen;' >ARRAY_M END: ".(microtime(true) - INDEX_START)."</a></h2>";
       // return $this->depar;
        
        //echo "<h2><a style = 'color:black;' >foreach__: ".(microtime(true) - INDEX_START)."</a></h2>";
          
        * /*
        */
         
            foreach(menu::getDepartment() as $kk){
                $this->depar[$kk['uid']] = $kk['title'];
                $this->depar_hid[$kk['hid']]= $kk['uid'];
                $this->office[$kk['title_o']] = $kk['u4_o'];
                $this->id_otdel[$kk['title_o']] = $kk['uid_o'];
                $this->otdel_hid[$kk['hid_o']] = $kk['uid_o'];
                $this->isset_lab[$kk['title_o']] = $kk['u3_l'];
                $this->laborat[$kk['title_l']] = $kk['u3_l'];
                $this->isset_sec[$kk['title_o']] = $kk['u2_s'];
                $this->id_laborat[$kk['title_l']] = $kk['uid_l'];
                $this->sector[$kk['title_s']] = $kk['u2_s'];
                $this->id_sector[$kk['title_s']] = $kk['uid_s'];
        }
      
        
      //  echo "<h2><a style = 'color:black;' >foreach+++: ".(microtime(true) - INDEX_START)."</a></h2>";
    }
    
    
    /*
     *  Формирование пункта меню ПОДРАЗДЕЛЕНИЕ 
     *  для определенных групп руководителей
     */
    protected function ShowItemMenuPodrazd($lidlevel){
        //var_dump($lidlevel);
       //  echo "<h2><a style = 'color:red;' >SHOW ITEM MENU START: ".(microtime(true) - INDEX_START)."</a></h2>";
        $this->array_menu_items();
        
       // var_dump($this->array_menu_items());
        
        // echo "<h2><a style = 'color:MediumOrchid;' >After Array_menu_item: ".(microtime(true) - INDEX_START)."</a></h2>";
        
        
        if($lidlevel == 'otdelleader'){
          
            $array_m = menu::getDepartmentsLowLevel();
            
            foreach($array_m as $key_array){
                if($key_array['uid_4'] !== NULL){
                   $this->depar     = array($key_array['uid_4']     => $key_array['title_4']); 
                }
                
                if($key_array['uid_3'] !== NULL){
                   $this->office     = array($key_array['title_3']     => $key_array['uid_4']);
                   $this->isset_lab = array($key_array['title_3'] => $key_array['uid_3']);
                }
            }
            
          
        }
        
        //var_dump($this->otdel_hid);
        if($lidlevel == 'lableader'){
            if(isset($this->otdel_hid[$_SESSION['user_id']])){
            //echo 'есть такое значение '.$otdel_hid[$_SESSION['user_id']];
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
            
            
            
          /*
           * Определение переменным $this->isset_lab и $this->isset_sec 
           * точных значений ПОДРАЗДЕЛЕНИЯ в котором руководит lableader
           *  
           */
            foreach ($this->isset_lab as $key => $value){
                if(key_exists($key, $this->office)){
                    $tmp_lab[$key] = $value;
                }
            }
            $this->isset_lab = $tmp_lab;
            
            
            foreach ($this->isset_sec as $key => $value){
                if(key_exists($key, $this->office)){
                    $tmp_sec[$key] = $value;
                }
            }
            $this->isset_sec = $tmp_sec;
            
            
        }
        
        if($lidlevel == 'sectorleader'){
          //  var_dump(menu::getDepartmentsLowLevel());
            $array_deprtm = menu::getDepartmentsLowLevel();
            $this->depar     = array($array_deprtm[0]['uid_4']     => $array_deprtm[0]['title_4']);
            $this->office    = array($array_deprtm[0]['title_2']     => $array_deprtm[0]['uid_4']);
            $this->isset_lab = array($array_deprtm[0]['title_2']     => $array_deprtm[0]['uid_3']);
            $this->sector    = array($array_deprtm[0]['title_1']     => $array_deprtm[0]['uid_2']);
            $this->id_sector = array($array_deprtm[0]['title_1']     => $array_deprtm[0]['uid_1']);
           
          }
          
          
        
          
         
        
        
        if($lidlevel == 'dephead'){
          foreach ($this->depar_hid as $key => $value){
            if($key == $_SESSION['user_id']){
                $rr[$value] = $this->depar[$value];
            }
          }
        
          $this->depar = $rr;
          //echo "<h2><a style = 'color:red;' >SHOW ITEM MENU END:  ".(microtime(true) - INDEX_START)."</a></h2>";
        }
        
        
    }

    
    
    
        
  
   
    
        
}
    
    
