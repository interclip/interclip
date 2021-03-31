<span id="content"> </span>
<div class="title">
    <h1>Upload a file to Interclip</h1>
    <p class="note">Upload any file type that's under 100MB.</p>
    <div class="demo-droppable">
        <p>Drag files here or click to upload</p>
    </div>
            
    <div id="modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <p><img height="250" src="css/loading-spin.svg" alt=""></p>
    </div>

    </div>
    <div class="output"></div>
</div>

<script>
    clickEnabled = true;
    const csrfToken = "<?=$_SESSION['token']?>";
</script>
<script src="js/file.js"></script>