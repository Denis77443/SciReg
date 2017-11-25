<!--<script type="text/javascript" src="../js/SelectSubject.js"></script>-->
<form action='index.php?url=redirect' method = 'post' >
    <select style='width: 300px;' id="sel_1_sub" name="parent">
 <!--  <select style='width: 300px;' onchange="SelectSubject(event, flag=<?=$flag_subj; ?>, user_id = <?=$slave_id;?>)" name='parent'>-->
        <option>Выберите тему ...</option>
        <?php foreach($sub_array as $key_arr){?>
        <option value='<?=$key_arr['id']?>'><?=$key_arr['title']?></option>
        <?php } ?>
    </select> 
    <div id='subtheme<?=$flag_subj?>'></div>
    <input type='hidden' name='user id' value='<?=$slave_id;?>'>
    <input type="submit" id="save0<?=$flag_subj;?>" value="Сохранить" style="display: none">
</form>

 