<html>
<div id="endora" style="display: none">
    <endora>
</div>

<head>
    <meta content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff" http-equiv="Content-Type" />
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recieve link | Interclip</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link href="css/button.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/recieve.css">
    <link rel="stylesheet" href="css/dark.css">
</head>

<body>

    <?php
  include("includes/menu.php");
  ?>

    <div class="wrapper">
        <div class="form-container">
            <form id="inputform" name="form" onsubmit="return validateForm()" action="./includes/get" method="POST">

                <div class="full-screen">

                    <div class="input-wrap">
                        <p class="title">Recieve link</p>
                        <input type="text" name="user" maxlength="5" id="code" placeholder="h&amp;$h">
                        <br>

                        <br>

                        <br>
                        <a class="btn" onClick="submit()" data-title="Make the magic happen"></a>
                        <div id="result"></div>
                        <div id="modalpage">
                        </div>
                    </div>

                </div>

            </form>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://codepen.io/electerious/pen/rroqdL"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://codepen.io/electerious/pen/rroqdL.js"></script>
            <script type="text/javascript" src="js/recieve.js"></script>
        </div>

    </div>

</body>

</html>