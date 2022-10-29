import { formatBytes } from "./lib/utils";
import { alertUser } from "./menu";

const modal = document.getElementById("modal") as HTMLDivElement;
const output = document.querySelector(".output") as HTMLSpanElement;
const fact = document.getElementById("fact") as HTMLSpanElement;
const dropzone = document.getElementById("dropzone") as HTMLDivElement;
const storageProvider = document.getElementById("provider") as HTMLSelectElement;

const fileSizeLimitInMegabytes = 1000;
const fileSizeLimitInBytes = fileSizeLimitInMegabytes * 1_048_576;

function encodeHTML(s: string) {
  return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
}

const showError = (message: string) => {
  alertUser({
    title: "Something's went wrong",
    text: `Upload failed with HTTP ${message}`,
    icon: "error",
  }, true)
};

declare global {
  const csrfToken: string;
  let fileOver: boolean;
}

const submitClip = (url: string) => {
  const form = document.createElement("form");
  form.action = "/set";
  form.method = "POST";
  form.style.display = "none";

  const csrfInput = document.createElement("input");
  csrfInput.name = "token";
  csrfInput.value = csrfToken;

  const urlInput = document.createElement("input");
  urlInput.name = "input";
  urlInput.value = url;

  form.appendChild(csrfInput);
  form.appendChild(urlInput);

  document.body.appendChild(form);
  form.submit();
};

function showCode(data) {
  data = encodeHTML(data);

  modal.style.display = "none";
  submitClip(data);
}

const progressBar = document.getElementById("progressBar") as HTMLProgressElement;
const progressValue = document.getElementById("progressPercent") as HTMLSpanElement;

async function uploadFile(file: File) {
  const formData = new FormData();
  formData.append("uploaded_file", file);
  modal.style.display = "block";

  if (
    storageProvider &&
    storageProvider.value === "ipfs"
    // todo(ft): uncomment when IPFS works again || localStorage.getItem("fileServer") === "ipfs"
  ) {
    // The progress bar is not available for the fetch request, so hide the progress bar
    progressBar.style.display = "none";
    progressValue.innerText = "Uploading to IPFS....";

    let providerEndpoint = "https://ipfs.interclip.app";

    if (file.type.match(new RegExp("video/.{1,10}"))) {
      // If the file is a video, don't use Cloudflare, because it blocks it
      providerEndpoint = "https://ipfs.io";
    }

    // Use fetch to upload to IPFS
    fetch("https://ipfs.infura.io:5001/api/v0/add", {
      method: "post",
      body: formData,
    })
      .then((res) => {
        return res.json();
      })
      .then((obj) => {
        submitClip(
          `${providerEndpoint}/ipfs/${obj.Hash}?filename=${encodeURIComponent(
            file.name
          )}`
        );
      });
  } else {

    progressBar.style.visibility = "hidden";
    progressValue.innerText = "Preparing upload";

    // Get the AWS presigned URL
    const urlToFetch = new URL("https://iclip.vercel.app");
    urlToFetch.pathname = "api/uploadFile";
    urlToFetch.searchParams.set("name", file.name);
    urlToFetch.searchParams.set("type", file.type);
    const presignedRes = await fetch(urlToFetch);

    // Upload the file to the presigned URL
    if (!presignedRes.ok) {
      switch (presignedRes.status) {
        case 404:
          throw new showError("API Endpoint not found");
        case 500:
          throw new showError("Generic fail");
        case 503:
          throw new showError((await presignedRes.json()).result);
      }

      throw new showError(await presignedRes.text());
    }
    const { url, fields } = await presignedRes.json();
    const formData = new FormData();

    Object.entries({ ...fields, file }).forEach(([key, value]) => {
      formData.append(key, value);
    });

    const request = new XMLHttpRequest();
    request.upload.onprogress = (event) => {
      progressValue.innerText = `${Math.round(
        (event.loaded / event.total) * 100
      )}%`;
      progressBar.value = (event.loaded / event.total) * 100;
    };
    request.upload.onloadstart = () => progressBar.style.visibility = "visible";

    request.onreadystatechange = () => {
      if (request.readyState === XMLHttpRequest.DONE) {
        const { status } = request;
        if (status >= 400) {
          showError(`Upload failed with HTTP ${status}`);
        } else {
          const link = `https://files.interclip.app/${fields.key}`;
          showCode(link);
        }
      }
    };

    request.open("POST", url);
    request.send(formData);
  }

  (document.querySelector(".droppable-area") as HTMLDivElement).style.display = "none";
}

