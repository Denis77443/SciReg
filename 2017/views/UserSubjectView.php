<div id="view_sbj">
<fieldset>
    <legend><a style="font-weight: bold;">Темы:</a></legend>
   <?php if($this->IssetSubjectOrNot() == FALSE){?>
        <div id='NoSubject'><a style='color:red;'><?=$this->message;?></a></div>
        
        <?=$this->subj_output1;?>
        <div id='result_sub01'></div>
        <?=$this->subj_output2;?>
        <div id='result_sub02'></div>
        <?=$this->subj_output3;?>
        <div id='result_sub03'></div>
        
   <?php }else{ ?>
      
        <?php $this->ShowSubjectAndHead();?>
        
        <?=$this->subj_output1;?>
        <div id='result_sub01'></div>
        <?=$this->subj_output2;?>
        <div id='result_sub02'></div>
        <?=$this->subj_output3;?>
        <div id='result_sub03'></div>
        
   <?php } ?>    
      
        
        
   <!-- 
    <div id="sub1">Тема 1</div>
    <div id="sub2">Тема 2</div>
    <div id="sub3">Тема 3</div>-->
    
</fieldset> 
</div>    
<?php echo 'Время выполнения скрипта: '.(microtime(true) - INDEX_START).' сек.';  ?>