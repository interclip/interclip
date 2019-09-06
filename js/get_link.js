var url = $("#urlLink").text();
function valUrl() {
  if (url != undefined || url != "") {
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
  }
}
valUrl();
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
function imageCheck(url) {
  return (
    url.match(/\.(jpeg|jpg|gif|png|bmp|svg|webp|tif|tiff|apng|ico|cur)$/) !=
    null
  );
}
if (imageCheck(url)) {
  $("#imgShow").attr("src", url);
} else {
  $("#imgShow").hide();
}
testImage(url, record);
