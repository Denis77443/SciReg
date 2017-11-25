<div class="content_main" style="min-height: 600px;">

    <fieldset>
        <legend><?=$this->depar[$id_deprt]?></legend>
        
        <!--ФИО Руководителя ОТДЕЛЕНИЯ-->
        <a href="index.php?url=userpage&id=<?=$this->head_depar[0]['hid']?>" style="font-weight: bold;">
            <?=$this->head_depar[0]['name']?>
        </a><br>
        
        <?php foreach($this->office as $key_labs => $value){?>
           <?php if($value == $id_deprt){?>
        
            <a href="<?php 
                        if(($this->isset_lab[$key_labs]!=NULL)||
                           ($this->isset_sec[$key_labs]!=NULL)){
                            
                            if(isset($this->isset_lab[$key_labs])){
                                echo 'index.php?url=laboratory&lid='.$this->isset_lab[$key_labs];}
                            
                            if(isset($this->isset_sec[$key_labs])){
                                echo 'index.php?url=laboratory&lid='.$this->isset_sec[$key_labs];}    
                                
                        }else{
                            echo 'index.php?url=usersindivis&lid='.$this->id_otdel[$key_labs];        
                    }?>"><?=$key_labs?>
            </a><br />
        
            
        <?php }} ?>
    </fieldset>
</div>    
