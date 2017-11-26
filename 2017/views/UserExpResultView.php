<fieldset>
    <legend>
        <a style='font-weight:bold;'>
            <label for='expected_result'>Ожидаемый результат</label>
        </a>
    </legend>
    <textarea <?=$this->disabled;?>
              data-autoresize 
              style='width:990px;' 
              id="<?=$this->user_id()?>" 
              class="textarea_class" 
              name = "expected_result"><?=$this->ShowExpResults();?></textarea>
</fieldset>

