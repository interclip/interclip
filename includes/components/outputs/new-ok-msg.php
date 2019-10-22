<p><span id="url" class="url"><?php echo $url ?> </span><br><br> was saved as</p>
<h1><?php echo $usr ?></h1>
<div id="embed"> </div>
<div id="qrcode"></div>

<script>
    var options = {
    text: "<?php echo $url ?>",
    background: "#444444",
    foreground: "#e4e4e4",
    
    }
    $('#qrcode').qrcode(options);
</script>