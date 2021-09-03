const desktopMenuItem = document.getElementById("desktopMenuItem");

let OSName = "Unknown OS";

if (navigator.appVersion.indexOf("Win") !== -1) OSName = "Windows";
if (navigator.appVersion.indexOf("Mac") !== -1) OSName = "MacOS";
if (navigator.appVersion.indexOf("X11") !== -1) OSName = "UNIX";
if (navigator.appVersion.indexOf("Linux") !== -1) OSName = "Linux";

OSName == "MacOS" || OSName == "Windows" || OSName == "Linux"
  ? (desktopMenuItem.style.display = "block")
  : (desktopMenuItem.style.display = "none");
