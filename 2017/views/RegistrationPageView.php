<div class="content_main">
    <form id="frm_rg" action='index.php?url=insertUser'>
    <fieldset>
        <legend>Регистрация</legend>
        
       <!--Вывод состояния---> 
       <div name="printAreaReg" id="printAreaReg" class="printArea"></div>    
       <div id="reg_outer" style="border:0px solid window; padding-top: 91px;">  
        
        
       <!--Фамилия-->
       <div class="regist">
           <div class="lbl_reg">
               <label for="surname" class="lab_profile">
                   <span class="star_reg">*</span>Фамилия
               </label>
           </div>  
           <div style="float: left;">
               <input  id="surname" name="surname" class="regist" pattern="^[А-ЯЁ][а-яё]+$" required="true" >
           </div>
            <div class="error_name" id="error_surname"></div>
       </div>
         
      <!--Имя-->
      <div class="regist">
           <div class="lbl_reg">
              <label for="name" class="lab_profile">
                     <span class="star_reg">*</span>Имя
              </label>
           </div>    
           <div style="float:left;">
                <input  id="name" name = "name" class="regist" pattern="^[А-ЯЁ][а-яё]+$" required>
           </div>
           <div class="error_name" id="error_name"></div>
       </div>
            
       <!--Отчество-->
       <div class="regist">
            <div class="lbl_reg">
                <label for="mname" class="lab_profile">
                     <span class="star_reg">*</span>Отчество
                </label>
            </div>
            <div style="float:left;">
                <input  id="mname" name="mname" class="regist" pattern="^[А-ЯЁ][а-яё]+$" required>
            </div>
            <div class="error_name" id="error_mname"></div>
       </div>
            
        <!--Должность-->
        <div class="regist">
            <div class="lbl_reg">
                <label for="id_post" class="lab_profile">
                    <span class="star_reg">*</span>Должность
                </label>
            </div>
            <div style="float:left;">
                <div class="regist"> 
                    <select class="regist" name="id_post" id="id_post" required='true'>
                        <option selected disabled value=''>Выберите должность...</option>
                    
                        <!--Руководство-->
                        <option value="100">Руководитель</option>
                        <option disabled>------------------------</option>
                        <?php foreach($post as $key_post){ 
                               if(($key_post['id_post'] < 6)||
                               ($key_post['id_post'] > 15)){ 
                                   if($key_post['id_post'] == '20'){ ?>
                        <option disabled>--------Наука 2------------</option>
                            <?php } ?>
           <option value="<?=$key_post['id_post'];?>"><?=$key_post['post_title'];?></option>
               <?php } }?>
                    </select>
                 </div>  
             </div>
        </div>
            
        <!--Отделение-->
        <div class="regist" id="division">
            <div class="lbl_reg">
                <label for="divis" class="lab_profile">
                    <span class="star_reg">*</span>Отделение
                </label>
            </div>  
            <div style="float:left;">  
                <div class="regist">    
                    <select name="divis" id="divis" class="regist" required='required'>
                        <option selected disabled value=''>Выберите отделение...</option>
                        <?php foreach($depar as $key_depar){?>
                        <option value="<?=$key_depar['uid'];?>"><?=$key_depar['title'];?></option>
                        <?php } ?>
                    </select>
                 </div> 
            </div>   
         </div>
            
         <!--Подразделение-->
         <div id="unit"></div>
            
         <!--Телефон раб.-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="phone" class="lab_profile">Телефон (раб.)</label>
             </div>    
             <div style="float:left;">   
                 <div style="float:left;">
                     <input class="regist" id="phone" name="phone" type="text"  placeholder="(495)851__-__" >    
                 </div>
             </div>
         </div>
            
         <!--Телефон моб.-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="mobile" class="lab_profile">Телефон (моб.)</label>
             </div>
             <div style="float:left;">
                 <input type="text" id="mobile" name="mobile"  class="regist" placeholder="(___)___-____">
             </div>   
         </div>
            
         <!--Логин-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="uname" class="lab_profile">
                     <span class="star_reg">*</span>Логин
                 </label>
             </div>    
             <div style="float:left;">
                 <input  id="uname" name="uname" class="regist"  pattern="^[a-z_][a-z0-9_-]{0,31}" required>
             </div>
             <div class="error_name" id="uname_check"></div>
             <div class="error_name" id="log_pass"></div>
         </div>
         
         <!--Пароль-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="password" class="lab_profile">
                     <span class="star_reg">*</span>Пароль
                 </label>
             </div>
             <div style="float:left;">
                 <input  id="password" name="password" class="regist" type="password" required>
             </div> 
             <div class="error_name" id="error_pass"></div>
         </div>
             
         <!--Email-->
         <div class="regist">
             <div class="lbl_reg">
                 <label for="email" class="lab_profile">E-mail</label>
             </div>  
             <div style="float:left;">
                 <input  id="email" name='email' class="regist" >
             </div> 
             <div class="error_name" id="em" style="padding-top: 7px;"></div>
         </div>
             
        <div class="sbm_reg">
            <input type="submit" id="reg_sbmt" name="submit" value="Сохранить" class="regist" style="width: 100px; margin-right: 20%">
        </div>
       
        </div> 
 
     </fieldset> 
     </form>   
</div>
<script type='text/javascript' src='../js/SelectDivision.js'></script>
<script type='text/javascript' src='../js/ChangePost.js'></script>
<?php include_once MenuController::GetUrl4Static()."/Views/footer.php"; ?>
<script src="../js/registration.js"></script>

