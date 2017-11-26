document.getElementById('letter').addEventListener('click', ajaxSearch);
    
function ajaxSearch(event){
    var scinceBlock = document.getElementById('scince'),
        scince2Block = document.getElementById('scince2');    
        
    var xhr = new XMLHttpRequest(),
        body = "index.php?url=ajax&param=search",
        nameLetter = "id="+event.target.id;
                
    xhr.open("POST", body, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
               
    xhr.onreadystatechange = function() {
        if ( xhr.readyState === 4 && xhr.status === 200 ) {
                
            scinceBlock.innerHTML = '';
            scince2Block.innerHTML = '';
                
            var myArr = JSON.parse(xhr.responseText),
                legScince = '<legend>Научные работники</legend>',
                legScince2 = '<legend>Наука 2</legend>';
              
            if( (myArr.scince.length !== 0)&&(myArr.scince2.length !== 0) ){
                scinceBlock.style.float = 'left';
                scince2Block.style.float = 'right';
            } else {
                scinceBlock.style.removeProperty("float");
                scince2Block.style.removeProperty("float");
            }
              
            if ( myArr.scince.length !== 0 ) {
                scinceBlock.innerHTML = '<fieldset>'+legScince+myArr.scince+'</fieldset>';
            }
                
            if ( myArr.scince2.length !== 0 ) {
                scince2Block.innerHTML = '<fieldset>'+legScince2+myArr.scince2+'</fieldset>';
            }
                
        }
    };
    xhr.send(nameLetter);
    event.preventDefault();
} 

