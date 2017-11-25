  function InsertSubNew(e){
    
        var flag_subj = e.target.id; //ID Флага - признак категории тем
    
        var slave_id = slave ; //ID пользователя
    
    var no_subject = document.getElementById('NoSubject'); //ID - div с сообщением - 
                                                           //"Темы не заданы...." 
                                                           //при установке тем div должен быть скрыт 
    if(no_subject){
        document.getElementById('NoSubject').style.display = 'none'; 
    }
    
    var xhr = new XMLHttpRequest();
    var body = "index.php?url=ajax&param=insert_subject";
    var data = "action=ShowSubject&flag_subj="+flag_subj+"&slave_id="+slave_id;
    
    xhr.open("POST", body, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                
                //Вывод списка тем
                document.getElementById('result_sub0'+flag_subj).innerHTML = xhr.responseText;
                
               /* Установка скролла в месте выбора тем
                * 
                var getCoordinates = document.getElementById('result_sub0'+flag_subj).getBoundingClientRect();
                window.scrollTo(getCoordinates.left, getCoordinates.top);
                */
                
                if(document.querySelector("select[id='sel_1_sub']")){
                    document.getElementById('sel_1_sub').addEventListener('change', function(e){
                        SelectSubject(e,flag=flag_subj, user_id=slave_id);
                    });
                  // document.querySelector("select[id='sel_1_sub']").onchange = SelectSubject1; 
                }
            
               
            }
        };
    xhr.send(data);
}

function SelectSubject(e,flag, user_id){
   // console.log(flag);
      
    var id_subject = e.target.value;
  
  if(flag === '3'){
      document.getElementById("save0"+flag).style.display = 'block';
  }else{
  
    var xhr1 = new XMLHttpRequest();
                    var body1 = "index.php?url=ajax&param=select_subject";
                    var data1 = "action=SelectSubject"+"&id_subject="+
                                 id_subject+"&flag="+flag+"&slave_id="+
                                 user_id;
                         
                    xhr1.open("POST", body1, false);
                    xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                  //  console.log(xhr1);
                            
                    xhr1.onreadystatechange = function() {
                        if(xhr1.readyState === 4 && xhr1.status === 200) {
                           //Вывод списка подтем
                           document.getElementById('subtheme'+flag).innerHTML = xhr1.responseText; 
                           
                           //Показываем кнопку сохранения
                           if(document.getElementById('id_sele')){
                               document.getElementById('id_sele').addEventListener('change', function(e){
                                   var value = e.target.value;
                                   //console.log(" FLAG "+flag+' val '+e.target.value);
                                   if(value !== 'Выберите пункт ...'){
                                        document.getElementById("save0"+flag).style.display = 'block';
                                   }else{
                                       document.getElementById("save0"+flag).style.display = 'none';
                                   }
                               });
                           }
                           
                        }
                    };
                   xhr1.send(data1);     
               }
  
  
}

