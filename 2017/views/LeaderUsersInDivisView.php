<div class="content_main" style="min-height: 600px;">
    <fieldset>
        <legend><?=$this->title_lab['title']?></legend>
        <a href="index.php?url=userpage&id=<?=$this->head_labs['id']?>" style="font-weight: bold;">
            <?=$this->head_labs['name']?>
        </a><br>
        <?php foreach ($this->users as $key_user){ ?>
        <a href="index.php?url=userpage&id=<?=$key_user['user_id']?>"><?=$key_user['name']?></a><br>

        <?php } ?>
    </fieldset>
</div>