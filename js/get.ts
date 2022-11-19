const urlElement = document.getElementById("urlLink") as HTMLSpanElement;

let shownUrl = new URL(urlElement.innerText);
const showUrl = (url: string) => {
  urlElement.innerText = url;
};

// Remove the protocol and optionally a trailing slash
showUrl(
  shownUrl
    .toString()
    .replace(/https?:\/\//, "")
    .replace(/\/$/, "")
);

const defaultFilesEndpoint = "files.interclip.app";

if (shownUrl.hostname === defaultFilesEndpoint) {
  const fileNamePart = shownUrl.pathname.split("/").at(-1);
  if (fileNamePart) {
    showUrl(fileNamePart);
    document.getElementById("clipType")!.innerText = "file";
  }
}
