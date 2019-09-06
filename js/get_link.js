var url = $('#urlLink').text();
function valUrl() {
    if (url != undefined || url != '') {
      var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
      var match = url.match(regExp);
      if (match && match[2].length == 11) {
        // Do anything for being valid
        // if need to change the url to embed url then use below line
        $('#ytplayerSide').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0&rel=0');
      } else {
       $('#ytplayerSide').hide()
  
        // Do anything for not being valid
      }
    }
  }
  valUrl()
  function imageCheck(url) {
    return(url.match(/\.(jpeg|jpg|gif|png|bmp|svg|webp|tif|tiff|apng|ico|cur)$/) != null);
}
if(imageCheck(url)) {
  $("#imgShow").attr('src', url);
} else {
  $("#imgShow").hide();
}
