<div class="content_main">
    <form id="frm_rg" action='index.php?url=insertUser'>
    <fieldset>
        <legend>Регистрация</legend>
        
       <!--Вывод состояния---> 
       <div name="printAreaReg" id="printAreaReg" class="printArea"></div>    
       <div id="reg_outer" style="border:0px solid window; padding-top: 91px;">  
        
        
       <!--Фамилия-->
       <div class="regist">
           <div class="lbl_reg">
               <label for="surname" class="lab_profile">
                   <span class="star_reg">*</span>Фамилия
               </label>
           </div>  
           <div style="float: left;">
               <input  id="surname" name="surname" class="regist" pattern="^[А-ЯЁ][а-яё]+$" required="true" >
           </div>
            <div class="error_name" id="error_surname"></div>
       </div>
         
      <!--Имя-->
      <div class="regist">
           <div class="lbl_reg">
              <label for="name" class="lab_profile">
                     <span class="star_reg">*</span>Имя
              </label>
           </div>    
           <div style="float:left;">
                <input  id="name" name = "name" class="regist" pattern="^[А-ЯЁ][а-яё]+$" required>
           </div>
           <div class="error_name" id="error_name"></div>
       </div>
            
       <!--Отчество-->
       <div class="regist">
            <div class="lbl_reg">
                <label for="mname" class="lab_profile">
                     <span class="star_reg">*</span>Отчество
                </label>
            </div>
            <div style="float:left;">
                <input  id="mname" name="mname" class="regist" pattern="^[А-ЯЁ][а-яё]+$" required>
            </div>
            <div class="error_name" id="error_mname"></div>
       </div>
            
        <!--Должность-->
        <div class="regist">
            <div class="lbl_reg">
                <label for="id_post" class="lab_profile">
                    <span class="star_reg">*</span>Должность
                </label>
            </div>
            <div style="float:left;">
                <div class="regist"> 
                    <select class="regist" name="id_post" id="id_post" required='true'>
                        <option selected disabled value=''>Выберите должность...</option>
                    
                        <!--Руководство-->
                        <option value="100">Руководитель</option>
                        <option disabled>------------------------</option>
                        <?php foreach($post as $key_post){ 
                               if(($key_post['id_post'] < 6)||
                               ($key_post['id_post'] > 15)){ 
                                   if($key_post['id_post'] == '20'){ ?>
                        <option disabled>--------Наука 2------------</option>
                            <?php } ?>
           <option value="<?=$key_post['id_post'];?>"><?=$key_post['post_title'];?></option>
               <?php } }?>
                    </select>
                 </div>  
             </div>
        </div>
            
        <!--Отделение-->
        <div class="regist" id="division">
            <div class="lbl_reg">
                <label for="divis" class="lab_profile">
                    <span class="star_reg">*</span>Отделение
                </label>
            </div>  
            <div style="float:left;">  
                <div class="regist">    
                    <select name="divis" id="divis" class="regist" required='required'>
                        <option selected disabled value=''>Выберите отделение...</option>
                        <?php foreach($depar as $key_depar){?>
                        <option value="<?=$key_depar['uid'];?>"><?=$key_depar['title'];?></option>
                        <?php } ?>
                    </select>
                 </div> 
            </div>   
         </div>
            
         <!--Подразделение-->
         <div id="unit"></div>
            
         <!--Телефон раб.-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="phone" class="lab_profile">Телефон (раб.)</label>
             </div>    
             <div style="float:left;">   
                 <div style="float:left;">
                     <input class="regist" id="phone" name="phone" type="text"  placeholder="(495)851__-__" >    
                 </div>
             </div>
         </div>
            
         <!--Телефон моб.-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="mobile" class="lab_profile">Телефон (моб.)</label>
             </div>
             <div style="float:left;">
                 <input type="text" id="mobile" name="mobile"  class="regist" placeholder="(___)___-____">
             </div>   
         </div>
            
         <!--Логин-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="uname" class="lab_profile">
                     <span class="star_reg">*</span>Логин
                 </label>
             </div>    
             <div style="float:left;">
                 <input  id="uname" name="uname" class="regist"  pattern="^[a-z_][a-z0-9_-]{0,31}" required>
             </div>
             <div class="error_name" id="uname_check"></div>
             <div class="error_name" id="log_pass"></div>
         </div>
         
         <!--Пароль-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="password" class="lab_profile">
                     <span class="star_reg">*</span>Пароль
                 </label>
             </div>
             <div style="float:left;">
                 <input  id="password" name="password" class="regist" type="password" required>
             </div> 
             <div class="error_name" id="error_pass"></div>
         </div>
             
         <!--Email-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="email" class="lab_profile">E-mail</label>
             </div>  
             <div style="float:left;">
                 <input  id="email" name='email' class="regist" >
             </div> 
             <div class="error_name" id="em" style="padding-top: 7px;"></div>
         </div>
             
        <div class="sbm_reg">
            <input type="submit" id="reg_sbmt" name="submit" value="Сохранить" class="regist" style="width: 100px; margin-right: 20%">
        </div>
       
        </div> 
 
     </fieldset> 
     </form>   
