<div class="content_main" style="min-height: 600px;">
    <div style="text-align:center;padding-top:15px;padding-bottom:20px;">
        Всего зарегестрировано пользователей: <?=$this->number[0]['count']?>
    </div>
    <div style='text-align:center;
         padding-top:55px;
         padding-bottom:20px; 
         font-weight: bold;'>Поиск сотрудников по фамилии
    </div>
    <div style='border: solid 0px; text-align:center;' id="letter">
        <?=$outputletters;?>
    </div> 
    
    <div class="results" id="result">
        <div class="wrapper" style="padding-left: 17%; width: 710px">
        <!--Вывод найденных пользователей-->
        <div id="scince" class="search"></div>
        <div id="scince2" class="search"></div>
        </div>
    </div>
    
</div>


<script>
    
    document.getElementById('letter').addEventListener('click', ajaxSearch);
    
    function ajaxSearch(event){
        
        var xhr = new XMLHttpRequest();
        var body = "index.php?url=ajax&param=search";
        var nameLetter = "id="+event.target.id;
                
        xhr.open("POST", body, true);
      //  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
               
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                
                document.getElementById('scince').innerHTML = '';
                document.getElementById('scince2').innerHTML = '';
                
                var myArr = JSON.parse(xhr.responseText);
                var legScince = '<legend>Научные работники</legend>';
                var legScince2 = '<legend>Наука 2</legend>';
              
              if( (myArr.scince.length !== 0)&&(myArr.scince2.length !== 0) ){
                   document.getElementById('scince').style.float = 'left';
                   document.getElementById('scince2').style.float = 'right';
               }else{
                   document.getElementById('scince').style.removeProperty("float");
                   document.getElementById('scince2').style.removeProperty("float");
               }
              
               
               if (myArr.scince.length !== 0) {
                document.getElementById('scince').innerHTML = '<fieldset>'+legScince+myArr.scince+'</fieldset>';
               }
               if (myArr.scince2.length !== 0) {
                document.getElementById('scince2').innerHTML = '<fieldset>'+legScince2+myArr.scince2+'</fieldset>';
               }
                
            }
        };
        
        xhr.send(nameLetter);
        event.preventDefault();
    } 
 
</script>