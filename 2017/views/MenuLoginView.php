<?php if(!defined('ROOT_MENUU')) die('access denied');?>
<?php include ROOT_MENUU.'/controllers/MenuController.php'; ?>
<?php include MenuController::GetUrl4Static()."/Views/header.php"; ?>

<ul id="menu">
    <li>
        <a href="<?=$url_index?>index.php">Home</a>
    </li>
    <li>
        <a href='index.php?url=registration'>Регистрация</a>
    </li>
    <li style="float:right;">
        <a href="index.php?url=login">Login</a>
    </li>
    <li style="float:right;">
      <a href="index.php?url=help" 
         style="padding: 3px 3px 3px"><img src='/Images/help30.png'></a>
  </li>
</ul>
  