</div>
<script type='text/javascript' src='../js/SelectDivision.js'></script>
<script type='text/javascript' src='../js/ChangePost.js'></script>
<?php include_once MenuController::GetUrl4Static()."/Views/footer.php"; ?>
<script>
   

    
    ['surname', 'name', 'mname'].forEach(function(ev){
        var elem = document.getElementById(ev);
        elem.addEventListener('input', getName);
    });
    
    document.getElementById('id_post').addEventListener('change', ChangePost);
    document.getElementById('divis').addEventListener('change', SelectDivision);
    document.getElementById('uname').addEventListener('input', CheckLogin);
    document.getElementById('password').addEventListener('input', Show_Valid);
    document.getElementById("email").addEventListener('blur', notEmpty);
    
      //document.getElementById('surname').addEventListener('input', getName);
    
    
    var formReg = document.getElementById('frm_rg');
   
    formReg.onsubmit = checkBeforeSubmit.bind(formReg);
    
    var buttonSubm = 0; //Признак нажатия submit
    
    
    /*
     * 
     * @param {type} e
     * @returns {undefined}
     */
    function checkBeforeSubmit(e){
      buttonSubm  = 1;
      var dataToInsert = {}; 
       
    
       
      var allInpt = document.querySelectorAll('input[required], select[required]'), error = 0;
      var allErr = document.querySelectorAll('.error_name');  
      var allInptToInsert = document.querySelectorAll("input:not([type='submit']), select:not([name='divis'])");
      
      for (var key in allInpt) { 
          var labl = allInpt[key].labels;
          var elem = document.getElementById(allInpt[key].id);
          if (labl !== undefined && labl.length !== 0 && labl[0].innerText.charAt(0) === '*') {
              if ( allInpt[key].value !== null && allInpt[key].value !== '') {
                  elem.removeAttribute("error");
                  elem.removeAttribute("style");
                 
                  if (elem.tagName === 'SELECT') {
                      elem.removeAttribute("error");
                      elem.parentNode.removeAttribute("style");
                  }
              } else {
                  elem.setAttribute("error","1");
                  error = 1;
              }
           }  
      }
 
      for (var key2 in allErr) {
          if (allErr[key2] !== undefined) {
              if (allErr[key2].innerText !== '' && allErr[key2].innerText !== null && 
                  allErr[key2].innerText !== undefined ) {
                  error = 1;
                  break;
              }
          }
      }
      
     for (var key3 in allInpt) {
         var elem3 = document.getElementById(allInpt[key3].id);
         
         if (allInpt[key3].nodeType === 1 && allInpt[key3].hasAttribute('error') === true) {
             if (allInpt[key3].tagName === 'SELECT') {
                 elem3.parentNode.setAttribute("style", "box-shadow:0 0 10px red");    
             }else{
               elem3.setAttribute("style", "box-shadow:0 0 10px red");   
           }
         }
     }
         
        if(error === 1){
            document.getElementById('printAreaReg').innerHTML = 'Необходимо заполнить форму регистрации';
           // console.log('Необходимо заполнить форму регистрации');
        }else{
          //  document.getElementById('printAreaReg').innerHTML = 'Форма готова к отправке'+e.target.action;
          //  console.log('Форма готова к отправке');
          
          for (var key4 in allInptToInsert) {
              if (allInptToInsert[key4].id !== undefined && 
                  allInptToInsert[key4].value !== '') {
                  dataToInsert[allInptToInsert[key4].id] = allInptToInsert[key4].value;
              }
          }
          
          
          var xhr = new XMLHttpRequest();
          var body = e.target.action;
          var data = JSON.stringify(dataToInsert);
          
          xhr.open("POST", body, false);
          xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          
          xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {
                
               // console.log(xhr.responseText.search('уже есть'));
                 
                if (xhr.responseText.search('Ошибка!') === -1) {
                    e.preventDefault();
                    document.getElementById('frm_rg').reset();
                    document.getElementById('printAreaReg').style.color = "green";
                    document.getElementById('printAreaReg').innerHTML = xhr.responseText;
                } else {
                     document.getElementById('uname').value = "";
                    document.getElementById('printAreaReg').innerHTML = xhr.responseText;
                }
               // console.log(xhr.responseText);
            }  
          };
          xhr.send(data);
        }
       
       e.preventDefault();
    }
    
   
    
    
    
    //Проверка на содержимое INPUT email, 
    //Если не пусто, то валидация
    
    function notEmpty(){
      var string =  document.getElementById("email").value;
      var email = string.match(/^[0-9a-zA-Z_-]{1,}[@][0-9a-zA-Z_-]{1,}[\.][0-9a-zA-Z_-]{1,}/);
      var elem = document.getElementById("em");
     
      if ( (string.length !== 0)&&(string.replace(/\s/g,'').length !== 0) ) {
        if (email === null ) {
            elem.textContent = 'e-mail введен с ошибкой!';
        } else {
            elem.textContent = ''; 
        }
      } else {
          elem.textContent = '';  
      }
    }
    
 
    /*
     * Валидация полей {Фамилия, Имя, Отчество}
     * 
     * @property {string} actualValue - Текущее значение поля input. 
     * @property {string} err - div в котором находятся имя/имена невалидных полей.
     * @property {string} fieldName - Имя невалидного поля/полей. 
     * @property {string} fieldNameAfter - Имя поля которое стало валидным.
     * @property {string} regExp - Обязательный формат заполнения ФИО : 
     *                             только кириллица и заглавная буква.
     * @property {string} errorName - Внешний div в котором находится сообщение 
     *                             об ошибке. Также данный div включает в себя 
     *                             err                              
     */
    function getName(event){
        
      var actualValue = event.target.value; 
      var err = document.getElementById("fieldErr"); 
      
     // console.log(event.target.name);
     // var fieldName = document.querySelector("label[for='"+event.target.name+"']").innerText;
      var fieldName = document.querySelector("label[for='"+event.target.name+"']").textContent;
     
      
     // console.log("fff = "+fff);
      
     // var fieldName = $("label[for='"+event.target.name+"']").text(); 
      var fieldNameAfter = fieldName;
      var regExp = actualValue.match(/^[А-ЯЁ][а-яё]*$/); 
      var errorName = document.getElementById("error_"+event.target.name); 
      
       if ( (err !== null)&&(err.innerHTML !== fieldName) ) { 
          if (err.innerHTML.indexOf(fieldName) === -1){
               fieldName = err.innerHTML +""+ fieldName;
          } else {
            fieldName = err.innerHTML;
          }
       }
       
        /*
         * Если текущее значение INPUT не соответствует формату, 
         * то вывод ошибки в div error_name.
         * 
         * В том случае, если значение INPUT соответствует формату, то
         * из переменной field_name удаляется имя поля с 
         * "ВАЛИДНЫМ ВВОДОМ"(если у этого поля уже был "НЕВАЛИДНЫЙ ВВОД")
         */     
        if (regExp === null) {
          errorName.style.display = "block";  
          errorName.innerHTML = "Формат: по-русски и с заглавной буквы";                   
        } else {
           
          /* Удаляем упоминание об ВАЛИДНОМ ПОЛЕ из field_name, если раньше 
           * это поле было НЕВАЛИДНЫМ */
          fieldName = fieldName.replace(fieldNameAfter,'');
          
          
          /* Если field_name - пусто, ошибок нет, очистка div */
          if ( (fieldName === ' ')||(fieldName === '')||(fieldName.length === 0) ){
                      // errorName.innerHTML = "";
                      errorName.style.display = "none";
                      errorName.innerText = '';
                      
                    //  console.log(event.target.id);
                      if(document.getElementById(event.target.id).hasAttribute("error")){
                          document.getElementById(event.target.id).removeAttribute("error");
                          document.getElementById(event.target.id).removeAttribute("style");
                      }
          } else {
              
            /*Вывод оставшихся ошибок в div*/  
            err.innerHTML = fieldName;
          }
        }
    }
    
    
  function CheckLogin(event) {
    var post = document.querySelector("select[name='id_post']").value;  
    var uname_letter = document.getElementById('uname').value;
    var uname = uname_letter.match(/^[a-z_][a-z0-9_-]{0,31}/);
        
               //------------------------
      //  if((post > 19)&&(post < 37)){
               if((uname !== null)&&(uname_letter == uname)){
                   document.getElementById("uname").style.background = '#66ff66'; 
                   document.getElementById("log_pass").innerHTML = '';
                }else{
                    document.getElementById("uname").style.background = '#ff6666'; 
                    document.getElementById("log_pass").innerHTML = 'Формат: [a-z_][a-z0-9_-]{0,31}* : \n\
                                                                     лат. буква или "_" , затем лат. буквы, \n\
                                                                     цифры,"_","-",макс. 32 символа';
                }
       //     }
        
        
        //console.log("POST" + post);
        //------------------------
    /*    document.getElementById("uname_check").style.position = "absolute";
        document.getElementById("uname_check").style.left = "71%";
        document.getElementById("uname_check").style.marginTop = (event.target.offsetTop - 25)+'px';*/
       // document.getElementById("uname_check").style.marginTop = (event.target.offsetTop - 25)+'px';
        
       
       // console.log('EV.TARGET.TOP = '+event.target.offsetTop);
       // console.log('LOGIN_CH.TOP = '+document.getElementById("uname_check").offsetTop);
        
        
        
        
       /*
        $.ajax({
            type: "POST",
            url: "index.php?url=checkuname",
            data:{
                action: 'CheckLogin',
                uname: uname
            },
            success: function(data){
                console.log(data);
                var inputLogin = document.getElementById('uname');
                
                if(data.search('БД') !== -1){
                   inputLogin.setCustomValidity('В БД есть такой логин!!!'); 
                   $('div[id="uname_check"]').html(data);
                } else {
                    
                       document.getElementById('uname_check').innerHTML = '';
                       inputLogin.setCustomValidity('');
                 
                }
                
            }
        });*/
    } 
    
    function Login_Valid(){
            var post = $('select[name="id_post"]').val();
            var uname_letter = document.getElementById('uname').value;
            var uname = uname_letter.match(/^[a-z][a-z0-9_]{0,31}/);
          
           // document.getElementById("uname_check").innerHTML = '';
          
            
            if((post > 19)&&(post < 37)){
               if((uname !== null)&&(uname_letter == uname)){
                   document.getElementById("uname").style.background = '#66ff66'; 
                   document.getElementById("log_pass").innerHTML = '';
                }else{
                    document.getElementById("uname").style.background = '#ff6666'; 
                    document.getElementById("log_pass").innerHTML = 'Формат: начинаться с буквы, \n\
                                                                     может содержать только строчные буквы латинского алфавита, \n\
                                                                     числа, знак _, \n\
                                                                     максимум 32 символов!';
                }
            }else{
                document.getElementById("uname").style.background = 'white';
                document.getElementById("log_pass").innerHTML = '';
            }
          
          }
          
       /*
        * Валидация пароля, только для пользователей Наука 2 
        */   
       function Show_Valid(){
           var post = document.querySelector("select[name='id_post']").value; 
          //  var post = $('select[name="post"]').val();
            var pass_letter = document.getElementById('password').value;
          
            var pass = pass_letter.match(/(?=.*\d)(?=.*[a-zA-Z]).{8,}/);
            
            if((post > 19)&&(post < 37)){
               if(pass !== null){
                   document.getElementById("password").style.background = '#66ff66'; 
                   document.getElementById("error_pass").innerHTML = '';
                }else{
                    document.getElementById("password").style.background = '#ff6666'; 
                    document.getElementById("error_pass").innerHTML = 'Формат: \n\
                                                                       8 символов, буквы лат. алфавита, спецсимволы, \n\
                                                                       минимум одна цифра!';
                }
            }else{
                document.getElementById("password").style.background = 'white';
                document.getElementById("error_pass").innerHTML = '';
            }
          
            
        } 
        
 
</script>

