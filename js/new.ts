import { a11yClick, alertUser } from "./menu";
import { generate } from "./lib/qr"; 

generate("https://interclip.app/" + code).then((data) => {
  const qrCodeContainer = document.getElementById("qrcode")!;
  qrCodeContainer.innerHTML = data;
  console.log(data);
});

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
}

copyButton.onkeydown = (e) => {
  if (a11yClick(e)) {
    e.preventDefault();
    copyCode();
  }
}

copyButton.onclick = async () => {
  copyCode();
};

declare global {
  const code: string;
  const url: string;
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
