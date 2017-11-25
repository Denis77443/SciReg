    <?php include(ROOT_MENUU.'/views/HeaderStatusView.php'); ?>
    <fieldset>
        <legend>Тема:<?=$title?></legend>
        <div style='float:left; width: 300px; padding-left: 30%;'>
        <br>
        <div style="padding-bottom: 40px; font-weight: bold;">
            <div style='float:left;padding-left: 8%'>Ф.И.О.</div>
            <div style='float:right; padding-right: 13%'>Статус</div>
        </div>
        
    <?php foreach($users_id as $key){?>
    <div class='fio_stat'><a href='index.php?url=userpage&id=<?=$key['uid'];?>' >
            <?=$this->ShowNameAndStat($key['uid']);?></a>
    </div>
    <div>
        <?php foreach($this->ShowStatus1($key['uid']) as $key1 => $value1){ ?>
        <div class='<?=$value1;?>'><?=$key1;?></div>
        <?php } ?>
    </div>
    <?php } ?>
        
     </div>   
   </fieldset>
