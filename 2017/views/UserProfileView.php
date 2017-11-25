
<div class="content_main">
  
      
<fieldset>
    
    <!--Вывод сообщения об ошибке или удачном изменении профиля-->
    <div id="profile_change" class="profile_change"></div>
    
    <legend>Редактирование профиля</legend>
    
    <form id="formEdit">
        <div class="edit_prof" >
            <div  class="profile">
                <label for="surname" class="lab_profile">Фамилия</label>
                <input value="<?=$this->ShowUserName('surname');?>" id="surname" class="profile" >
            </div>
            <div class="profile">
                <label for="name" class="lab_profile">Имя</label>
                <input value="<?=$this->ShowUserName('name');?>" id="name"  class="profile" >
            </div>    
            <div class="profile">
                <label for="mname" class="lab_profile">Отчество</label>
                <input value="<?=$this->ShowUserName('mname');?>" id="mname" class="profile">
            </div>
            <div class="profile">
                <label for="password" class="lab_profile">Новый пароль</label>
                <input value="" id="password" class="profile" type="password">   
            </div>
            <div class="profile">
                <label for="second_pass" class="lab_profile">Повторить новый пароль</label>
                <input value="" id="second_pass" class="profile" disabled="" type="password">
            </div>
            <div class="profile">
                <label for="phone" class="lab_profile">Телефон(раб.)</label>
                <input value="<?=$this->ShowUserName('phone');?>" id="phone" class="profile">
            </div>
            <div class="profile">
                <label for="mobile" class="lab_profile">Телефон(моб.)</label>
                <input value="<?=$this->ShowUserName('mobile');?>" id="mobile" class="profile">
            </div>
            <div class="profile">
                <label for="email" class="lab_profile">E-mail</label>
                <input value="<?=$this->ShowUserName('email');?>" id="email" class="profile">
            </div>
            
         </div> 
    
    </form>
         <div class="sbmt">
             <input type="button" id="subm" value="Сохранить" style="border: 1px solid;" >
             <input type="hidden" id="user_id" value="<?=$this->user_id();?>">
         </div>

</fieldset>
          
</div> 
<script src="/js/profile.js"></script>