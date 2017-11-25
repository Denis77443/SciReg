<?php

//$index_start = microtime(true);
//define('INDEX_START' , microtime(true), true);
//echo '<h1>INDEX WORK : '.(microtime(true) - INDEX_START).'</h1>';
error_reporting(E_ALL ^ E_NOTICE); 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


define('ROOT_MENUU', dirname(__FILE__), true);
//echo ROOT_MENUU;
date_default_timezone_set('Europe/Moscow');

require_once (ROOT_MENUU.'/components/Router.php');
include_once (ROOT_MENUU.'/components/DB.php');


$test = new Router();
$test->run();
