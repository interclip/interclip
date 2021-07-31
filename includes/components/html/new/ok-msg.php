<?php
$createArray = createClip($url);
$usr = $createArray[0];
$err = $createArray[1];
?>

<?php if ($err === "") : ?>
    <p><span id="url" class="url"><?php echo $url ?> </span><br><br> was saved as</p>
    <div id="codeSection">
        <h1 class="usrCode"><?php echo $usr ?></h1>
        <svg fill="none" stroke="currentColor" id="copyCode" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
        </svg>
    </div>
    <div id="qrcode"></div>
<?php else : ?>
    <p><span id="url" class="url"><?php echo $url ?> </span><br></p>
    <h1 class="usrCode"><?php echo $err ?></h1>
<?php endif; ?>

<script>
    const url = "<?php echo $url ?>";
    const code = "<?php echo $usr ?>";
</script>
<script src="<?php echo ROOT ?>/js/new.js"></script>