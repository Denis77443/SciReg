<?php //echo '<br />TIME - BEFORE STATUS '.(microtime(true) - INDEX_START); ?>
<div class="content_main" style="min-height: 600px;">
    <?php include_once ROOT_MENUU.'/views/HeaderStatusView.php';?>
<fieldset>
    <legend>
        <!--Название отделения или отделений-->
        <?php foreach($this->depar as $key => $value){?>
                <div class="divis_name"><?=$value?></div> 
        
        <?php } ?>
    </legend>
    
    
        
        <div style='padding-left: 0%; padding-right: 0%; float:left; width: 50%' class="sc_stat" id="sc_stat">
        <fieldset>
            <legend>Научные работники [<?=$countScince?>]</legend>
            <div style='float:left;' class='fio_stat_tmpl'>Ф.И.О.</div>
            <div class='fio_stat_row' id='Т'>Статус</div>
                <?=$name;?>
        </fieldset> 
             
        </div>
    
       
    
        <div style="border:0px solid red; width:50%;float:left;">
        <fieldset>
            <legend>Наука 2 [<?=$countScince2?>]</legend>
            <div style='float:left;' class='fio_stat_tmpl'>Ф.И.О.</div>
            <div class='fio_stat_row' id='Т'>Статус</div>
                <?=$nameSc2;?>
        </fieldset>
        </div>
    
</fieldset>
</div> 
