var dataBeforeEdit = {}; // Первоначальные данные пользователя 
                            // (до редактирования профиля)
     
   document.addEventListener("DOMContentLoaded", getBeforeData);
   document.getElementById('subm').addEventListener('click', checkChangeFields);
   document.getElementById('password').addEventListener('input', function(){
       document.getElementById('second_pass').disabled = false;
   });
     
   function getBeforeData(){
       var profile = document.querySelectorAll("input[class='profile']"),
           before = {};
        
       for (var key in profile) {
           if (profile[key].id !== undefined) {
               before[key] = {name:profile[key].id, value:profile[key].value};
           }    
       } 
       return dataBeforeEdit = before;
    }
      
    function checkChangeFields(bef){     
        var errorActive = document.getElementById("profile_change"),
            profile = document.querySelectorAll("input[class='profile']"),
            after = {};
        
        validateProfile();
        
        bef = dataBeforeEdit;
        
        /*
         * 
         * Сравнение полей...
         * на предмет изменений и запись в after только тех полей, 
         * которые были изменены
         */
        for (var key in profile) {
            if (profile[key].id !== undefined) {
                if( (profile[key].value !== bef[key].value)&&
                    (profile[key].id !== 'second_pass') ){
                    after[profile[key].id] = profile[key].value;
                }     
             }
         }
 
        if ( (Object.keys(after).length !== 0)&&(errorActive.innerHTML === '') ) {
             
            after.user_id = document.getElementById('user_id').value;
             
            var xhr = new XMLHttpRequest();
            var body = "index.php?url=ajax&param=get_profile_param";
            var data = JSON.stringify(after);
             
            xhr.open("POST", body, false);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
             
            xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {        
                for (var key in after) {
                     if( (key !== 'user_id')&&(key !== 'password') ) {
                         var label = document.querySelector('label[for='+key+']').textContent;
                         document.getElementById("profile_change").innerHTML += '<div>Поле: "'+label+'" изменено</div>';
                     } 
                     
                     if (key === 'password') {
                         errorActive.innerHTML += '<div>Пароль изменён</div>';
                         document.getElementById("password").value = '';
                         document.getElementById("second_pass").value = '';
                         document.getElementById("second_pass").disabled = true;
                     }  
                 }
                 getBeforeData();
                   
             } 
             };
             xhr.send(data);
         }
         
     }
     

     function validateProfile(){
         var nl = document.querySelectorAll("input");
         var profChange = document.getElementById('profile_change');
         var error  = []; // Ошибки в полях Фамилия Имя Отчество пустота
         var error2 = []; // Ошибки в полях Фамилия Имя Отчество недоп. симв.
         var errorPass = ''; // Ошибки при вводе пароля
         
         profChange.innerHTML = ''; //очистка информационного div сразу после нажатия submit
         profChange.style.color = 'green';
         
         for (var key in nl) {
             if ( (nl[key].id === 'surname' && nl[key].value.match(/([0-9a-zA-Z])/g) !== null)||
                (nl[key].id === 'name' && nl[key].value.match(/([0-9a-zA-Z])/g) !== null)||
                (nl[key].id === 'mname' && nl[key].value.match(/([0-9a-zA-Z])/g) !== null) ) {
                 error2.push(nl[key].id);
             }
       
             if ( (nl[key].id === 'surname' && nl[key].value === '')||
                  (nl[key].id === 'name' && nl[key].value === '')||
                  (nl[key].id === 'mname' && nl[key].value === '') ){
                 error.push(nl[key].id);
             }
           
             if (nl[key].id === 'password') {
                 var first_pass = nl[key].value;
             }
           
             if (nl[key].id === 'second_pass') {
                var second_pass = nl[key].value;
             }

             if (nl[key].id === 'email' &&  nl[key].value !== '' ) {
                 if (nl[key].value.match(/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/) === null) {
                     error2.push(nl[key].id);
                 }
             }
            
         }
         
         if (first_pass !== second_pass) { errorPass = 1; }
 
         if ( (error.length !== 0)||(error2.length !== 0) ) {
             profChange.innerHTML = 'Ошибка!';
             profChange.style.color = 'red';
             
             error.forEach(function(key, val){
                var label = document.querySelector('label[for='+key+']').textContent;
                profChange.innerHTML += '<div>Поле: "'+label+'" не должно быть пустым</div>';
             });
             
             error2.forEach(function(key, val){
                var label = document.querySelector('label[for='+key+']').textContent;
                profChange.innerHTML += '<div>Поле: "'+label+'" содержит недопустимые символы</div>';
             });
         }
         
         if (errorPass !== '') {
             profChange.style.color = 'red';
             profChange.innerHTML += '<div>Поле: "Новый пароль" отличается от поля "Повторить новый пароль"</div>'; 
         } 
     }
