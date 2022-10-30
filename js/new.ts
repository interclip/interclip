import { alertUser } from "./menu";
import QRCode from "qrcode";

const copyButton = document.getElementById("copyCode") as HTMLButtonElement;

copyButton.onclick = () => {
  navigator.clipboard.writeText(code);
  alertUser({
    toast: true,
    position: "top-end",
    icon: "success",
    timer: 1000,
    timerProgressBar: true,
    title: "Yay!",
    text: "Copied to clipboard",
  });
};

declare global {
  const code: string;
  const url: string;
}

const update = async (scheme) => {
  const style = window
    .getComputedStyle(document.documentElement)
    .getPropertyValue("content")
    .replace(/"/g, "");

  if (scheme === null || scheme === "system") {
    scheme = style;
  }

  const qrCodeContainer = document.getElementById("qrcode")!;

  qrCodeContainer.innerHTML = "";
  await QRCode.toCanvas(qrCodeContainer, `https://interclip.app/${code}`, {
    errorCorrectionLevel: "M",
    color: {
      dark: scheme === "light" ? "#157EFB" : "#151515",
      light: "#e4e4e4",
    },
    margin: 0,
    width: 320,
    scale: 2,
  });
};

window.matchMedia("(prefers-color-scheme: dark)").addListener((e) => {
  const switcherScheme = localStorage.getItem("dark-mode-toggle");
  update(switcherScheme || e.matches ? "dark" : "light");
});

const computedStyle = localStorage.getItem("dark-mode-toggle");

update(computedStyle);

const themeSwitchToggle = document.getElementById("slct") as HTMLSelectElement;

themeSwitchToggle.addEventListener("change", () => {
  update(themeSwitchToggle.value);
});

const initialValue = localStorage.getItem("recentClips");

if (initialValue) {
  const recentlyMadeArray = JSON.parse(initialValue);
  if (!recentlyMadeArray.includes(url)) {
    recentlyMadeArray.push(url);
  }
  if (recentlyMadeArray.length > 6) {
    const reversedRecents = recentlyMadeArray.reverse();
    reversedRecents.pop();
    localStorage.setItem(
      "recentClips",
      JSON.stringify(reversedRecents.reverse())
    );
  } else {
    localStorage.setItem("recentClips", JSON.stringify(recentlyMadeArray));
  }
} else {
  localStorage.setItem("recentClips", JSON.stringify([url]));
}
