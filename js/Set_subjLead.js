
function Set_subjLead(event, name){
        
         var url_par = location.search;
         var id   = event.target.id; //id состоит из "id Темы + id Пользователя"
         var name = name; // Ф.И.О. пользователя
         var subj = document.getElementById(id).innerText;
          
         if(subj === undefined){subj = document.getElementById(id).innerHTML;}  
           
         id = id.split('+');
         
           
         if (confirm('Назначить сотрудника: ' + name + '\nРуководителем темы:"'+subj+'"?') ) {
             
             var xhr = new XMLHttpRequest();
             var body = "index.php?url=setsubj";
             var data = "idSubj="+id[0]+"&idUser="+id[1];   
              
             xhr.open("POST", body, false);
             xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
             
             xhr.onreadystatechange = function(){
                 if(xhr.readyState === 4 && xhr.status === 200) {
                     window.location = 'index.php' + url_par;
                 }
             };
             xhr.send(data); 
             
           }else{
             return false;
           }
}