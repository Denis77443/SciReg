function ChangePost(){            
    var elem = document.querySelector("select[name='id_post']");
    var post = document.querySelector("select[name='id_post']").value;       
    var select_lab =  document.getElementById('select_lab');
 
    //Для браузеров не поддерживающих атрибут required 
    //убираем свечение вокруг select
    if (elem.parentNode.hasAttribute('style') === true) {
       elem.parentNode.removeAttribute('style');
    }
            
            
            //console.log('SELECT_DEP --  '+select_dep);
            //console.log('SELECT_LAB --  '+select_lab);
            
            
            document.getElementById("division").style.display = 'block';
            document.getElementById("divis").required = true;
            
            
            if((post > 19)&&(post<37)){
                console.log('Наука 2');
                document.getElementById("password").pattern = '(?=.*[0-9]{1,})(?=.*[a-z]).{8,}';
                document.getElementById("password").title = 'Пароль должен быть минимум из 8 символов.\n\
Содержать - буквы латинского алфавита, спецсимволы,\n\
минимум одну цифру!'; 
                  
                 document.getElementById("uname").pattern = '^[a-z][a-z0-9_]{0,31}';
                 document.getElementById("uname").title = "Логин должен начинаться с буквы,\nможет содержать только строчные буквы латинского алфавита, числа, знак _, максимум 32 символов!"; 
                
                
                
            }else{
                console.log('Наука просто');
                               
                              
                
                document.getElementById("log_pass").innerHTML = '';
                
                document.getElementById("password").removeAttribute("pattern");
                document.getElementById("password").removeAttribute("title");
              
                document.getElementById("uname").removeAttribute("pattern");
                document.getElementById("uname").removeAttribute("title");
             
                
                if(post === '100'){
                    
                    // У руководителя - НЕТ выбора ОТДЕЛЕНИЯ и ПОДРАЗДЕЛЕНИЯ
                    document.getElementById("divis").required = false;
                    document.getElementById("division").style.display = 'none';
                    
                    
                    //Если выбрана лаборатория, то убираем
                    if (typeof(select_lab) !== 'undefined' && select_lab !== null)
                        { 
                        // exists.
                        document.getElementById("select_lab").required = false;
                        document.getElementById("department").style.display = 'none';
                        
                      }
                }
                
                         
                
            }
            /*
            document.getElementById("pass").value = '';
            document.getElementById("uname").value = '';
            //document.getElementById("pass").pattern = '';
            
            document.getElementById("pass").style.background = 'white';
            document.getElementById("uname").style.background = 'white';
            
           // console.log(post);
            if(post === '100'){
                document.getElementById("log_pass").innerHTML = '';
                document.getElementById("pass").removeAttribute("pattern");
                document.getElementById("pass").removeAttribute("title");
                
                document.getElementById("uname").removeAttribute("pattern");
                document.getElementById("uname").removeAttribute("title");
             //   document.getElementById("pass").removeAttribute("oninput");
                
                document.getElementById("q").required = false;
                document.getElementById("divis_dep_lab").style.display = 'none';
                
                
                if(select_lab != null){ 
                    //alert('dddd'); 
                    document.getElementById("q1").required = false;
                    document.getElementById("select_lab").style.display = 'none';
                }
                
            }else{
                document.getElementById("divis_dep_lab").style.display = 'block';
                if((post > 19)&&(post < 37)){
                   
                    document.getElementById("pass").pattern = '(?=.*[0-9]{1,})(?=.*[a-z]).{8,}';
                    document.getElementById("pass").title = 'Пароль должен быть минимум из 8 символов.\n\
Содержать - буквы латинского алфавита, спецсимволы,\n\
минимум одну цифру!'; 
                  
                    document.getElementById("uname").pattern = '^[a-z][a-z0-9_]{0,31}';
                    document.getElementById("uname").title = "Логин должен начинаться с буквы,\nможет содержать только строчные буквы латинского алфавита, числа, знак _, максимум 32 символов!"; 
                  
                }else{
                    document.getElementById("pass").removeAttribute("pattern");
                    document.getElementById("pass").removeAttribute("title");
                    
                    document.getElementById("uname").removeAttribute("pattern");
                    document.getElementById("uname").removeAttribute("title");
                 //   document.getElementById("pass").removeAttribute("oninput");
                 document.getElementById("log_pass").innerHTML = '';
                }
            }*/
        }
        
function OnSubmitNewUser(){
    var post = document.querySelector("select[name='id_post']").value;
    var select_lab = document.querySelector("select[name='lid']").value; 
            //var post = $('select[name="post"]').val();
            //var select_lab = $('select[name="select_lab"]').val();
            
            //console.log('value()'+select_lab);
          if(post !== '100'){ 
           if(select_lab === undefined){
               document.getElementById('q').onchange();
               document.getElementById("depart_msg").innerHTML = 'Не выбрано подразделение!!!';
               //alert('Лаборатория не выбрана');
               return false;
           }
       }else{
          document.getElementById("depart_msg").innerHTML = ''; 
       }
        }        
        