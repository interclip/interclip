function changePlaceholder() {
  const hash = Math.random().toString(36).slice(2, 7);
  const codeInput = document.querySelector("#code");

  if (codeInput) {
    codeInput.setAttribute("placeholder", hash);
  } else {
    throw new DOMException("Did not find input to place the placeholder into.");
  }
}

if (!localStorage.getItem("hideHashAnimation")) {
  setInterval(() => {
    changePlaceholder();
  }, 500);
  changePlaceholder();
}
