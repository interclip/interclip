 <!-- Compiled and minified CSS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link rel="stylesheet" href="./css/about.css">
 <link rel="stylesheet" href="css/dark.css">

 <html>

 <head>
     <title>About | Interclip</title>
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
         <h1>About Interclip</h1>

         <div id="repoInfo">
             Read the <a href="https://aperta-principium.github.io/Interclip/">Documentation </a>
             <br />
             Current release: <span id="version"></span>
             <br />
             Latest commit: <span id="commit"></span>

         </div>

         <p>From <a href="http://filiptronicek.me/interclip-3/"> the article</a> on <a href="http://filiptronicek.me">filiptronicek.me </a></p>
         <p>
             <p>
                 Soooo...
                 I was really excited for this release, mainly because a lot of people I talked to said it is a great
                 idea and that they would use it. The problem with storing the URL into a plain text file accessible to
                 everyone (besides f-ing security) is, that if for every user there was a text file (each just under
                 50B) would be a very bandwidth and storage hungry action. So I went through the hassle of doing all the
                 PHP-MySQL crazy $(&quot;hit&quot;) and made the database. Then I went through a couple of fullscreen
                 form and text designs on CodePen:
             </p>
             <ul>
                 <li><a href="https://codepen.io/filip_tronicek/pen/qeJjPz">When the user makes a query on a specific
                         code</a> </li>
                 <li><a href="https://codepen.io/filip_tronicek/pen/rXqwro">When the user adds a URL to the database</a>
                 </li>
                 <li><a href="https://codepen.io/filip_tronicek/pen/dxNdgN">Recieve link main page</a> </li>
                 <li><a href="https://codepen.io/MilanMilosev/pen/JdgRpB/">Main page - send link</a> </li>
             </ul>
             <p>After that I figured out that the code that is used to access the urls from the database should be 5
                 digit base 36 strings, so that makes about 60 466 176 combinations - and I want to make a cron script,
                 that deletes all the codes after a week in the next major release.</p>
             <p>
                 <img src="https://user-images.githubusercontent.com/29888641/62826686-faa86b80-bbbf-11e9-81db-c7e321eaf2e4.png" alt="image">
                 <img src="https://user-images.githubusercontent.com/29888641/62826693-127fef80-bbc0-11e9-95fe-300241e31397.png" alt="image">
                 <img src="https://user-images.githubusercontent.com/29888641/62826696-24619280-bbc0-11e9-81d1-e99c1c37e0d1.png" alt="image">
                 <img src="https://user-images.githubusercontent.com/29888641/62826699-36433580-bbc0-11e9-925b-00c7f0e4e8f4.png" alt="image">
                 <img src="https://user-images.githubusercontent.com/29888641/62826703-4e1ab980-bbc0-11e9-9bc1-eb102f8059fa.png" alt="image">
                 <img src="https://user-images.githubusercontent.com/29888641/62826710-6094f300-bbc0-11e9-8511-59d12efb8618.png" alt="image">
             </p>
         </p>
     </div>
     <!-- Compiled and minified JavaScript -->
     <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
     <script src="js/about.js"></script>



 </body>

 </html>