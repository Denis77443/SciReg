<fieldset>
    <legend>
        <a class="legenda">
            <label for='plan'><?=$this->titleNIR?></label>
        </a>
    </legend>
    <?php $p1='plan';?>
    
    <?php if($this->titleNIR == 'План НИР'){ 
        include ROOT_MENUU.'/views/PreHtmlView.php';
    } ?>
   
    <textarea <?=$this->disabled;?> id="<?=$this->user_id()?>" class="textarea_class" 
              name = "plan" ><?=$this->ShowPlanNIR();?></textarea>
</fieldset>    
