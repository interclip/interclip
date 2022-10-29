const urlElement = document.getElementById("urlLink") as HTMLSpanElement;

let shownUrl = new URL(urlElement.innerText);
const showUrl = (url: string) => {
  urlElement.innerText = url;
};

showUrl(shownUrl.toString().replace(/https?:\/\//, ""));

const defaultFilesEndpoint = "files.interclip.app";

if (shownUrl.hostname === defaultFilesEndpoint) {
  const fileNamePart = shownUrl.pathname.split("/").at(-1);
  if (fileNamePart) {
    showUrl(fileNamePart);
    document.getElementById("clipType")!.innerText = "file";
  }
}
