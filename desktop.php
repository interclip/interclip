 <!-- Compiled and minified CSS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link rel="stylesheet" href="./css/about.css">
 <link rel="stylesheet" href="./css/dark.css">
 <link rel="stylesheet" href="./css/desktop.css">

 <html>

 <head>
     <title>Desktop | Interclip</title>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

 </head>
 <div class="nav">
     <?php
        include "includes/menu.php";
        ?>
 </div>
 <br>

 <body class="">
     <div class="center">
         <h1>Interclip on the desktop</h1>

         Enjoy the simplicity of Interclip on locally on your computer!
         <div class="repocard">
             <h1>Interclip desktop</h1>
             <span id="commits"> </span>
             <br>
             <span id="stars"></span>
         </div>
        <div id="repo"></div>
     </div>
     <!-- Compiled and minified JavaScript -->
     <script src='https://cdn.jsdelivr.net/gh/jquery/jquery/dist/jquery.min.js'></script>
     <script src="https://cdn.jsdelivr.net/gh/darcyclarke/Repo.js/repo.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
     <script src="js/desktop.js"></script>



 </body>

 </html>