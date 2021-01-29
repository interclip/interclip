fetch(
  "https://api.github.com/repos/filiptronicek/Interclip/releases").then(resp => resp.json()).then(data => {
    document.querySelector("#version").innerHTML += `<a href='${data[0].html_url}'>${data[0].tag_name}</a>`;
  }
).catch(() => {
  document.querySelector("#release").style.display = "none";
});
