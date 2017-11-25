<fieldset>
    <legend><a style='font-weight:bold;'>Результаты научной деятельности</a></legend>
    <label for='articles_in_russ'><a>Статьи в рецензируемых российских журналах :</a></label>
    
    <textarea 
              <?= $this->disabled_rep; ?>
              class="textarea_class" 
              name='articles_in_russ' 
              id ='articles_in_russ'
              style="width: 998px;"><?=$this->ShowResultsOfSA()['air']?></textarea>
    <label for='articles_in_foreign'><a>Статьи в рецензируемых зарубежных журналах :</a></label>
    <textarea 
              <?= $this->disabled_rep; ?>
              class="textarea_class" 
              name='articles_in_foreign'
              id='articles_in_foreign'
              style="width: 998px;"><?=$this->ShowResultsOfSA()['aif']?></textarea>
    <label for='monograph'><a>Монографии (ISBN) :</a></label>
    <textarea  
              class="textarea_class" 
              <?= $this->disabled_rep; ?>
              cols='140'
              name='monograph' 
              id='monograph'
              style="width: 998px;"><?=$this->ShowResultsOfSA()['mon']?>
    </textarea>
    <label for='reports_at_conf'><a>Доклады на конференции :</a></label>
    <textarea  
              class="textarea_class" 
              <?= $this->disabled_rep; ?>
              cols='140'
              name='reports_at_conf'
              id ='reports_at_conf'
              style="width: 998px;"><?=$this->ShowResultsOfSA()['rac']?></textarea>
    <label for="lecture_course"><a>Курсы лекций :</a></label>
    <textarea  
              class="textarea_class"
              <?= $this->disabled_rep; ?>
              cols='140'
              name='lecture_course' 
              style="width: 998px;"><?=$this->ShowResultsOfSA()['lc']?></textarea>
     <label for='patents'><a>Патенты :</a></label>
     <textarea  
              class="textarea_class" 
              <?= $this->disabled_rep; ?>
              cols='140'
              name='patents' 
              id='patents'
              style="width: 998px;"><?=$this->ShowResultsOfSA()['patents']?></textarea>
     <label for='leadership'><a>Руководство соискателями ученой степени кандидата наук :</a></label>
     <textarea  
              class="textarea_class" 
              <?= $this->disabled_rep; ?>
              cols='140'
              name='leadership'
              id ='leadership'
              style="width: 998px;">
        <?=$this->ShowResultsOfSA()['lead']?></textarea>
     <label for='other'><a>Прочее :</a></label>
     <textarea data-autoresize 
              class="textarea_class" 
              <?= $this->disabled_rep; ?>
              name='other' 
              style="width: 998px;"><?=$this->ShowResultsOfSA()['other']?></textarea>
</fieldset>    

