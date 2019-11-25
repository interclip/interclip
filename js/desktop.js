$('#repo').repo({ user: 'aperta-principium', name: 'Interclip-desktop' });

$.get(
    "https://api.github.com/repos/aperta-principium/Interclip-desktop/commits",
    {},
    function(data) {
        console.log(data);
      $("#commits").append(
        "Latest commit: <a href='" +
          data[0].html_url +
          "'>" +
          data[0].sha.substring(0,7) +
          "</a> by <a href='" +
          data[0].author.html_url +
          "'>" +
          data[0].commit.author.name +
          "</a>"
      );
    }
  );
  
  $.get(
    "https://api.github.com/repos/aperta-principium/Interclip-desktop/stargazers",
    {},
    function(data) {
        console.log(data);
        $("#stars").append(
          "Stars: " + data.length
        );

    }
  );
  