var url = $("#urlLink").text();
function valUrl() {
  if (url != undefined || url != "") {
    console.log("The URL: " + url);

    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[2].length == 11) {
      // Do anything for being valid
      // if need to change the url to embed url then use below line
      $("#ytplayerSide").attr(
        "src",
        "https://www.youtube.com/embed/" + match[2] + "?autoplay=0&rel=0"
      );
    } else {
      $("#ytplayerSide").hide();

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
  return url.match(/\.(docx|pdf|xls|xlsx|doc|ppt|pptx|docm|dotx|dotm|docb|xlt|xlm|pot|pps|pptm|pub|xps|)$/) != null;
}

if (videoCheck(url)) {
  console.log("A video");
  $("#videoSource").attr("src", url);
} else {
  $("#player").hide();
}
if(documentCheck(url)) {
  console.log("A document");
  $("#documentEmbed").attr("src", "https://view.officeapps.live.com/op/embed.aspx?src="+url);
} else {
  $("#documentEmbed").hide();
}

testImage(url, record);
valUrl();
console.log("Hit end")