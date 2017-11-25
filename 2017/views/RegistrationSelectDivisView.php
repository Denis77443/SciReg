<!--Подразделение-->
<div  class="regist" id="department">
    <div class="lbl_reg">
        <label for="lid" class="lab_profile">
            <span style="color:red;">*</span>Отдел/Лаб.
        </label>
    </div> 
    <div style="float:left;">
        <div class="regist">
        <select name="lid" id="lid" class="regist" required='required'>
              <option selected disabled value=''>Выберите подразделение...</option>
                <?php foreach($divis as $key_divis){?>
                    <option value="<?=$key_divis['uid'];?>"><?=$key_divis['title'];?></option>
                 <?php } ?>
        </select>
        </div>    
     </div>   
</div>