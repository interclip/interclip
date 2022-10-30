import { formatBytes } from "./lib/utils.js";

const filesSpan = document.getElementById("files");
const filesSizeSpan = document.getElementById("filesize");

interface FileSizeResponse {
  bytes: number;
  count: number;
}

const updateFileStats = async () => {
  if (!(filesSpan && filesSizeSpan)) {
    return;
  }

  // Retrieve data from the Interclip file API
  if (!localStorage.getItem("file_stat")) {
    fetch("https://interclip.app/includes/size.json")
      .then((res) => res.json())
      .then((res: FileSizeResponse) => {
        filesSpan.innerText = `${res.count}`;
        filesSizeSpan.innerText = `${formatBytes(res.bytes)}`;
        filesSizeSpan.setAttribute(
          "title",
          `Average file size: ${formatBytes(res.bytes / res.count)}`
        );

        const cache = {
          value: res,
          expires: (new Date().getTime() + 60 * 60 * 1000).toString(),
        };

        localStorage.setItem("file_stat", JSON.stringify(cache));
      });
  } else {
    // Retrieving API data from cache
    const fileStat = JSON.parse(localStorage.getItem("file_stat")!).value;
    filesSpan.innerText = `${fileStat.count}`;
    filesSizeSpan.innerText = `${formatBytes(fileStat.bytes)}`;
    filesSizeSpan.setAttribute(
      "title",
      `Average file size: ${formatBytes(fileStat.bytes / fileStat.count)}`
    );
  }
};

updateFileStats();
