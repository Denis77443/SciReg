<?php


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


if(!defined('ROOT_MENUU')){ die('access denied');}

class Router {
    //put your code here
    private $routes;
    private $url;
     
   
    
   
     
    public function __construct(){
       define('INDEX_START' , microtime(true), true);
      
       //define('INDEX_START' , $_SERVER['REQUEST_TIME'], true);
      //echo "<h2><a style = 'color:blue;' >КОНСТРУКТОР ROUTER".(microtime(true) - INDEX_START)."</a></h2>";
         //var_dump(get_class());
        
        session_start();
         
        
        //echo '<h1>Конструктор Router</h1>';
        
        if(isset($_SESSION['user_id'])){
            
            
            include ROOT_MENUU.'/controllers/lidlevel.php';
             
        }else{
           
            /*
             * Меню ЛОГИНА и РЕГИСТРАЦИИ
             * Меню когда пользователь не залогинен или не зарегистрирован
             */
            $url_index = '';
            
            if(filter_has_var(INPUT_GET, 'url')){
                $url_index = '';
            }else{
                $url_index = '../';
            }
            include_once ROOT_MENUU.'/views/MenuLoginView.php';
        }
        
        $routesPath = ROOT_MENUU.'/config/routes1.php';
        $this->routes = include($routesPath);
        
        //var_dump($routesPath);
        
      
    }
    
   // public function __destruct() {
     //   echo '<h1>Конструктор Router уничтожен</h1>';
   // }

    
   /*
    * Текущая папка года - программа
    */
    public static function currientYear(){
      
    
      preg_match("/\d\d\d\d/",filter_input(INPUT_SERVER,'SCRIPT_NAME') , $match);
      $str = $match[0];
      return $str;
    }
    
    
    

    private function user_type(){
      if(isset($_SESSION['user_id'])){  
        $db_pdo = DB::connection();
        $lidlev = lidlevel::get_lid($db_pdo, $_SESSION['user_id']);
        $lidlev->nomber_level();
        return lidlevel::$user_type;
      }
      
    }


    public function run(){
       
     //   echo "<h2>стр. ".__LINE__." <a style='color:white'>Запуск метода RUN()".(microtime(true) - INDEX_START)."</a></h2>";
        
      //  var_dump($this->routes);
        $classname = '';
        $classname1 = '';
        $actionname = '';
        $url = '';
        $param = '';
        
       
        //Главная страница
        if(!isset($_SESSION['user_id'])&&!isset($_GET['url'])){
            $classname = $this->routes[0][3].'Controller';
            $actionname = $this->routes[1][12].'Action';
           
        }
        
    //   echo "<h2>стр. ".__LINE__." <a style='color:black'>Запуск метода RUN()".(microtime(true) - INDEX_START)."</a></h2>";
       
        if(isset($_GET['url'])&&!isset($_SESSION['user_id'])){
            $classname = $this->routes[0][3].'Controller';
            $url = $_GET['url'];
             if(in_array($url, $this->routes[1])){
                 $key = array_search($url, $this->routes[1]);
                 $actionname = ucfirst($this->routes[1][$key]).'Action';
            //     echo 'Time of first class  '.(microtime(true) - INDEX_START);
             }
          //  echo("<h1>Класс - $classname<br>Метод -$actionname ".__LINE__."</h1>");
           // var_dump($_SESSION);
          //   echo 'Time RUN  '.(microtime(true) - INDEX_START);
        }
        
      //  echo "<h2>стр. ".__LINE__." <a style='color:yellow'>Запуск метода RUN()".(microtime(true) - INDEX_START)."</a></h2>";
        
        if(isset($_SESSION['user_id'])){
        
   
            
            foreach($this->routes[0] as $value){
               // echo "<h2>стр. ".__LINE__." <a style='color:blue'>Foreach итерация".(microtime(true) - INDEX_START)."</a></h2>";
              
               if(preg_match("~$value~", $this->user_type())){
                   $classname = ucfirst($value).'Controller'; 
                   break;
               }   
            }
            
         //  echo '<br />NOT EXPERIMENT --- '.$classname.'<br />'; 
         //  echo "<h2>стр. ".__LINE__." <a style='color:blue'>Запуск метода RUN()".(microtime(true) - INDEX_START)."</a></h2>";
            
            if(!isset($_SESSION['lidlevel'])){
               $url = $this->user_type();
               $_SESSION['lidlevel'] = $this->user_type();
            }else{
               
                if(isset($_GET['url'])){
                    $url = $_GET['url'];
                    
                    // P - признак дополнительных параметров входящих в 
                    if(isset($_GET['p'])){
                        $param = $_GET['p'];
                    }
                    
                }
            }
            
            if(in_array($url, $this->routes[1])){
               // var_dump($url);
                 $key = array_search($url, $this->routes[1]);
                 //var_dump($key);
                 $actionname = ucfirst($this->routes[1][$key]).'Action';
                 
                 
                 //Параметры методов 
                 //{проверка на присутствие в URL дополнительных параметров}
                 
                 if(!empty($param)){
                     if(array_key_exists($param, $this->routes[2])){
                         $key_param[$param] = $this->routes[2][$param];
                         $param_array = $_GET;
                         
                        // Проверка на то, что доп параметр имеет в свою 
                        // очередь еще доп. параметр
                        if(is_array($this->routes[2][$param])){
                         
                            // Ищем совпадение в массиве доп. параметров 
                            // с url
                            foreach($param_array as $key_url => $val_url){
                                if(array_search($val_url, $key_param[$param])){
                                    $param1[$param] = $val_url;
                                }
                            }  
                        }
                 
                    }
                     
                 }
                 
                 
             }else{
              
                 if(isset($_SESSION['lidlevel'])){
                    $actionname = ucfirst($_SESSION['lidlevel']).'Action'; 
                 }
                 
             }
          
          
        }
       
        //var_dump($actionname);
        
       if(preg_match('~leader|head|Scince~', $actionname)){
           $actionname = 'UserAction';
        }
        
        
        
        include_once  ROOT_MENUU.'/controllers/'.$classname.'.php';
    //    echo '<br />Time RUN CLASS = '.$classname.'= EXIST  '.(microtime(true) - INDEX_START);
        $var = new $classname;
        
        if(method_exists($var,$actionname)){
           // echo 'все хорошо'; 
         //   echo '<br />Time RUN METHOD'.$actionname.' EXIST  '.(microtime(true) - INDEX_START);
            if(!empty($param1)){
                $var->$actionname($param1); 
            }else{
                $var->$actionname();
            }   
            
        }else{
           
            $this->error = 'Ошибка доступа к методу - '.$actionname.' из класса '.$classname.$classname1;
            include ROOT_MENUU.'/views/Error.html';
           // echo "<h2><a style='color:red'>Error for EVERYBODY!!!</a></h2>";
        }
        
        
    }
}
