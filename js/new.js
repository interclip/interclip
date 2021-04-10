const shareButton = document.getElementById("shareBtn");

if(navigator.share) {
    shareButton.style.display = "block";
}

function share() {
    if (navigator.share) {
        navigator.share({
            url: url,
            title: 'Interclip',
            text: 'A URL shared with Interclip',
        })
            .then(() => console.log('Share was successful.'))
            .catch((error) => console.log('Sharing failed', error));
    } else {
        console.log(`Your system doesn't support sharing files.`);
    }
}
