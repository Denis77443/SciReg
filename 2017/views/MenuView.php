<?php   
  if(!defined('ROOT_MENUU')) die('access denied');
  include_once ScinceController::GetUrl4Static()."/Views/header.php";
 // echo "sdjklfjsdklfksd";
  $subjectPath = include(ROOT_MENUU.'/config/JSON_progs.php');
  //echo '<br />TIME -MENU '.(microtime(true) - $this->start); 
 // var_dump($subjectPath);
 //echo "<h2><a style = 'color:green;' >МЕНЮ START: ".(microtime(true) - INDEX_START)."</a></h2>"; 
?>
<ul id="menu">
  <li>
    <a href="<?=$this->get_url() ?>"><?= $this->home ?></a>
  </li> 
  <li>
      <a href="index.php"><?= $this->name_user()?></a>
  </li>
  <?php if(!empty($this->podrazd)){?>
  <li>
      <a><?= $this->podrazd ?></a>
            <ul>
      <?php if(isset($this->depar)){
              foreach($this->depar as $key => $value){
        ?>
        <li>
            <a style="white-space: normal;" 
               href="index.php?url=department&did=<?=$key?>"><?=$value?>
             <!--  href="Views/departments_in_division.php?did=&boss=1">-->
            </a>
            <ul>
              <?php foreach ($this->office as $key_of => $value_of){
                       if($value_of == $key){ ?>
               <li><a style="white-space: normal;" href='<?php if(($this->isset_lab[$key_of] != NULL)||
                                      ($this->isset_sec[$key_of] != NULL)){
                           if(isset($this->isset_lab[$key_of])){
                           echo "index.php?url=laboratory&lid=".$this->isset_lab[$key_of];} //Отделы
                           
                           if(isset($this->isset_sec[$key_of])){
                           echo "index.php?url=laboratory&lid=".$this->isset_sec[$key_of];} //Центр
                           
                           //echo 'laboratories_in_division.php?dep_id='.$this->isset_lab[$key_of];   
                       }else{
                           echo "index.php?url=usersindivis&lid=".$this->id_otdel[$key_of];}?>'>
                           <?=$key_of?></a><ul><?php
                          if(isset($this->laborat)){  
                            foreach($this->laborat as $key_l => $value_l) {
                                if ($value_l == $this->id_otdel[$key_of]){   
                        ?>
                        <li><a style="white-space:normal;" href="index.php?url=usersindivis&lid=<?=$this->id_laborat[$key_l]?>"><?=$key_l?></a>
                        </li>                 
                        <?php 
                                }       
                             } 
                          }   
                        ?>
                     </ul>  
                    <ul><?php
                        if(isset($this->sector)){
                          foreach ($this->sector as $key_s => $value_s) {
                            if($value_s == $this->id_otdel[$key_of]){
                      ?>
                        <li>
                            <a style="white-space: normal;" 
                               href="index.php?url=usersindivis&lid=<?=$this->id_sector[$key_s]?>"><?=$key_s?>
                            </a>
                        </li>
                      <?php  
                            }  
                          }
                        }
                        ?></ul></li><?php } } ?></ul>
        </li>
          <?php 
               }
            } 
          ?>
    </ul>
  </li>
  <?php } ?>
   <li>
       <?php if(isset($this->service)){?>
      <a><?= $this->service ?></a>
      <ul id="ul_serv">
          <li><a href="index.php?url=subjectcat"><?=$this->subject_list?></a>
              <ul id = "subj_cat_ul">
                  <?php foreach($subjectPath as $key_subj => $value_sub){ ?>
                  <li>
                        <a class="sbj" id="<?=$key_subj?>" style="white-space: normal; cursor: pointer;" ><?=$value_sub?></a>
                    </li>
                   <?php } ?>
              </ul>   
          </li>
          <!--Добавить тему (пользователь - зав.отделения, секретарь)-->
         
          <?php if( (isset($this->insert_subj))AND($this->ShowInsertItem() == TRUE) ){ ?>
          
          
          
    
          <script type="text/javascript" src="../js/InsertSubNew.js"></script>
          
          
            <li id="d_ins_subj">
                <a><?=$this->insert_subj?></a>
                <ul id='ins_subj'>
                        <li>
                            <a style="cursor: pointer; white-space: normal;" 
                               id="1">Темы Программ ФНИ ГАН</a> 
                           <!--    onclick='Insert_Sub_New(event, slave_id =<?=$this->user_id();?> )' >Темы Программ ФНИ ГАН</a> -->
                        </li>
                        <li>
                            <a style="cursor: pointer; white-space: normal;" 
                               id="2">Темы Президиума и ОФН РАН</a> 
                         <!--      onclick='Insert_Sub_New(event, slave_id =<?=$this->user_id();?>)'>Темы Президиума и ОФН РАН</a>-->
                        </li>
                        <li>
                            <a style="cursor: pointer; white-space: normal;" 
                               id="3">Темы Программ ФЦП развития УНУ</a>
                           <!--    onclick='Insert_Sub_New(event, slave_id =<?=$this->user_id();?>)'>Темы Программ ФЦП развития УНУ</a>-->
                        </li>
                        
                    </ul>
            </li>
                       
                <script>
                    var slave = <?=$this->user_id();?>; 
                    document.getElementById('ins_subj').addEventListener('click', InsertSubNew);
                </script>
                
                <?php } ?>
            <!-- Назначить руководителя темы (пользователь - зав. отделения)-->
            <?php if(method_exists('HeadController','leadship')){HeadController::leadship();}?>
           
            <script type="text/javascript" src="/js/Set_subjLead.js"></script>
            
            <?php if(isset($this->leadship)){ ?>
                <li>
                    <a><?=$this->leadship?></a>
                    <ul id="ul_sub_lead">
                        
                        <?php foreach($this->leadship() as $key => $value) {?>
                        <li>
                            <a class="menu_serv" id="<?=$key."+".$this->user_id()?>" ><?=$value?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php }
            
            //УДАЛЕНИЕ
            //
            //Удалить тему (пользователь - секретарь, зав. отделения)
                 if(isset($this->candelete)){ 
                    if($this->candelete() == TRUE){
               ?> 
                
                <script type="text/javascript" src="../js/Delete_subj.js"></script>
                
                <li id="li_del_sbj0">
                    <a><?=$this->deleteSubj?></a>
                    <ul id='ul_del_sbj0'>
                        <?php foreach($this->ListOfSubj() as $key_l => $val_l) { ?>
                        <li>
                            <a class='menu_serv' 
                               id="<?=$key_l.'+'.$this->user_id()?>"><?=$val_l?></a> 
                          <!--     onclick="Delete_subj(event, id_sub=<?=$key_l?>, user_id=<?=$this->user_id();?>)"> -->
                        </li>
                        <?php } ?>
                    </ul>
                </li><?php } }else { 
          //Удалить тему (пользователь - руководитель темы)
          if(($this->isUserSubjectLeader() !== FALSE)&&(isset($this->deleteSubj))) { ?>
                
                <script type="text/javascript" src="../js/Delete_subj.js"></script>
                
            <li id="li_del_sbj1">
                <a><?=$this->deleteSubj?></a>
                    <ul id='ul_del_sbj1'>
                        <?php foreach($this->title_sub as $key_sub => $value_sub){ ?>
                        <li>
                            <a class='menu_serv'  
                               id="<?=$key_sub.'+'.$this->user_id()?>"><?=$value_sub?></a> 
                            <!--   onclick="Delete_subj(event, id_sub=<?=$key_sub?>, user_id=<?=$this->user_id();?>)"-->
                            
                        </li>
                    <?php } ?>
                    </ul>
            </li>
                <?php } }?>
                    <?php if(isset($this->search)){?>
            <li>
                <a href="index.php?url=search"><?=$this->search?></a>
            </li>
           <?php } ?>
            <?php if(isset($this->status)){?>
            <li>
                <a id="status" style="cursor:pointer"><?=$this->status?></a>
            </li>
           <?php } ?>
            
           <!--Руководитель темы пункт меню Статус(по темам)--->
           <?php if($this->isUserSubjectLeader() !== FALSE) {?>
            <li>
                <a>Статус (по темам)</a>
                <ul id = "st_sbj">
                    <?php foreach ($this->title_sub as $key_sub => $value_sub) {?>
                       
                       <li >
                          
                           <a id="<?=$key_sub?>" style="white-space: normal; width:190px; cursor: pointer " ><?=$value_sub;?></a>
                          
                       </li>
                        
                        <!--<li>fgfgf</li>-->
                    <?php } ?>
                </ul>
            </li>
           <?php } ?>
            
      </ul>
  </li> 
  <!--Руководитель темы пункт меню ТЕМА-->
  <?php if($this->isUserSubjectLeader() !== FALSE) {?>
  <li>
      <a><?=$this->subject?></a>
      <ul>
          
          <?php foreach ($this->title_sub as $key_sub => $value_sub) {?>
          <li>
              <a style="white-space: normal; width:190px;"><?=$value_sub?></a>
              <ul>
              <?php foreach ($this->arraySubUser as $key_sub_usr){    
                       if(in_array($value_sub, $key_sub_usr)){?>
                       <li>
                           <a href="index.php?url=userpage&id=<?= $key_sub_usr['uid']?>"><?=$key_sub_usr['short_name']?></a>
                       </li>
              <?php } }?>
              </ul>    
          </li>
          <?php } ?>
      </ul>
  </li>
       <?php } }?>
  <li>
    <a href="index.php?url=profile"><?= $this->profile ?>
    </a>
  </li>
  <li style="float:right;">
      <a href="<?=$this->get_url() ?>index.php?url=logout">Exit</a>
  </li>
 <li style="float:right;">
      <a href="index.php?url=help" 
         style="padding: 3px 3px 3px"><img src='/Images/help30.png'></a>
  </li>
</ul>



<?php  if(isset($this->service)){ ?>
<script>
    document.getElementById('subj_cat_ul').addEventListener('click', getSubjTitle);
    
    function showListOfSubjects(e){
        
        var xhr = new XMLHttpRequest();
        var body = "index.php?url=ajax&param=subjectlist";
      //  var id = JSON.stringify({id:e.target.id});
       
        
        xhr.open("POST",  body, true);
        xhr.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
        
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
             //   console.log(data);
                document.getElementById('subjList').innerHTML = '<fieldset>'+'<legend>'+data['id']+'</legend>'+data['title']+'</fieldset>';
                
            }
        };
        
  
        xhr.send(JSON.stringify({"id":e.target.id}));
    }
    
    function getSubjTitle(e){
        
        subjList(e);
        showListOfSubjects(e);
        checkMainPage();
        
        

    }
    
    
