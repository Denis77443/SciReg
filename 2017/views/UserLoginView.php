<?php if(!defined('ROOT_MENUU')) die('access denied');?>
<?php //include_once ROOT_MENUU.'/controllers/MenuController.php'; ?>



  <div class="content_main">  
     
    <form action='index.php?url=auth' method='post'>
        <fieldset>
          <legend>
            <a class="legenda">Вход</a>
          </legend>
           
           <!--Вывод ошибок---> 
          <div name="printArea" id="printArea" class="printArea"></div>    
          <div class='login_div'>
              
              <!--Login-->
                <div class="login_div_label">
                      <label for="surname">Login:</label>
                </div>
                <div class="login_div_input">
                    <input  name='surname' class="user_login" type='text' id="input_user_surname" required>
                </div>
              
                <!--Password-->
                <div class="login_div_label" style="clear: both; ">
                    <label for="name">Password:</label>
                </div>
              
                <div class="login_div_input">
                    <input name='name' class="user_login" id="input_user_name" type='password' required>
                </div>
                
                <div class="login_btn">
                    <input type="submit" id="submit_adm1" value="Вход" >
                </div>
           </div>
            </fieldset>
     </form>
      
</div>   
<script>
    
    document.addEventListener("DOMContentLoaded", function(e){
        e.preventDefault();
        var form = document.querySelector("form");
            form.onsubmit = submitted.bind(form);
    });
    
    
    
    function submitted(e) {
        //e.preventDefault();
        
        var surname = document.getElementById('input_user_surname').value;
        var name = document.getElementById('input_user_name').value;
        
        var xhr = new XMLHttpRequest();
        var body = e.target.action;
        var data = "surname="+surname+"&name="+name;
        
        xhr.open("POST", body, false);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
               console.log(xhr.responseText); 
               
               if(xhr.responseText.search("Ошибка") !== -1){
                  
                   document.getElementById('printArea').innerHTML = xhr.responseText;
               }else{
                   
                  window.location.replace('index.php');
                  return false;
               }
            }
        };
        
        xhr.send(data);
        
    e.preventDefault();
}
 
</script>    

<?php include_once MenuController::GetUrl4Static()."/Views/footer.php"; ?>

