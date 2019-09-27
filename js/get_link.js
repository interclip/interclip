var url = $("#urlLink").text();
function GetVimeoIDbyUrl(url) {
  var id = false;
  $.ajax({
    url: 'https://vimeo.com/api/oembed.json?url='+url,
    async: false,
    success: function(response) {
      if(response.video_id) {
        id = response.video_id;
      }
    }
  });
  return id;
}        //

function valUrl() {
  if (url != undefined || url != "") {
    console.log("The URL: " + url);

    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[2].length == 11) {
      // Do anything for being valid
      // if need to change the url to embed url then use below line
      $("#ytplayerSide").attr("src","https://www.youtube.com/embed/" + match[2] + "?autoplay=0&rel=0");
    } else {
      if(GetVimeoIDbyUrl(url)) {
        id = GetVimeoIDbyUrl(url);
        console.log("A Vimeo! " + id);
        $('#video').html('<iframe id="vimeoPlayer" src="" width="100%" height="500" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>')
        $("#vimeoPlayer").attr("src",'//player.vimeo.com/video/'+id+'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffff00"');
      } else {
        $("#ytplayerSide").hide();
      }
      // Do anything for not being valid
    }
  } else {
    $("#ytplayerSide").hide();
    $("#player").hide();
    $("#imgShow").hide();
    console.log("The url wasn't set");

    // Do anything for not being valid
  }
}
function testImage(url, callback, timeout) {
  timeout = timeout || 5000;
  var timedOut = false,
    timer;
  var img = new Image();
  img.onerror = img.onabort = function() {
    if (!timedOut) {
      clearTimeout(timer);
      callback(url, "error");
    }
  };
  img.onload = function() {
    if (!timedOut) {
      clearTimeout(timer);
      callback(url, "success");
    }
  };
  img.src = url;
  timer = setTimeout(function() {
    timedOut = true;
    callback(url, "timeout");
  }, timeout);
}
function record(url, result) {
  if (result == "success") {
    $("#imgShow").attr("src", url);
  } else {
    console.log("Error loading image");
    $("#imgShow").hide();
  }
}
function videoCheck(url) {
  return url.match(/\.(mp4|mkv)$/) != null;
}

function documentCheck(url) {
  return url.match(/\.(txt|doc|docx|xls|xlsx|ppt|pptx|pdf|pages|dxf|eps|ps|ttf|xps|zip|rar)$/) != null;
}
function musicCheck(url) {
  return url.match(/\.(mp3|waw|ogg)$/) != null;
}
if (videoCheck(url)) {
  console.log("A video");
  $("#video").html('<video id="player" width="100%" playsinline controls><source id="videoSource"/></video>')
  $("#videoSource").attr("src", url);
} else {
  $("#player").hide();
}
if(documentCheck(url)) {
  console.log("A document");
  $("#documentEmbed").attr("src", "https://drive.google.com/viewerng/viewer?embedded=true&url="+url);
} else {
  $("#documentEmbed").hide();
}
if(musicCheck(url)) {
  $("#music").html('<audio controls><source src="'+url+'"></audio> ');



}
testImage(url, record);
valUrl();
console.log("Hit end")