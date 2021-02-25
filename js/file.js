const modal = document.getElementById("modal");
const output = document.querySelector(".output");

const fileSizeLimitInMegabytes = 100;
const
  fileSizeLimitInBytes = fileSizeLimitInMegabytes * 1048576;


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
      uploadRe(files[i]);
    }
  });

  const paste = async () => {
    try {
      const clipboardItems = await navigator.clipboard.read();
      for (const clipboardItem of clipboardItems) {
        for (const type of clipboardItem.types) {
          if (type !== "text/html") {
            const blob = await clipboardItem.getType(type);
            const newBlob = new File([blob], "clipboard.png", { type });

            if (newBlob.size > fileSizeLimitInBytes) {
              alert(`File size over ${fileSizeLimitInMegabytes} MB.`);
              location.reload();
              break;
            }
            uploadRe(newBlob);
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

function showCode(data) {
  modal.style.display = "none";
  document.body.innerHTML += `
    <form id="clip" action="../includes/new" method="POST">
      <input type="url" name="input" value="${data}">
      <input type="submit">
    </form>`;
  document.getElementById("clip").submit()
}
