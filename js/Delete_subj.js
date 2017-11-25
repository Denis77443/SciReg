
function deleteSubj(event){
           var url_par = location.search;
           var id = event.target.id;
          // console.log(url_par);
          
           var subj = document.getElementById(id).innerHTML;

           if(subj === undefined){subj = document.getElementById(id).innerText;}
           
           id = id.split('+');
          // console.log(id);
           
           if (confirm("Вы желаете удалить тему:\n"+subj+" ?")){
               
               var xhr = new XMLHttpRequest();
               var body = "index.php?url=ajax&param=delete_subject";
               var data = "action=DeleteSubject&id_sub="+id[0]+"&user_id="+id[1];
               
               xhr.open("POST", body, false);
               xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
               
               xhr.onreadystatechange = function() {
                    if(xhr.readyState === 4 && xhr.status === 200) {
                      //  console.log(xhr.responseText);
                        window.location = 'index.php' + url_par;
                    }
                };
                
                xhr.send(data);
 
           }else{
               //console.log(url_par);
               return false;
           }
       }