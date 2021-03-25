const modal = document.getElementById("modal");
const output = document.querySelector(".output");

const fileSizeLimitInMegabytes = 100;
const
  fileSizeLimitInBytes = fileSizeLimitInMegabytes * 1048576;

function encodeHTML(s) {
  return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
}

function showCode(data) {
  data = encodeHTML(data);

  modal.style.display = "none";
  document.body.innerHTML += `
    <form id="clip" action="../includes/new" method="POST">
      <input type="url" name="input" value="${data}">
      <input type="submit">
    </form>`;
  document.getElementById("clip").submit();
}

function uploadRe($files) {
  // Begin file upload
  const request = new XMLHttpRequest();
  request.onreadystatechange = () => {
    if (request.readyState == XMLHttpRequest.DONE) {
      const data = (request.responseText);
      const link = JSON.parse(data).result;
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

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

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

      if (clickEnabled !== false) {
        $(".note").fadeOut(500);
      }

      if (files[i].size > fileSizeLimitInBytes) {
        Swal.fire(
          'Something\'s went wrong',
          `Your file is ${formatBytes(files[i].size)}, which is over the limit of ${fileSizeLimitInMegabytes}MB`,
          'error'
        ).then(() => {
          location.reload();
        })
        break;
      }
      uploadRe(files[i]);
    }
  });

  document.onpaste = function(event){
    const items = (event.clipboardData || event.originalEvent.clipboardData).items;
    for (const item of items) {
      if (item.kind === 'file') {
        const blob = item.getAsFile();
        uploadRe(blob);
      }
    }
  }

})(this);
