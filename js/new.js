const copyButton = document.getElementById("copyCode");
copyButton.onclick = () => {
    navigator.clipboard.writeText(code);
    swalFire({
        toast: true,
        position: 'top-end',
        icon: "success",
        timer: 1000,
        timerProgressBar: true,
        title: "Yay!",
        text: "Copied to clipboard"
    });
};

const update = (scheme) => {
    const style = window
        .getComputedStyle(document.documentElement)
        .getPropertyValue("content")
        .replace(/"/g, "");

    if (scheme === null || scheme === "system") {
        scheme = style;
    }

    document.getElementById("qrcode").innerHTML = "";
    const qr = QRCode.generateSVG(`https://interclip.app/${code}`, {
        ecclevel: "M",
        fillcolor: scheme === "light" ? "#157EFB" : "#151515",
        textcolor: "#e4e4e4",
        margin: 0,
        modulesize: 4
    });
    document.getElementById("qrcode").appendChild(qr);
};

window.matchMedia("(prefers-color-scheme: dark)").addListener(
    (e) => {
        const switcherScheme = localStorage.getItem("dark-mode-toggle");
        update(switcherScheme || e.matches ? "dark" : "light");
    }
);

const computedStyle = localStorage.getItem("dark-mode-toggle");
let options;

update(computedStyle);

const themeSwitchToggle = document.getElementById("slct");

themeSwitchToggle.addEventListener("change", () => {
    update(themeSwitchToggle.value);
});

const initialValue = localStorage.getItem("recentClips");

if (initialValue) {
    const recentlyMadeArray = JSON.parse(initialValue);
    if (!recentlyMadeArray.includes(url)) {
        recentlyMadeArray.push(url);
    }
    if (recentlyMadeArray.length > 6) {
        const reversedRecents = recentlyMadeArray.reverse();
        reversedRecents.pop();
        localStorage.setItem("recentClips", JSON.stringify(reversedRecents.reverse()));
    } else {
        localStorage.setItem("recentClips", JSON.stringify(recentlyMadeArray));
    }
} else {
    localStorage.setItem("recentClips", JSON.stringify([url]));
}