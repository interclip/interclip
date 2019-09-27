$.get(
  "https://api.github.com/repos/filiptronicek/Interclip/releases",
  {},
  function(data) {
    $("#version").append(
      "<a href='" + data[0].html_url + "'>" + data[0].tag_name + "</a>"
    );
  }
);
$.get(
  "https://api.github.com/repos/filiptronicek/Interclip/commits",
  {},
  function(data) {
    $("#commit").append(
      "<a href='" +
        data[0].html_url +
        "'>" +
        data[0].commit.message +
        "</a> by <a href='" +
        data[0].author.html_url +
        "'>" +
        data[0].commit.author.name +
        "</a>"
    );
  }
);
