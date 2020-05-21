const modal = document.getElementById("modal");
const output = document.querySelector(".output");
const copyBtn = document.querySelector(".copy");

fileSizeLimitInMegabytes = 100;
fileSizeLimitInBytes = fileSizeLimitInMegabytes * 1000000;

$(".copy").hide();

modal.style.display = "none";
(function(window) {
  function triggerCallback(e, callback) {
    if (!callback || typeof callback !== "function") {
      return;
    }
    let files;
    if (e.dataTransfer) {
      files = e.dataTransfer.files;
    } else if (e.target) {
      files = e.target.files;
    }
    callback.call(null, files);
  }
  function makeDroppable(ele, callback) {
    const input = document.createElement("input");
    input.setAttribute("type", "file");
    input.setAttribute("multiple", true);
    input.style.display = "none";
    input.addEventListener("change", function(e) {
      triggerCallback(e, callback);
    });
    ele.appendChild(input);

    ele.addEventListener("dragover", function(e) {
      e.preventDefault();
      e.stopPropagation();
      ele.classList.add("dragover");
    });

    ele.addEventListener("dragleave", function(e) {
      e.preventDefault();
      e.stopPropagation();
      ele.classList.remove("dragover");
    });

    ele.addEventListener("drop", function(e) {
      e.preventDefault();
      e.stopPropagation();
      ele.classList.remove("dragover");
      triggerCallback(e, callback);
    });

    ele.addEventListener("click", function() {
      input.value = null;
      if (clickEnabled) input.click();
    });
  }
  window.makeDroppable = makeDroppable;
})(this);
(function(window) {
  makeDroppable(window.document.querySelector(".demo-droppable"), function(
    files
  ) {
    console.log(files);
    output.innerHTML = "";
    for (let i = 0; i < files.length; i++) {
      if (files[i].type.indexOf("image/") === 0) {
        output.innerHTML +=
          `<img width="200" src="${URL.createObjectURL(files[i])}" />`;
      }
      output.innerHTML += "<p>" + files[i].name + "</p>";

      if(clickEnabled != false) {
        $(".note").fadeOut(500);
      }

      if(files[i].size > fileSizeLimitInBytes) {
        console.log(`File size over ${fileSizeLimitInMegabytes} MB.`);
        alert(`File size over ${fileSizeLimitInMegabytes} MB.`);
        location.reload();
        break;  
      }
      uploadRe(putRe(files[i]));

    }

  });
})(this);
function putRe(file) {
  return file;
}

function fallbackCopyTextToClipboard(text) {
  const textArea = document.createElement("textarea");
  textArea.value = text;
  textArea.style.position = "fixed"; //avoid scrolling to bottom
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    const successful = document.execCommand("copy");
    const msg = successful ? "successful" : "unsuccessful";
    console.log(`Fallback: Copying text command was ${msg}`);
  } catch (err) {
    console.error(`Fallback: Oops, unable to copy ${err}`);
  }

  document.body.removeChild(textArea);
}
function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text).then(
    function() {
      console.log("Async: Copying to clipboard was successful!");
      $(".copy").css("background", "#0db60d");
      setTimeout(function(){ $(".copy").css("background", "#2463ac");}, 3000);

    },
    function(err) {
      console.error(`Async: Could not copy text: ${err}`);
      $(".copy").css("background", "#f00");

    }
  );
}
function showCode(data) {
  $.get(
    `./includes/components/short-api.php?url=${encodeURI(
      data.data.link
    )}&keyword=${data.data.name}`,

    function(data, status) {
      console.log(`Data: ${data.shorturl} \nStatus: ${status}`);
      if (status == "success") {
        $.post(
          "includes/api.php",
          {
            url: data.shorturl
          },
          function(data, status) {
            console.log(`Data: ${data} \nStatus: ${status}`);
            if (status == "success") {
              $("#content").hide();
              $(".code").text(data);
              modal.style.display = "none";
              $(".copy").show();
              copyBtn.addEventListener("click", function() {
                copyTextToClipboard(data);
              });
            }
          }
        );
      }
    }
  );
}

function uploadRe($files) {
  console.log($files);
  // Begin file upload
  console.log("Uploading file to put.re..");

  // API Endpoint
  const apiUrl = "https://api.put.re/upload";

  const settings = {
    async: false,
    crossDomain: true,
    processData: false,
    contentType: false,
    type: "POST",
    url: apiUrl,
    mimeType: "multipart/form-data"
  };

  const formData = new FormData();
  formData.append("image", $files);
  settings.data = formData;

  modal.style.display = "block";

  $.ajax(settings).done(function(response) {
    const data = JSON.parse(response);
    //data.data.link = "https://iq.now.sh/s/" + data.data.name;
    console.log(data);
    showCode(data);
  });
  $(".demo-droppable").hide();
}
console.log("Done");