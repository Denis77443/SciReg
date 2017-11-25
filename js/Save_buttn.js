function Save_buttn(event, flag){
    var id = $(event.target).val();
    //alert('ID - '+ id+' FLAG - '+flag);
    
    if(flag == "1"){
          document.getElementById("save01").style.display = 'block'; 
    }
    
    if(flag == "2"){
        document.getElementById("save02").style.display = 'block'; 
    }
    
    
}