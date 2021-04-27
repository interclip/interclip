const filesSpan = document.getElementById("files");
const filesSizeSpan = document.getElementById("filesize");

/* Retrieve data from the Interclip file API */
if (!localStorage.getItem("file_stat_expires") || parseInt(localStorage.getItem("file_stat_expires")) < Date.now()) {
    fetch("https://interclip.app/includes/size.json").then((res) => res.json()).then((res) => {
        filesSpan.innerText = `${res.count}`;
        filesSizeSpan.innerText = `${formatBytes(res.bytes)}`;
        filesSizeSpan.setAttribute("title", `Average file size: ${formatBytes(res.bytes / res.count)}`);
        localStorage.setItem("file_stat_expires", (new Date().getTime() + (60 * 60 * 1000)));
        localStorage.setItem("file_stat", JSON.stringify(res));
    });
} else {
    /* Retrieving API data from cache */
    const fileStat = JSON.parse(localStorage.getItem("file_stat"));
    filesSpan.innerText = `${fileStat.count}`;
    filesSizeSpan.innerText = `${formatBytes(fileStat.bytes)}`;
    filesSizeSpan.setAttribute("title", `Average file size: ${formatBytes(fileStat.bytes / fileStat.count)}`);
}