<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Upload a file | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/file.css">
</head>

<body>
    <a class="skip-link" href="#maincontent">Skip to main</a>
    <?php
    include "includes/anti-csrf.php";
    store();
    include("includes/menu.php");
    ?>
    <main id="maincontent">
        <?php
        $fileUpload = true;
        ?>

        <?php if ($isStaff && $_ENV["FILES_TOKEN"]) : ?>
            <span style="display: none" id="filesToken">
                <?php echo $_ENV["FILES_TOKEN"] ?>
            </span>
        <?php endif; ?>

        <?php if ($fileUpload) : ?>
            <span id="content"> </span>
            <div class="title">
                <h1>Upload a file to Interclip</h1>
                <p class="note">Upload any file type that's under 1GB</p>
                <div class="server-container">
                    Upload to
                    <select id="provider">
                        <option value="ipfs" disabled>IPFS (out of service)</option>
                        <option value="iclip" selected>Interclip Storage Server</option>
                    </select>
                </div>
                <div class="droppable-area" id="dropzone">
                    <p>Drag files here or click to upload</p>
                </div>

                <div id="modal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span>
                            <progress id="progressBar" value="0" max="100"></progress>
                            <br>
                            <span id="progressPercent">
                                0%
                            </span>
                            <div id="fact">
                                Inter-clippin' good!
                            </div>
                        </span>
                    </div>

                </div>
                <div class="output"></div>
            </div>

            <script>
                const csrfToken = "<?= $_SESSION['token'] ?>";
            </script>
            <script src="<?php echo ROOT ?>/out/file.js"></script>
        <?php else : ?>
            <span id="content"> </span>
            <div class="title">
                <h1>Upload a file to Interclip</h1>
                <p class="note">
                    Sorry, file uploads to Interclip are disabled at this time, we're working on a fix. See
                    <a href="https://github.com/interclip/interclip/issues/59" target="_blank" rel="noreferrer nofollow">
                        #59
                    </a>
                    on why
                </p>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>