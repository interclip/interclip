function placeHolder() {
  const hash = Math.random()
    .toString(36)
    .substr(2, 5);

  document.querySelector("#code").setAttribute("placeholder", hash);
}

function printOutText(text) {
  document.querySelector("#result").innerText = text;
}

setInterval(() => {
    placeHolder();
}, 500);

function submit() {
  document.forms.form.submit();
}

function validateForm() {
  const x = document.forms.form.code.value;
  if (x === "") {
    printOutText("Code must be filled out");
    return false;
  } else if (x.length !== 5) {
    printOutText("Code must be exactly the length of five");
    return false;
  } else {
    return true;
  }
}
placeHolder();
