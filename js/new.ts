import { a11yClick, alertUser } from "./menu";
import QRCode from "qrcode";
import { rememberClip } from "./lib/recentClips";

const copyButton = document.getElementById("copyCode") as HTMLButtonElement;

const copyCode = async () => {
  await navigator.clipboard.writeText(code);
  await alertUser({
    toast: true,
    position: "top-end",
    icon: "success",
    timer: 1000,
    timerProgressBar: true,
    title: "Yay!",
    text: "Copied to clipboard",
  });
};

copyButton.onkeydown = (e) => {
  if (a11yClick(e)) {
    e.preventDefault();
    copyCode();
  }
};

copyButton.onclick = async () => {
  copyCode();
};

declare global {
  const code: string;
}

const update = async (scheme: string | null) => {
  const style = window
    .getComputedStyle(document.documentElement)
    .getPropertyValue("content")
    .replace(/"/g, "");

  if (scheme === null || scheme === "system") {
    scheme = style;
  }

  const qrCodeContainer = document.getElementById("qrcode")!;

  qrCodeContainer.innerHTML = "";
  await QRCode.toCanvas(qrCodeContainer, `${appUrl}${root}/${code}`, {
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

rememberClip(code);
