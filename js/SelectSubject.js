function SelectSubject(event, flag, user_id){
    var id_subject = $(event.target).val();
    //alert('SELECT SUBJECT'+id_subject);
    console.log('ID subject = '+id_subject);
    console.log('ID FLAG = '+flag);
    console.log('ID user = '+user_id);
    
    
    $.ajax({
        type: "POST",
        url:"index.php?url=ajax&param=select_subject",
        data:{
            action: 'SelectSubject',
            id_subject: id_subject,
            flag: flag,
            slave_id: user_id},
            cache: false,
            success: function(responce){ $('div[id="subtheme'+flag+'"]').html(responce); }
        
    });
}

