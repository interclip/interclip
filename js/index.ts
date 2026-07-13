import "./file";
import { getRecentlyMadeClipEntries, isClipCode } from "./lib/recentClips";
import { a11yClick } from "./menu";

const historyBtn = document.querySelector(
  "#content > .input-wrapper > svg",
) as HTMLOrSVGImageElement;
const selectDropdown = document.querySelector(
  ".history-select",
) as HTMLSelectElement;

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
