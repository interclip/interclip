const input = document.getElementById("search-input");
const searchBtn = document.getElementById("search-btn");

const expand = () => {
  searchBtn.classList.toggle("close");
  input.classList.toggle("square");
  if (input.hasAttribute("placeholder")) {
    input.blur(); // Remove focus
    input.removeAttribute("placeholder");
  } else {
    input.setAttribute("placeholder", "Paste your link here");
  }
};

searchBtn.addEventListener("click", expand);

window.onload = () => {
  expand();
}