</script>
<?php } ?>
 <?php if(isset($this->status)){ ?>
<script>
    
    
    document.getElementById('status').addEventListener('click', getStatus);
    
    
    
    function headerStat(){
        var header = document.getElementById('stat_header');
        var form = document.getElementsByTagName('ul')[0];
        
        form = (header !== null) ? header:form; 
        
        var  formParent = form.parentNode,
                   el; 
                   // console.log(form);
        
        while(el = form.nextSibling) {
          //  console.log('formPPPP'+formParent);
              formParent.removeChild(el);
              
          }
          
        function removeClass(classs){
           const elements = document.getElementsByClassName(classs);
           while (elements.length > 0) elements[0].remove(); 
        }
     
     
     if(header !== null){
         ['sbj_stat', 'footer'].forEach(function(ev){
          removeClass(ev);
        //  console.log('delete header');
       });
       var div_footer = document.createElement("div");
          div_footer.className = 'footer';
          document.body.appendChild(div_footer);
       
     }else{
       ['content_main', 'sbj_stat', 'footer'].forEach(function(ev){
          removeClass(ev);
       });
       
       var div_0 = document.createElement("div");
          div_0.className = 'content_main';
          div_0.style = 'min-height: 600px;';
          document.body.appendChild(div_0);
                    
          var div_1 = document.createElement("div");
          div_1.id = 'sbj_stat';
          div_0.appendChild(div_1);
           
          var div_footer = document.createElement("div");
          div_footer.className = 'footer';
          document.body.appendChild(div_footer);
     //  console.log('delete ALL');
       
    }
        
    }
    
    
    function getStatus(e){
        
        
        headerStat();
        checkMainPage();
        
        
        var xhr = new XMLHttpRequest();
        var body = "index.php?url=ajax&param=status";
        var action = "action=showstatus&id="+<?=$this->user_id();?>;
        
        xhr.open("POST", body, false);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
             
                document.getElementById('sbj_stat').innerHTML = xhr.responseText;
 
            }
        };
        
        
        xhr.send(action);
        
    }
