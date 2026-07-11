import "./file";
import { a11yClick } from "./menu";

const historyBtn = document.querySelector(
  "#content > .input-wrapper > svg",
) as HTMLOrSVGImageElement;
const selectDropdown = document.querySelector(
  ".history-select",
) as HTMLSelectElement;

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
    if (!Array.isArray(parsedValue)) {
      sessionStorage.removeItem(RECENT_CLIPS_KEY);
      return [];
    }

    const now = Date.now();
    const seenCodes = new Set<string>();
    const recentClips = parsedValue
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

    if (recentClips.length > 0) {
      sessionStorage.setItem(RECENT_CLIPS_KEY, JSON.stringify(recentClips));
    } else {
      sessionStorage.removeItem(RECENT_CLIPS_KEY);
    }

    return recentClips;
  } catch {
    try {
      sessionStorage.removeItem(RECENT_CLIPS_KEY);
    } catch {
      // Storage remains unavailable, so history is disabled for this page.
    }
    return [];
  }
};

window.addEventListener("click", (e) => {
  if (!selectDropdown.classList.contains("hidden")) {
    if (e.target !== selectDropdown && e.target !== historyBtn) {
      selectDropdown.classList.add("hidden");
      selectDropdown.blur();
    }
  }
});

const historyBtnAction = (e: Event) => {
  e.preventDefault();
  selectDropdown.classList.toggle("hidden");
  selectDropdown.focus();
};

historyBtn.onclick = (e) => {
  historyBtnAction(e);
};

historyBtn.onkeydown = (e) => {
  if (a11yClick(e)) {
    historyBtnAction(e);
  }
};

selectDropdown.onchange = () => {
  const selectedCode = selectDropdown.value;
  if (isClipCode(selectedCode)) {
    location.assign(`${root}/${encodeURIComponent(selectedCode)}`);
  }
};

const recentlyMade = getRecentlyMadeClipEntries();

if (recentlyMade.length > 0) {
  historyBtn.classList.remove("hidden");
  for (const clip of recentlyMade) {
    const option = document.createElement("option");
    option.value = clip.code;
    option.innerText = clip.code;
    selectDropdown.append(option);
  }
}
