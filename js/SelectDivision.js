function SelectDivision (){
    
  var elem = document.querySelector("select[name='divis']");  
  var id_division = elem.value;
  console.log(id_division);
  
  var xhr = new XMLHttpRequest();
  var body = "index.php?url=selectdivis";
  var data = "action=SelectDivision&id_division="+id_division;
  
  xhr.open("POST", body, false);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  
  xhr.onreadystatechange = function(){
    if(xhr.readyState === 4 && xhr.status === 200){
       
       //Для браузеров не поддерживающих атрибут required 
    //убираем свечение вокруг select
    if (elem.parentNode.hasAttribute('style') === true) {
       elem.parentNode.removeAttribute('style');
    }   
        
        document.getElementById('unit').innerHTML = xhr.responseText;
        
        //Браузеры не поддерживающие аттрибут required
        //Если submit уже нажимали, то селект должен выйти с 
        //признаком - обязательно к заполнению  
        if(buttonSubm !== undefined && buttonSubm !== null && buttonSubm === 1){
            var elem1 = document.getElementById('lid');
           
            elem1.parentNode.setAttribute("style", "box-shadow:0 0 10px red");
          
        }
        
        var a = document.getElementById('lid');
        
        a.addEventListener('change', function(){
            if (a.parentNode.hasAttribute('style') === true) {
                a.parentNode.removeAttribute('style');
            }
        });
        
        
        
    }  
  };
  
        /*     $.ajax({
                     type: "POST",
                     url: "index.php?url=selectdivis",
                     data: { action: 'SelectDivision', id_division: id_division },
                     cache: false,
                     success: function(responce){ $('div[id="unit"]').html(responce); }
                    });    */
    xhr.send(data);
}


