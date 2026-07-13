import { a11yClick, alertUser } from "./menu";
import QRCode from "qrcode";

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

const RECENT_CLIPS_KEY = "recentClips";
const RECENT_CLIP_TTL_MS = 2 * 24 * 60 * 60 * 1000;
const MAX_RECENT_CLIPS = 6;

interface RecentClip {
  code: string;
  expiresAt: number;
}

const isClipCode = (value: unknown): value is string =>
  typeof value === "string" && /^[A-Za-z0-9]{5}$/.test(value);

const getRecentlyMadeClipEntries = (): RecentClip[] => {
  try {
    localStorage.removeItem(RECENT_CLIPS_KEY);
  } catch {
    // Storage can be unavailable in privacy-restricted browser contexts.
  }

  try {
    const initialValue = sessionStorage.getItem(RECENT_CLIPS_KEY);
    if (!initialValue) return [];

    const parsedValue: unknown = JSON.parse(initialValue);
    if (!Array.isArray(parsedValue)) return [];

    const now = Date.now();
    const seenCodes = new Set<string>();
    return parsedValue
      .filter(
        (entry): entry is RecentClip =>
          typeof entry === "object" &&
          entry !== null &&
          isClipCode((entry as RecentClip).code) &&
          typeof (entry as RecentClip).expiresAt === "number" &&
          (entry as RecentClip).expiresAt > now &&
          (entry as RecentClip).expiresAt <= now + RECENT_CLIP_TTL_MS,
      )
      .filter((entry) => {
        if (seenCodes.has(entry.code)) return false;
        seenCodes.add(entry.code);
        return true;
      })
      .slice(0, MAX_RECENT_CLIPS);
  } catch {
    return [];
  }
};

const rememberClip = (clipCode: string) => {
  if (!isClipCode(clipCode)) return;

  const recentClips = getRecentlyMadeClipEntries().filter(
    (entry) => entry.code !== clipCode,
  );
  recentClips.unshift({
    code: clipCode,
    expiresAt: Date.now() + RECENT_CLIP_TTL_MS,
  });

  try {
    sessionStorage.setItem(
      RECENT_CLIPS_KEY,
      JSON.stringify(recentClips.slice(0, MAX_RECENT_CLIPS)),
    );
  } catch {
    // History is optional when session storage is unavailable.
  }
};

rememberClip(code);
