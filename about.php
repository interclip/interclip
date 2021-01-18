<html>

<head>
    <title>About | Interclip</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="./css/dark.css" media="(prefers-color-scheme: dark)">
    <link rel='stylesheet' type='text/css' href='//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' />

</head>
<div class="nav">
    <?php
    include "includes/menu.php";
    ?>
</div>
<br>

<body>

    <div class="center">
        <h1>About Interclip</h1>

        <div id="repoInfo">
            Read the <a href="https://aperta-principium.github.io/Interclip/">Documentation</a>
            <br />
            Current release: <span id="version"></span>
            <br />
            Latest commit: <span id="commit"></span>
            <br />
            Total clips: 
            <?php
                include_once "./includes/db.php";
                $conn = new mysqli($servername, $username, $password, $DBName);

                $sqlquery = "SELECT id FROM userurl ORDER BY ID DESC LIMIT 1";
                $result = $conn->query($sqlquery);
                while ($row = $result->fetch_assoc()) {
                    $count = $row['id'];
                    break;
                }
                echo $count;
            ?>
        </div>

        <p>From <a href="https://dev.to/filiptronicek/interclip-2hcn/"> the article</a> on <a href="https://dev.to">dev.to </a></p>
        <p>
            <p>Hi devs,
                I want to ask you a question: Do you use the same operating system and tools for everything you do at work (or home)? If your answer is yes, well, then there&#39;s nothing to worry about but if not, a tool I made may come in handy.
                You probably use a deployment tool for your work (if you do web development), or you have a documentation website which you want to read on your computer. For both of those things (and anything else URL related), you can use Interclip. </p>
            <h2 id="what-is-interclip-">What is Interclip?</h2>
            <p>Interclip is a modern solution for sharing URLs with yourself or someone else. You probably share URLs by just emailing them to yourself, right? Think about it. Isn&#39;t this just like... dumb? Filling your inbox and taking so much time is just nonsense. If you use Interclip, you can just paste a URL and get back a five-digit randomly generated code. This code you can then input on your other device (doesn&#39;t even have to be yours) and get the URL back.</p>
            <h2 id="don-t-know-where-to-host-a-file-interclip-can-also-help">Don&#39;t know where to host a file? Interclip can also help</h2>
            <p>Interclip is not just about URLs, but also about files. Any file smaller than 100 MB can be uploaded to Interclip and never be deleted. So you can host anything because there&#39;s also no file-type limitation. Also, every file is encrypted so no-one else will be able to see it.</p>
            <h2 id="api-of-course-we-do">API? Of course we do</h2>
            <p>There&#39;s also a very simple API for developers to use. Check the documentation for details.</p>
            <h2 id="open-source">Open Source</h2>
            <p>The entirety of Interclip is open source. I am working on it just by myself now so any contributions are welcome! And if you&#39;re not really into web-development or you don&#39;t have the time, a star on GitHub would be very appreciated. </p>
            <p>The APP: <a href="https://interclip.app">interclip.app</a>
                Github repo: <a href="https://github.com/aperta-principium/Interclip">aperta-principium/Interclip</a></p>
            <p>Thanks for reading. Happy developing!</p>

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
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        .madeBy {
            font-family: 'Open Sans', cursive;
            text-transform: uppercase;
            font-size: 1.2rem;
        }

        .madeBy>span>a {
            font-weight: 600;
            color: black;
            text-decoration: none;
        }

        .madeBy>span>a>img {
            border-radius: 250px;
            width: 25px;
        }
    </style>
    <div class='madeBy center'>
        made with <i class="icon ion-heart"></i> and a little bit of code by &nbsp;
        <span><a href="https://github.com/filiptronicek"><img src="https://github.com/filiptronicek.png"></a></span></div>


</body>

</html>
