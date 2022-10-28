let shownUrl = new URL(document.getElementById("urlLink").innerText);
const showUrl = (url) => {
  document.getElementById("urlLink").innerText = url;
};

showUrl(shownUrl.toString().replace(/https?:\/\//, ""));

const defaultFilesEndpoint = "files.interclip.app";

if (shownUrl.hostname === defaultFilesEndpoint) {
  showUrl(shownUrl.pathname.split("/").at(-1));
  document.getElementById("clipType").innerText = "file";
}