function triggerCallback(e, callback) {
  if (!callback || typeof callback !== "function") {
    return;
  }

  if (e.dataTransfer) {
    const urls = new Set<string>();

    // "Borrowed" from https://github.com/thinkverse/draggable/blob/ddb6d6ff23ef80fb60f80d4119586f4b0902e8f5/src/draggable.ts#L40-L46
    for (const item of e.dataTransfer.items) {
      if (["text/uri-list", "text/plain"].includes(item.type)) {
        urls.add(e.dataTransfer.getData("URL"));
        continue;
      }
    }

    const firstURL = urls.values().next().value;
    if ([...urls].length !== 0 && firstURL && firstURL !== "") {
      submitClip(firstURL);
      return;
    }
  }

  let files;
  if (e.dataTransfer) {
    files = e.dataTransfer.files;
  } else if (e.target) {
    files = e.target.files;
  }
  if (files.length > 0) {
    callback.call(null, files);
  }
}

window.fileOver = false;

function makeDroppable(ele, callback) {
  const input = document.createElement("input");
  input.setAttribute("type", "file");
  input.setAttribute("multiple", "false");
  input.style.display = "none";
  input.addEventListener("change", (e) => {
    triggerCallback(e, callback);
  });
  ele.appendChild(input);

  ele.addEventListener("dragover", (e) => {
    e.preventDefault();
    e.stopPropagation();
    fileOver = true;
    if (dropzone) {
      dropzone.classList.add("dragover");
    }
    ele.classList.add("dragover");
  });

  ele.addEventListener("dragleave", (e) => {
    e.preventDefault();
    e.stopPropagation();
    fileOver = false;
    setInterval(() => {
      if (!fileOver) {
        ele.classList.remove("dragover");
        if (dropzone) {
          dropzone.classList.remove("dragover");
        }
      }
    }, 100);
  });

  ele.addEventListener("drop", (e) => {
    e.preventDefault();
    e.stopPropagation();
    fileOver = false;
    ele.classList.remove("dragover");
    triggerCallback(e, callback);
  });

  if (dropzone) {
    dropzone.onclick = () => {
      input.value = "";
      input.click();
    };
  }
}

makeDroppable(document.body, (files: File[]) => {
  document.getElementById("content")!.style.display = "none";
  output.innerHTML = "";

  const [file] = files;

  if (file.type.indexOf("image/") === 0) {
    output.innerHTML += `<img width="200" src="${URL.createObjectURL(
      file
    )}" />`;
  }
  output.innerHTML += `<p>${file.name}</p>`;

  if (file.size > fileSizeLimitInBytes) {
    alertUser(
      {
        title: "Something's went wrong",
        text: `Your file is ${formatBytes(
          file.size
        )}, which is over the limit of ${fileSizeLimitInMegabytes}MB`,
        icon: "error",
      },
      true
    );
  }
  uploadFile(file);
});

document.onpaste = (event) => {
  const items = event.clipboardData?.items;
  if (!items) return;
  for (const item of items) {
    if (item.kind === "file") {
      const blob = item.getAsFile();
      uploadFile(blob!);
    }
  }
};

window.onload = () => {
  if (storageProvider) {
    const preferredDestination = localStorage.getItem("fileServer") || "iclip";
    const selectedOption = [...storageProvider.options]
      .find((e) => e.value === preferredDestination);

    if (!selectedOption) {
      return;
    }

    const optionAllowed = selectedOption.getAttribute("disabled") === null;

    if (optionAllowed) {
      storageProvider.value = preferredDestination;
    }
  }

  fetch("https://interclips.filiptronicek.workers.dev/")
    .then((res) => res.text())
    .then((res) => {
      fact.innerText = res;
    });
};
