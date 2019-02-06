$(document).ready(function(){

    $(window).load(function() {
        $('#slider').nivoSlider(
        {
        effect: 'fade', // Specify sets like: 'fold,fade,sliceDown'
        animSpeed: 500 // How long each slide will show
        
    }
        );
    });
    
    
    
    
});

$(document).ready(function(){
   $("#contact").submit(function(){
      var error = false;
      var name = $("#name").val();
      var email = $("#email").val();
      var subject = $("#subject").val();
      var message = $("#message").val();
      if($("#name").val()==""){
          var error = true;
          $("#error_name").fadeIn(500);
          $("#name").css("border","1px solid #ff7373");
          $("#error_name").css("color","#ff7373");
          $("#name").keyup(function(){
              $("#error_name").fadeOut(500);
              $("#name").css("border","1px solid #CCCCCC");
          });
          return false;
      }
      
      if($("#email").val()=="" || email.indexOf("@") == "-1" || email.indexOf(".") == "-1"){
          var error = true;
          $("#error_email").fadeIn(500);
          $("#email").css("border","1px solid #ff7373");
          $("#error_email").css("color","#ff7373");
          $("#email").keyup(function(){
              $("#error_email").fadeOut(500);
              $("#email").css("border","1px solid #CCCCCC");
          });
          return false;
      }
      
      if($("#subject").val()==""){
          var error = true;
          $("#error_subject").fadeIn(500);
          $("#subject").css("border","1px solid #ff7373");
          $("#error_subject").css("color","#ff7373");
          $("#subject").keyup(function(){
              $("#error_subject").fadeOut(500);
              $("#subject").css("border","1px solid #CCCCCC");
          });
          return false;
      }
      
      if($("#message").val()==""){
          var error = true;
          $("#error_message").fadeIn(500);
          $("#message").css("border","1px solid #ff7373");
          $("#error_message").css("color","#ff7373");
          $("#message").keyup(function(){
              $("#error_message").fadeOut(500);
              $("#subject").css("border","1px solid #CCCCCC");
          });
          return false;
      }
      
      
      if(error == false){
          $.ajax({
              type: "POST",
              url: "model/contact/class.contact.php",
              data: "name="+name+"&email="+email+"&subject="+subject+"&message="+message,
              success: function(data){
                  $("#contact").fadeOut(500);
                  $("#info2").html(data);
                  setTimeout(function(){
                         window.location = 'contact';
                        }, 2000);
              }
          });
          return false;
      }
       
   }); 
    
});


$(document).ready(function(){
   $("#logout").bind('click',function(){
      $.ajax({
                   type: "GET",
                   url: "model/logout/class.logout.php",
                   success: function(data){
                       $("#logout").fadeOut(500);
                       $("#user").fadeOut(500);
                       $("#info").html(data);
                       setTimeout(function(){
                         window.location = 'index.php';
                        }, 3000);
                   }
                });
      return false;
   });
});



$(document).ready(function(){
   $("#login").submit(function(){
       var user = $("#user").val();
       var password = $("#password").val();
       var remmember = $("#remmember").is(':checked');
       var error = false;
      
      
      
      
      if($("#user").val()=="" || $("#user").val()=="username" ){
          var error = true;
          $("#error_username").fadeIn(500);
          $("#user").keyup(function(){
              $("#error_username").fadeOut(500);
          });
          return false;
      }else{
          
      }
      
      if($("#password").val()=="" || $("#password").val()=="password"){
          var error = true;
          $("#error_password").fadeIn(500);
          $("#password").keyup(function(){
              $("#error_password").fadeOut(500);
          });
          return false;
      }else{
          
      }
      
      if(error == false){
          $("#loginuser").attr({"disabled" : "disabled","value" : "Loadiing.."});
          $("#user").attr({"disabled" : "disabled"});
          $("#password").attr({"disabled" : "disabled"});
          
          $.ajax({
                   type: "POST",
                   url: "model/login/class.login.php",
                   data: "user="+user+"&password="+password+"&remmember="+remmember,
                   success: function(data){
                       $("#title").fadeOut(500);
                       $("#login").fadeOut(500);
                       $("#info").html(data);
                       setTimeout(function(){
                         window.location = 'index.php';
                        }, 3000);
                        
                   }
                });
          return false;
      }
      
   }); 
    
});