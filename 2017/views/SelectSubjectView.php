<select style='width: 300px;' name='subj' id='id_sele'>    
<!--<select style='width: 300px;' onchange="Save_buttn(event, flag = <?=$flag;?>)" name='subj' id='id_sele'>-->
        <option>Выберите пункт ...</option>
        <?php foreach($sub_array as $key_arr){?>
        <option value='<?=$key_arr['id']?>' title='<?=$key_arr['id']?>'><?=$key_arr['title']?></option>
        <?php } ?>
</select> 
<!--<script type="text/javascript" src="../js/Save_buttn.js"></script>-->
    
