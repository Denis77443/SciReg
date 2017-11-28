<fieldset>
    <legend>
        <a class="legenda">
            <label for='report'>Отчет</label>
        </a>
    </legend>
    
    <?php if($this->titleNIR == 'План НИР'){?>
    <!--Latex-->
    <div id="lat">
        <a id='<?=$this->user_id();?>' class="latex" >
            <img id='<?=$this->user_id();?>' src='/Images/LaTeX25.png' alt='lorem'>
        </a>
    </div>
    <script type="text/javascript" src="/js/Latex.js"></script>
    <?php } ?>
    
    <?php $p1='report'; ?>
    <?php include ROOT_MENUU.'/views/PreHtmlView.php';?>
    <div id="textRep">
        <textarea <?= $this->disabled_rep; ?> class="textarea_class" name='report' 
              id="<?=$this->user_id();?>"><?=$this->ShowReport();?></textarea>
    </div>  
    <div style="padding-left:0%">
        <form name="upload">
            
            <!--Показывать кнопку прикрепления файлов только на "собственной" 
            странице пользователя-->
            <?=$this->ShowButtonUploadFiles()?>
               
            
            <div id="res_fl"><?=$this->ShowFiles();?></div>
        </form>
        </div>
</fieldset>


<?php include ROOT_MENUU.'/views/PopupBoxView.php';?>

<div id="result"></div>





