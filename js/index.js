 $('input[type=text]').blur(function(){
   $('.placeholder').removeClass("placeholder--animate");
   $('.border').removeClass("border--animate");
   $('.lb').removeClass("lb--animate");
   checkInput();
 })
 .focus(function() {		
   $('.placeholder').addClass("placeholder--animate");
   $('.border').addClass("border--animate");
   $('.lb').addClass("lb--animate");
   checkInput();
 });

 function checkInput() {
   if ( $('input[type=text]').val()) {
       $('.placeholder').css('display', 'none');
    } else {
      $('.placeholder').css('display', 'visible');
    }
 }