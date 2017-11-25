<div class="content_main" style="min-height: 600px;">
<fieldset>
    <legend><?=$this->title_lab['title']?></legend> 
    <!--ФИО Руководителя -->
    <a href="index.php?url=userpage&id=<?=$this->head_labs[0]['hid']?>" style="font-weight: bold;">
        <?=$this->head_labs[0]['name']?>
    </a><br>
    <?php foreach($this->labs as $key_labs => $value){ if($value !=NULL){ ?>
     <a href='index.php?url=usersindivis&lid=<?=$key_labs?>'><?=$value?></a><br />
    <?php }} ?>
    
     <?php foreach($this->sec as $key_sec => $value){ if($value != NULL){?>
     <a href='index.php?url=usersindivis&lid=<?=$key_sec?>'><?=$value?></a><br />
     <?php } }?>
</fieldset>
</div>    


