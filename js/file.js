const modal = document.getElementById("modal");
const output = document.querySelector(".output");
const copyBtn = document.querySelector(".copy");

const fileSizeLimitInMegabytes = 100;
const 
fileSizeLimitInBytes = fileSizeLimitInMegabytes * 1048576;

((window) => {
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
    input.setAttribute("multiple", false);
    input.style.display = "none";
    input.addEventListener("change", (e) => {
        triggerCallback(e, callback);
      });
    ele.appendChild(input);

    ele.addEventListener("dragover", (e) => {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.add("dragover");
      });

    ele.addEventListener("dragleave", (e) => {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.remove("dragover");
      });

    ele.addEventListener("drop", (e) => {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.remove("dragover");
        triggerCallback(e, callback);
      });

    ele.addEventListener("click", () => {
        input.value = null;
        if (clickEnabled)
          input.click();
      });
  }
  window.makeDroppable = makeDroppable;
})(this);
((window) => {
  makeDroppable(window.document.querySelector(".demo-droppable"), (files) => {
      $("#content").hide();
      output.innerHTML = "";
      for (let i = 0; i < files.length; i++) {
        if (files[i].type.indexOf("image/") === 0) {
          output.innerHTML += `<img width="200" src="${URL.createObjectURL(
            files[i]
          )}" />`;
        }
        output.innerHTML += "<p>" + files[i].name + "</p>";

        if (clickEnabled != false) {
          $(".note").fadeOut(500);
        }

        if (files[i].size > fileSizeLimitInBytes) {
          alert(`File size over ${fileSizeLimitInMegabytes} MB.`);
          location.reload();
          break;
        }
        uploadRe(putRe(files[i]));
      }
    });

const paste = async () => {
  try {
      const clipboardItems = await navigator.clipboard.read();
      for (const clipboardItem of clipboardItems) {
          for (const type of clipboardItem.types) {
              if (type !== "text/html") {
                console.log(type);
                const blob = await clipboardItem.getType(type);
                blob.name = "clipboard.png";
                console.log(blob.name);
                if (blob.size > fileSizeLimitInBytes) {
                  alert(`File size over ${fileSizeLimitInMegabytes} MB.`);
                  location.reload();
                  break;
                }
                uploadRe(blob);
              }
          }
      }
  } catch (err) {
      console.error(err.name, err.message);
  }
};

document.onpaste = () => {
    paste();
};

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
    () => {
      console.log("Async: Copying to clipboard was successful!");
      $(".copy").css("background", "#0db60d");
      setTimeout(() => {
          $(".copy").css("background", "#2463ac");
        }, 3000);
    },
    (err) => {
      console.error(`Async: Could not copy text: ${err}`);
      $(".copy").css("background", "#f00");
    }
  );
}
function showCode(data) {

  let status = "success";
  if (status == "success") {
    $.post(
      "includes/api.php",
      {
        url: data,
      },
      (data, status = "success") => {
        console.log(`Data: ${data.result} \nStatus: ${status}`);
        if (status === "success") {
          $(".code").text(data.result);
          modal.style.display = "none";
          $(".copy").show();
          copyBtn.addEventListener("click", () => {
            copyTextToClipboard(data.result);
          });
        }
      }
    );
  }
}

function uploadRe($files) {
  // Begin file upload
  const request = new XMLHttpRequest();
  request.onreadystatechange = () => {
    if (request.readyState == XMLHttpRequest.DONE) {
      const data = (request.responseText);
      const link = JSON.parse(data).result;
      //data.data.link = "https://iq.now.sh/s/" + data.data.name;
      showCode(link);
    }
  };
  // API Endpoint
  const apiUrl =
    "/upload/?api";

  const formData = new FormData();
  formData.append("uploaded_file", $files);

  request.open("POST", apiUrl);
  request.send(formData);

  modal.style.display = "block";
  $(".demo-droppable").hide();
}
