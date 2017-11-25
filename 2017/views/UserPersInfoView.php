<fieldset>
    <legend style='font-weight: bold;'>Персональные данные</legend>
        <div style='font-weight: bold; font-size: 20px; float:left;'>
            <?=$this->ShowSNMUser().", ".$this->ShowMaxPost()?>
        </div>
        <div style='font-weight: bold; font-size: 20px; float:left; margin-left:30px;margin-right:20px;'>
            Статус:
        
         </div><?=$this->ShowStatus($this->user_id())?><br>   
        <?=$this->ShowDepartment();?>
        <?php if(method_exists(get_called_class(), 'ShowSubjectHead')){ ?>
        <?php if(!empty($this->ShowSubjectHead())){ 
                 foreach($this->ShowSubjectHead() as $value_array){ 
                     foreach ($value_array as $title){ 
        ?>
       <div>
           <p>
           Руководитель темы: <?=$title?> 
           </p>
       </div>
        <?php }}} }?>
</fieldset>


