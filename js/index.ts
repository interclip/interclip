import { validateForm } from "./lib/utils";

const historyBtn = document.querySelector("#content > .input-wrapper > svg") as HTMLOrSVGImageElement;
const selectDropdown = document.querySelector(".history-select") as HTMLSelectElement;
const linkInput = document.getElementById("search-input") as HTMLInputElement;
const form = document.getElementById("content") as HTMLFormElement;

const clipForm = document.getElementById("content") as HTMLFormElement;

clipForm.onsubmit = validateForm;

historyBtn.onclick = () => {
  selectDropdown.classList.toggle("hidden");
};

selectDropdown.onchange = (e: any) => {
  linkInput.value = e.target.value;
  form.submit();
};

const getEntries = () => {
  const initialValue = localStorage.getItem("recentClips");
  return initialValue ? JSON.parse(initialValue) : [];
};

const recentlyMade = getEntries();

if (recentlyMade.length > 0) {
  historyBtn.classList.remove("hidden");
  for (const clip of recentlyMade) {
    const option = document.createElement("option");
    option.value = clip;
    option.innerText = clip;
    selectDropdown.append(option);
  }
}
