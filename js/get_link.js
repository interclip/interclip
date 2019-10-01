var url = $("#urlLink").text();
function GetVimeoIDbyUrl(url) {
  var id = false;
  $.ajax({
    url: "https://vimeo.com/api/oembed.json?url=" + url,
    async: false,
    success: function(response) {
      if (response.video_id) {
        id = response.video_id;
      }
    }
  });
  return id;
} //

function valUrl() {
  if (url != undefined || url != "") {
    console.log("The URL: " + url);

    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/i;
    var match = url.match(regExp);
    if (match && match[2].length == 11) {
      console.log("YT");
      // Do anything for being valid
      // if need to change the url to embed url then use below line)
      $("#embed").html(
        '<iframe id="yt" width="100%" height="500" frameborder="0"> </iframe>'
      );

      $("#yt").attr(
        "src",
        "https://www.youtube.com/embed/" + match[2] + "?autoplay=0&rel=0"
      );
    } else {
      if (GetVimeoIDbyUrl(url)) {
        id = GetVimeoIDbyUrl(url);
        console.log("A Vimeo! " + id);
        $("#embed").html(
          '<iframe id="vimeoPlayer" src="" width="100%" height="500" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
        );
        $("#vimeoPlayer").attr(
          "src",
          "//player.vimeo.com/video/" +
            id +
            '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffff00"'
        );
      } else {
        $("#yt").hide();
      }
      // Do anything for not being valid
    }
  } else {
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
  return url.match(/\.(mp4|mkv)$/i) != null;
}

function documentCheck(url) {
  return (
    url.match(
      /\.(doc|docx|xls|xlsx|ppt|pptx|pdf|pages|eps|ps|ttf|xps|zip|rar)$/i
    ) != null
  );
}
function textCheck(url) {
  return url.match(/\.(txt)$/i) != null;
}
function musicCheck(url) {
  return url.match(/\.(mp3|waw|ogg)$/i) != null;
}
if (videoCheck(url)) {
  console.log("A video");
  $("#embed").html(
    '<video id="player" width="100%" playsinline controls><source id="videoSource"/></video>'
  );
  $("#embedSource").attr("src", url);
} else {
  $("#player").hide();
}
if (documentCheck(url)) {
  console.log("A document");
  $("#embed").html(
    "<iframe id='documentEmbed' src='' width='100%' height='623px' frameborder='0'>"
  );
  $("#documentEmbed").attr(
    "src",
    "https://drive.google.com/viewerng/viewer?embedded=true&url=" + url
  );
} else {
  $("#documentEmbed").hide();
}
if (musicCheck(url)) {
  $("#embed").html('<audio controls><source src="' + url + '"></audio> ');
}
if (textCheck(url)) {
  $("#embed").load(url);
}
testImage(url, record);
valUrl();
console.log("Hit end");
