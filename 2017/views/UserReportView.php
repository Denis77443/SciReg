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
    <fieldset>
        <legend>
            <a class="legenda">Файлы</a>
        </legend>
        <form name="upload">
            <input type='file' value='Прикрепить файл' multiple id='files' style="width: 90px;">
            <div id="res_fl"><?=$this->ShowFiles();?></div>
        </form>
    </fieldset>
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
    
    function showFile(e){
        var w = window.open();
        w.document.open();
        w.document.write(e.target.innerHTML);
        w.document.close();
        //console.log(e.target.innerHTML);
        //console.log(YEAR);
    }
    
    document.getElementById('files').addEventListener('change', function(e){
        e.preventDefault();
        console.log(e.target.files[0]);
        console.log(e.target.files[0].name);
        
        var input = e.target.files[0];
        
        var xhr = new XMLHttpRequest ();
        var body = "index.php?url=ajax&param=file";
       // var data = "action=upload&input="+input;
       var formData = new FormData();
          // formData.append('action', 'upload');
           formData.append('file',input);
     //  formData.append('name', input.name);
       
       //var data = 'filename='+e.target.files[0].name+"&form="+formData;
       
        
        xhr.open("POST", body, true);
     //   xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        //xhr.setRequestHeader("Cache-Control", "no-cache");
       // xhr.setRequestHeader('Content-type', 'multipart/form-data; charset=UTF-8');
      //  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
     //    xhr.setRequestHeader("X-File-Name", input.name);
       //  xhr.setRequestHeader("X-File-Size", input.size);
       //  xhr.setRequestHeader("X-File-Type", input.type);
        
        
        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {
               document.getElementById("res_fl").innerHTML += "<div name='fl' style='text-decoration: underline; cursor:pointer; clear:both; float:left'>"+input.name+"</div>";    
            }
        };
       // xhr.send(data);
         xhr.send(formData);
    });
    //document.getElementById("lat").addEventListener("click", Latex);
    //document.getElementById("77").addEventListener("change", check);
        
    function ready(){ 
       
      
      var textReport = document.querySelectorAll("textarea[class='textarea_class']");
      
       var fl = document.querySelectorAll("div[id='res_fl']:not(br)");
       
      Array.prototype.forEach.call(fl, function(obj){
          
          ['click'].forEach(function(evt){
              obj.addEventListener(evt, function(e){
                  console.log(e.target);
                  console.log(e.target.textContent);
                  var name = e.target.textContent;
                  
                  var host = location.host;
                  
                  var url = 'http://'+host+'/'+YEAR+'/index.php?url=openfile&name='+name;
                  window.open(url, "location=yes,resizable=yes,scrollbars=yes,status=yes"); 
                  
              }, false) ;
          });
          //console.log(obj.childNodes);
      }); 
      
      
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

