const downloadsUrl =
  "https://github.com/aperta-principium/Interclip-desktop/releases/latest/download/Interclip-";

function download(os) {
  fetch(
    "https://api.github.com/repos/aperta-principium/Interclip-desktop/releases"
  )
    .then((r) => r.json())
    .then((data) => {
      if (os === "Linux" || os === "Ubuntu") {
        location.href = `${
          downloadsUrl + data[0].tag_name.replace("v", "")
        }.AppImage`;
      } else if (os === "Windows") {
        location.href = `${downloadsUrl}install.exe`;
      } else if (os === "Macos") {
        location.href = `${
          downloadsUrl + data[0].tag_name.replace("v", "")
        }.dmg`;
      }
    });
}
