<p><span id="url" class="url"><?php echo $url ?> </span><br><br> was saved as</p>
<h1><?php echo $usr ?></h1>
<div id="embed"> </div>
<div id="qrcode"></div>

<script>
    const style = window
        .getComputedStyle(document.documentElement)
        .getPropertyValue('content')
        .replace(/"/g, '')
    if (style == "" || style == "light") {
        var options = {
            text: "<?php echo $usr ?>",
            background: "#ff9800",
            foreground: "#000000",
        }
    } else if (style == "dark") {
        var options = {
            text: "<?php echo $usr ?>",
            background: "#444444",
            foreground: "#e4e4e4",
        }
    }

    $('#qrcode').qrcode(options);
</script>