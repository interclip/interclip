var span = document.getElementsByClassName("close")[0];
var modal = document.getElementById("myModal");

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  location.reload(false); 
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    location.reload(false); 
  }
};
