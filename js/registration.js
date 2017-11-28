   
['surname', 'name', 'mname'].forEach(function(ev){
    var elem = document.getElementById(ev);
        elem.addEventListener('input', getName);
});
    
document.getElementById('id_post').addEventListener('change', ChangePost);
document.getElementById('divis').addEventListener('change', SelectDivision);
document.getElementById('uname').addEventListener('input', CheckLogin);
document.getElementById('password').addEventListener('input', validPassSc2);
document.getElementById('email').addEventListener('blur', notEmpty);
  
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
    var dataToInsert = {}, 
        allInpt = document.querySelectorAll('input[required], select[required]'), error = 0,
        allErr = document.querySelectorAll('.error_name'),  
        message = document.getElementById('printAreaReg'),
        allInptToInsert = document.querySelectorAll("input:not([type='submit']), select:not([name='divis'])");
      
        for (var key in allInpt) { 
        var labl = allInpt[key].labels,
            elem = document.getElementById(allInpt[key].id);
    
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
            message.innerHTML = 'Необходимо заполнить форму регистрации';
        } else {
          
          for (var key4 in allInptToInsert) {
              if (allInptToInsert[key4].id !== undefined && 
                  allInptToInsert[key4].value !== '') {
                  dataToInsert[allInptToInsert[key4].id] = allInptToInsert[key4].value;
              }
          }
          
          
          var xhr = new XMLHttpRequest();
          var body = e.target.action;
          var data = JSON.stringify(dataToInsert);
          
          beforeSend();
          xhr.open("POST", body, false);
          xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          
          xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {
                
                if (xhr.responseText.search('Ошибка!') === -1) {
                    e.preventDefault();
                    document.getElementById('frm_rg').reset();
                    message.style.color = "green";
                    message.innerHTML = xhr.responseText;
                } else {
                     document.getElementById('uname').value = "";
                    message.innerHTML = xhr.responseText;
                }
                
            }  
          };
          xhr.send(data);
        }
       e.preventDefault();
    }
    
   /*
    * Loader gif
    * Ожидание ответа от сервера
    */ 
   function beforeSend(){
       document.getElementById('printAreaReg').innerHTML = '<img src="/Images/loader.gif" id="loading" style="height:40px; width: 40px;" />';
   }
    //Проверка на содержимое INPUT email, 
    //Если не пусто, то валидация
    
    function notEmpty(){
      var string =  document.getElementById("email").value,
          email = string.match(/^[0-9a-zA-Z_-]{1,}[@][0-9a-zA-Z_-]{1,}[\.][0-9a-zA-Z_-]{1,}/),
          elem = document.getElementById("em");
     
      if ( (string.length !== 0)&&(string.replace(/\s/g,'').length !== 0) ) {
         elem.textContent = ( email === null ) ? 'e-mail введен с ошибкой!' : '';
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

      var fieldName = document.querySelector("label[for='"+event.target.name+"']").textContent;
 
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
    
    
  function CheckLogin() {
    var post = document.querySelector("select[name='id_post']").value,  
        uname_letter = document.getElementById('uname').value,
        uname = uname_letter.match(/^[a-z_][a-z0-9_-]{0,31}/);
  
        if ( (uname !== null)&&(uname_letter == uname) ) {
            document.getElementById("uname").style.background = '#66ff66'; 
            document.getElementById("log_pass").innerHTML = '';
        } else {
            document.getElementById("uname").style.background = '#ff6666'; 
            document.getElementById("log_pass").innerHTML = 'Формат: \n\
                                                             лат. буква или "_", затем лат. буквы, \n\
                                                             цифры,"_","-",макс. 32 символа';
        }
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
function validPassSc2(){
    var post = document.querySelector("select[name='id_post']").value, 
        passLetter = document.getElementById('password').value,
        pass = passLetter.match(/(?=.*\d)(?=.*[a-zA-Z]).{8,}/);

    var passStyle = document.getElementById("password"),
        errorStyle = document.getElementById("error_pass");        
            
        if ( (post > 19)&&(post < 37) ) {
            if (pass !== null) {
                passStyle.style.background = '#66ff66'; 
                errorStyle.innerHTML = '';
            } else {
                passStyle.style.background = '#ff6666'; 
                errorStyle.innerHTML = 'Формат: \n\
                                        8 символов, буквы лат. алфавита, спецсимволы, \n\
                                        минимум одна цифра!';
            }
        } else {
          passStyle.style.background = 'white';
          errorStyle.innerHTML = '';
        }   
} 
        
 
