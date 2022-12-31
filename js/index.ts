import { validateForm } from "./lib/utils";
import "./file";
import { a11yClick } from "./menu";

const historyBtn = document.querySelector(
  "#content > .input-wrapper > svg"
) as HTMLOrSVGImageElement;
const selectDropdown = document.querySelector(
  ".history-select"
) as HTMLSelectElement;
const linkInput = document.getElementById("search-input") as HTMLInputElement;
const form = document.getElementById("content") as HTMLFormElement;

const clipForm = document.getElementById("content") as HTMLFormElement;

clipForm.onsubmit = validateForm;

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
}

historyBtn.onclick = (e) => {
  historyBtnAction(e);
};

historyBtn.onkeydown = (e) => {
  if (a11yClick(e)) {
    historyBtnAction(e);
  }
};

selectDropdown.onchange = (e: any) => {
  linkInput.value = e.target.value;
  form.submit();
};

const getRecentlyMadeClipEntries = () => {
  const initialValue = localStorage.getItem("recentClips");
  return initialValue ? JSON.parse(initialValue) : [];
};

const recentlyMade = getRecentlyMadeClipEntries();

if (recentlyMade.length > 0) {
  historyBtn.classList.remove("hidden");
  for (const clip of recentlyMade) {
    const option = document.createElement("option");
    option.value = clip;
    option.innerText = clip;
    selectDropdown.append(option);
  }
}

