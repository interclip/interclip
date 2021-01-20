$.get(
  "https://api.github.com/repos/filiptronicek/Interclip/releases",
  {},
  function(data) {
    $("#version").append(
      `<a href='${data[0].html_url}'>${data[0].tag_name}</a>`
    );
  }
);
