<div class="content_main" style="min-height: 600px;">
    <div style="text-align:center;padding-top:15px;padding-bottom:20px;">
        Всего зарегестрировано пользователей: <?=$this->number[0]['count']?>
    </div>
    <div class="caption">Поиск сотрудников по фамилии</div>
    <div style='text-align:center;' id="letter">
        <?=$outputletters;?>
    </div> 
    
    <div class="results" id="result">
        <div class="wrapper">
        <!--Вывод найденных пользователей-->
        <div id="scince" class="search"></div>
        <div id="scince2" class="search"></div>
        </div>
    </div>   
</div>

<script src="/js/search.js"></script>