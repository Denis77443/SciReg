
   
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
<script>
    /*
    function Auto_Save_Plan(event, user_id){
    
        var name = $(event.target);
        var field_name = name.attr('name');
        var field_value = name.val();
       
            $.ajax({
                type: "POST",
                url:  "index.php?url=ajax&param=save_plan",
                data:{
                    action: 'Auto_Save',
                    user_id: user_id,
                    field_name: field_name,
                    field_value: field_value
                },
                cache:false,
                success: function(responce){ $('div[id="plan_plan"]').html(responce);
                    
                }
                
            });
        
        }*/
  //  $("textarea[name='expected_result']").height( $("textarea[name='expected_result']")[0].scrollHeight );
</script>  

<?php echo 'Время выполнения скрипта: '.(microtime(true) - INDEX_START).' сек.';  ?>
