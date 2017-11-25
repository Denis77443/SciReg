      document.getElementById('lat').addEventListener('click', Latex);
      
      function Latex(event){
          
           var text = document.getElementsByTagName("textarea")[2].value;
           /*
           var simArr = text.match(/[^a-z0-9A-Zа-яА-Я\s\:]/g);
          
           
           
           var Shchema = {
               1: {simbol: 'α',latex:'$\\alpha$'},
               2: {simbol: 'β',latex:'$\\beta$'},
               3: {simbol: 'Θ', latex: '$\\Theta$'},
               4: {simbol: 'Ψ', latex: '$\\Psi$'},
               5: {simbol: 'Λ', latex: '$\\Lambda$'},
               6: {simbol: 'Δ', latex: '$\\Delta$'}
           };
           
         
           if(simArr !== null){
               
           for (const val of Object.values(Shchema)) {
                 //   console.log("Объект "+val.simbol);// use val
                  //  console.log(simArr.indexOf(val.simbol));
                   // if(simArr.find)
                    
                    if(simArr.indexOf(val.simbol) !== -1){
                       var h = new RegExp(val.simbol);
                   //    console.log(h);
                        text = text.replace(h, val.latex);
                   //  console.log(val.latex);
                    }
              }
          }else{
              text = document.getElementsByTagName("textarea")[2].value;
          }
           */
           
         //  console.log(text);
           
           var host = location.host;
           var user_id = event.target.id;
          
       
           
           var xhr = new XMLHttpRequest();
           var body = "index.php?url=latex&id="+user_id;
         //  var param = 'kk=hhh&jjj=oooo';
          
           xhr.open("POST", body, false);
          // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
               xhr.setRequestHeader('Content-Type', 'text/html; charset=utf-8');
               
           /*   
             xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var str = xhr.responseText;
              //  document.getElementById('test').innerHTML = str;
                
              //  setTimeout(function() {
                  // var url = 'http://'+host+'/'+YEAR+'/index.php?url=latex&id='+user_id; 
                  //  window.open(url, "location=yes,resizable=yes,scrollbars=yes,status=yes"); 
                //}, 2000);
                
                var url = 'http://'+host+'/'+YEAR+'/index.php?url=latex&id='+user_id;
                  window.open(url, "location=yes,resizable=yes,scrollbars=yes,status=yes"); 
               // alert(str);
              //  consol.log(str);
              
             // window.open('/tmp/denis.pdf');
            }else{
                document.getElementById('test').innerHTML = 'НЕТ ОТВЕТА';
            }
             };*/
            
           xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
               var str = xhr.responseText;
               
               if (str.search('No pages of output.') !== -1) {
                       
                 if (str.match(/-----------------------(.|\n)*terminal!/)){ 
                   alert('Ошибка LaTeX!!!\n'+str.match(/-----------------------(.|\n)*terminal!/));
                 } else {
                       
                   if (str.match(/-----------------------(.|\n)*memory you used:/)) { 
                     alert('Ошибка LaTeX!!!\n'+str.match(/-----------------------(.|\n)*memory you used:/));
                   }
                 }    
                } else {
                  var url = 'http://'+host+'/'+YEAR+'/index.php?url=latex&id='+user_id;
                  window.open(url, "location=yes,resizable=yes,scrollbars=yes,status=yes"); 
                }
              
            }
        };
          
          xhr.send(text); 
          
        //  var url = 'http://'+host+'/'+YEAR+'/index.php?url=latex&id='+user_id; 
          //         window.open(url, "location=yes,resizable=yes,scrollbars=yes,status=yes"); 
                   //  event.preventDefault();

       }