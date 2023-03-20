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
                <div class="server-container" hidden>
                    Upload to
                    <select id="provider">
                        <option value="ipfs" disabled>IPFS (out of service)</option>
                        <option value="iclip" selected>Interclip Storage Server</option>
                    </select>
                </div>
                <?php if ($isStaff) : ?>

                <?php endif; ?>

                <div class="container--tabs">
                    <section class="row">
                        <ul class="nav nav-tabs">
                            <li class="active"><a title="Upload a file" href="#file">File</a></li>
                            <li class=""><a title="Upload a text snippet" href="#text">Text</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="file" class="tab-pane active">
                                <div class="droppable-area" id="dropzone">
                                    <button class="no-styles">Drag files here or click to upload</button>
                                </div>
                            </div>
                            <div id="text" class="tab-pane">
                                <span class="glyphicon glyphicon-fire glyphicon--home--feature two columns text-center"></span>
                                <span class="col-md-10">
                                    <form action="" id="gist">
                                        <textarea class="mono" name="data" placeholder="Eggs, chocolate, milk" required></textarea>
                                        <br />
                                        <input type="submit" value="Create clip from text">
                                    </form>
                                </span>
                            </div>
                        </div>
                    </section>
                </div>
                <dialog id="modal">
                    <progress id="progressBar" value="0" max="100"></progress>
                    <span class="percentage-bar">
                        0%
                    </span>
                    <span class="percentage-bar">
                        0%
                    </span>
                    <br />
                    <span id="file-progress">
                        0 B / 0 B
                    </span>
                    <div>
                        <span class="control-icon" title="Cancel upload" tabindex="0" id="cancel-upload">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                </dialog>
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