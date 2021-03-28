<html lang="en">

<head>
    <title>Desktop | Interclip</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">¨
    
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="css/dark.css" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="./css/desktop.css">
</head>
<body>
    <div class="nav">
        <?php
        include_once "includes/menu.php";
        include_once "includes/getos.php";
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $user_os = getOS();

        ?>
    </div>
    <div class="center">
        <h1>Interclip on the desktop</h1>

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script src="js/desktop.js"></script>
        <script>
            download("<?php echo $user_os ?>")
        </script>

</body>

</html>