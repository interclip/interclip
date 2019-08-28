<!DOCTYPE html>
<link rel="stylesheet" href="css/index.css">
<div id="endora" style="display: none">
  <endora>
</div>
<ul>
  <li><a class="active" href="#">Send</a></li>
  <li><a href="./recieve/">Recieve</a></li>
  <li style="float:right"><a href="about.html">About</a></li>
</ul>

<form name="urlform" id="content" onsubmit="return validateForm()" action="new.php" method="POST">

  <input type="text" name="input" class="input" id="search-input">
  <button type="reset" class="search" id="search-btn"></button>
</form>
<script src="js/index.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>