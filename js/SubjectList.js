var YEAR = self.location.pathname.match(/\d\d\d\d/);

function subjList(event){ 
           
          var url = location.href;
          var str = 'Views';
          var url_views;
          
         var form = document.getElementsByTagName('ul')[0],
                   formParent = form.parentNode,
                   el; 
         
          if(url.indexOf(str) === -1){
             url_views = '/'+YEAR+'/';
          }else{
             url_views = '../';
          }
          
          
          while(el = form.nextSibling) {
              
              formParent.removeChild(el);
          }
          
          function removeClass(classs){
           const elements = document.getElementsByClassName(classs);
           while (elements.length > 0) elements[0].remove(); 
        }
          removeClass('content_main');
          removeClass('content_other_user');
          removeClass('footer');
         
         
          
          var div_0 = document.createElement("div");
          div_0.className = 'content_main';
          div_0.style = 'min-height: 600px;';
          document.body.appendChild(div_0);
                    
          var div_1 = document.createElement("div");
          div_1.id = 'subjList';
          div_0.appendChild(div_1);
           
          var div_footer = document.createElement("div");
          div_footer.className = 'footer';
          document.body.appendChild(div_footer);
           
          var id = event.target.id;
           
       //    console.log(id);
         //  console.log(url);
        /*   
           $.ajax({
            dataType: 'json',
            type: "POST",
            url: "index.php?url=ajax",
            //url:  url_views + "controllers/SubjectListajaxController.php",
           // url: "/"+ YEAR + "/controllers/SubjectListajaxController.php",
            data: "id="+id, 
            cache: false,
            success: function(data){
                        $("#subjList").html("<fieldset>"+"<legend>"+data.id+
                                            "</legend>"+data.title+"</fieldset>");
   
                     }
            }); */
           
         
       }