</script>    

 <?php } ?>


<!--Если пользователь руководитель ТЕМЫ--->
 <?php if( (method_exists(get_called_class(), 'isUserSubjectLeader'))&&($this->isUserSubjectLeader() !== FALSE) ) {?>

    <script>
        
        document.getElementById('st_sbj').addEventListener('click', getSubjectId);
                
        function getSubjectId(e){
        
        headerStat();
        checkMainPage();  
          
          
          
          var xhr = new XMLHttpRequest();
          var body = "index.php?url=ajax&param=subjectStat";
          var id_subject = "action=showsubjstatus&id="+e.target.id+"&title="+e.target.text;
          
           xhr.open("POST", body, false);
           xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
           
           
           xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('sbj_stat').innerHTML = xhr.responseText;
                
            }
        };
        xhr.send(id_subject);
        //e.preventDefault();   
    }

        
        
    </script>
    
 <?php } ?>
    
<script>
    document.addEventListener("DOMContentLoaded", checkMainPage);
    
    
    /*
     * checkMainPage - скрывать пункты меню ДОБАВИТЬ ТЕМУ и УДАЛИТЬ ТЕМУ 
     * на тех страницах где нет фактического удаления и добавления ТЕМ, несмотря
     * на то, что категория пользователя позволяет ДОБАВЛЯТЬ/УДАЛЯТЬ
     *   
     */
    function checkMainPage(){
       var insertSubject = document.querySelector('#d_ins_subj');      
       var deleteSubject0 = document.querySelector('#li_del_sbj0');      
       var deleteSubject1 = document.querySelector('#li_del_sbj1');
             
             
        if(!document.getElementById('view_sbj')){
            [insertSubject, deleteSubject0, deleteSubject1].forEach(function(ev){
               if(ev){
                   ev.style.display = 'none';
               } 
            });
          
        }
    }
</script>
<script>
    /*
     * Вызов функции - Руководитель Темы (Сервис => Темы(Руководство))
     */
    if(document.getElementById('ul_sub_lead')){
        document.getElementById('ul_sub_lead').addEventListener('click', function(e){
            Set_subjLead(e,name='<?=$this->ShowSNMUser();?>');
        });
    }    
    
    /*
     * Вызов функции - Удаление Темы (Сервис => Темы(Удаление)) 
     * [КАТ. ПОЛЬЗВОТ. - СЕКРЕТАРЬ, РУК. ОТДЕЛЕНИЙ]
     */
    if(document.getElementById('ul_del_sbj0')){
        document.getElementById('ul_del_sbj0').addEventListener('click', function(e){
           deleteSubj(e);
        });
    }
    
    /*
     * Вызов функции - Удаление Темы (Сервис => Темы(Удаление)) 
     * [КАТ. ПОЛЬЗВОТ. - РУКОВОДИТЕЛЬ ТЕМЫ]
     */
    if(document.getElementById('ul_del_sbj1')){
        document.getElementById('ul_del_sbj1').addEventListener('click', function(e){
           deleteSubj(e);
        });
    }
</script>
    
    
    