<script>
    /*
     * @desc changes the size of the textarea window depending on the content
     * @param {type} obj 
     * @returns {undefined}
     */
   // document.onmouseup = function(){alert('sdfsfd');}
    document.addEventListener("DOMContentLoaded", ready);
    
   
    //document.getElementById('res_fl').addEventListener('click', showFile);
    
    
    if(document.getElementById('files')){
    //console.log('FILES');
    document.getElementById('files').addEventListener('change', function(e){
        var input = e.target.files[0], flag=0, sameFile=0;
        var formData = new FormData();
            formData.append('file',input);
            
            console.log(e.target.files[0]);
            
        var oldFiles = document.querySelectorAll("div[class='show_fl']");
           
           if (oldFiles.length !== 0) {
               Array.prototype.forEach.call(oldFiles, function(obj) {
                   if ( obj.innerHTML === input.name ) {
                       flag=(confirm(input.name+" уже существует!\n\nЖелаете перезаписать?") == true) ? 1:0;    
                       sameFile = 1;
                   } else {
                      flag = 1; 
                   }
               });
                   
              
           } else {
               console.log('ничего нет');
               flag = 1;
           }
           
       if(flag === 1){    
   
        var xhr = new XMLHttpRequest ();
        var body = "index.php?url=ajax&param=file";
        
        xhr.open("POST", body, true);
      
        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {
             // console.log(xhr.responseText);
               if (sameFile === 0) {

                 document.getElementById("res_fl").innerHTML += "<div name='fl' class='show_fl'>"+input.name+"</div>";    
                 document.getElementById("res_fl").innerHTML += "<div class='del_fl'><label for='"+input.name+"'><img src='/Images/delete-icon.png' class='imgDel'></label></div>";    
                 openFile('show_fl');
                 deleteFile('del_fl');
              } 

            }
            
            e.preventDefault();
        };
        xhr.send(formData);
    }  
    }, false);
    }
    //document.getElementById("lat").addEventListener("click", Latex);
    //document.getElementById("77").addEventListener("change", check);
      
 /*
  * Открытие прикреплённого файла
  * 
  * @param {type} classs
  * @returns {undefined}
  */
 
 function openFile(classs){
     var fl = document.querySelectorAll("div[class="+classs+"]");
    
     /*
      * Открытие прикреплённого файла
      */ 
     Array.prototype.forEach.call(fl, function(obj){
         ['click'].forEach(function(evt){
             obj.addEventListener(evt, function(e){
                 var name = e.target.textContent,
                     host = location.host,
                     user_id = location.search.split('id=')[1];
                     url = 'http://'+host+'/'+YEAR+'/index.php?url=openfile&name='+name+"&user_id="+user_id;
                     
                  window.open(url, "location=yes,resizable=yes,scrollbars=yes,status=yes");   
              }, false) ;
          });
      });  
 } 
 
 /*
  * Удаление прикреплённого файла
  * @returns {undefined}
  */
 function deleteFile(classs, fu){
     var delFile = document.querySelectorAll("div[class="+classs+"]");  
     console.log(delFile);
     console.log(fu);
     /*
       * Удаление прикреплённого файла
       */ 
      Array.prototype.forEach.call(delFile, function(obj){
          ['click'].forEach(function(evt1){
              obj.addEventListener(evt1, function(e){
                  var name1 = e.target.textContent;
                  var label = obj.childNodes[0];
                  
                //  alert('DELete '+label.htmlFor);
                  console.log('label === '+label.htmlFor);
                  //console.log('name1 === '+name1);
               if( confirm('Удалить файл: '+label.htmlFor+" ?") === true){
                   console.log('delete!!!');
                   var xhr = new XMLHttpRequest ();
                   var body = "index.php?url=ajax&param=deletefile";
                   var data = "action=delete&filename="+label.htmlFor;
                   
                   xhr.open("POST", body, true);
                   xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                   
                   xhr.onreadystatechange = function(){
                       if ( xhr.readyState === 4 && xhr.status === 200 ) {
                          
                          var del = document.querySelectorAll('div[class=show_fl]');
                          
                          Array.prototype.forEach.call(del, function(obj){
                              if(obj.textContent === label.htmlFor){
                                  obj.remove();
                                  label.remove();
                              }
                             
                          });
                        
                       }
                       
                   };
                   xhr.send(data);
               }
                        
              }, false) ;
          });
      }); 
 }   
      
    function ready(){ 
       
    
      var textReport = document.querySelectorAll("textarea[class='textarea_class']");
 
       openFile('show_fl');
       deleteFile('del_fl', 'from READY');
      // console.log('DEL from READY');
     //e.preventDefault();
      
      Array.prototype.forEach.call(textReport, function(obj){
         // var data = {};
        var offset = obj.offsetHeight - obj.clientHeight;
        var resizeTextarea = function(el) {
            var scrollLeft = window.pageXOffset ||
            (document.documentElement || document.body.parentNode || document.body).scrollLeft;

            var scrollTop  = window.pageYOffset ||
            (document.documentElement || document.body.parentNode || document.body).scrollTop;
    
            el.style.height = "auto";
            el.style.height = (el.scrollHeight + offset)+'px';
            
            window.scrollTo(scrollLeft, scrollTop);
            
        };
        
        resizeTextarea(obj);
        ['input', 'keyup'].forEach( function(evt){
            
          obj.addEventListener(evt, function(){resizeTextarea(obj)}, false);
         // obj.addEventListener('mouseout', function(){console.log('ddfdfdf');});
          
          
        });
       
        ['change'].forEach(function(evt){
            
          obj.addEventListener(evt, check, false) ;
         
        });
        
       
        
       
      });
      
      
      
    }
   
 
  
  
    function check(e){  
        
      
        
        var fieldName = e.target.name;
        var getLabel = document.querySelector("label[for='"+fieldName+"']").innerHTML;
        
        
        document.getElementById('saveField').innerHTML = "\""+getLabel.replace(/[^а-яА-Я\s]/g,'').toLowerCase()+"\"";
        document.getElementById('popup').style.display = 'block';
        //var content = document.getElementById('77').value;
        
       // document.getElementById('content').innerHTML = content.slice(0,400)+'...';
        
     var modal = document.querySelector("#modal");
var modalOverlay = document.querySelector("#modal-overlay");
var closeButton = document.querySelector("#close-button");
var cancelButton = document.querySelector("#canсel-button");
var openButton = document.querySelector("#open-button");

openButton;

closeButton.addEventListener('click', function(){
   
  document.getElementById('popup').style.display = 'none';  
});


cancelButton.addEventListener('click', function(){
    
    document.getElementById('popup').style.display = 'none';
    //console.log("user id= "+<?=$this->user_id()?>);
    var userId = <?=$this->user_id()?>;
    console.log("UID "+userId);
    console.log("fieldName "+fieldName);
    
    var xhr = new XMLHttpRequest();
    var body = "index.php?url=ajax&param=save_report";
    var data = "user_id="+userId+"&field_name="+fieldName+"&value="+e.target.value;
    
    xhr.open("POST", body, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('result').innerHTML = xhr.responseText;
                
            }
        };
        
        xhr.send(data);
        e.preventDefault();
    
});

    }
    
</script>

