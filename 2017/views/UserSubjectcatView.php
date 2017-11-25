<div class="content_main" style="min-height: 600px;">
<fieldset>
    <legend>Темы</legend>
    <?php $subjectPath = include(ROOT_MENUU.'/config/JSON_progs.php');
            foreach ($subjectPath as $key_sub => $value_sub){
    ?>
    <!--<div id="subj_cat_div">-->
    <div class="ll">
        <a class="sbj1" style="cursor:pointer" href="javascript:void(0)" id="<?=$key_sub?>"><?=$value_sub?></a><br>
   <!-- </div>-->
  </div>
    <?php } ?>
        
</fieldset>
    <div id="subjList"></div>
</div>

<script>
  
    
    var jj = document.querySelectorAll('.ll');
    
    [].forEach.call(jj,function(e){
        e.addEventListener('click',showListOfSubjects,false);
    });
    
    
    
   // document.getElementById('subj_cat_ul').addEventListener('click', getSubjTitle1);
  
</script>    
<!--<script src="../views/SubjetcListAjax.js"></script>-->


