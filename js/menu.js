// Get the modal
const settingsModal = document.getElementById("settingsModal");
const darkModeToggle = document.querySelector("dark-mode-toggle");

// Get the button that opens the modal
const btn = document.getElementById("triggerModal");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("closeBtn")[0];

// Get the toggle checkbox
const colorSchemePreference = document.querySelector("#slct");
const toggle = document.querySelector("#hashanimation");
const betaToggle = document.querySelector("#betafeatures");
// Get the system value
const systemOpt = document.getElementById("systemOption");

const userAgent = navigator.userAgent.toLowerCase();

const isTablet = /(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/.test(
    userAgent
);

const isPhone = !isTablet
    ? "ontouchstart" in document.documentElement &&
    /mobi/i.test(navigator.userAgent)
    : false;

// When the user clicks the button, open the modal
btn.onclick = () => {
    settingsModal.style.display = "block";
};

colorSchemePreference.addEventListener("change", function () {
    if (this.value === "system") {
        const isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        darkModeToggle.mode = isDark ? "dark" : "light";
        localStorage.removeItem("dark-mode-toggle");
    } else {
        darkModeToggle.mode = this.value;
    }
});

toggle.addEventListener("change", function () {
    if (this.checked) {
        localStorage.removeItem("hideHashAnimation");
    } else {
        localStorage.setItem("hideHashAnimation", "true");
    }
});

betaToggle.addEventListener("change", function () {
    if (this.checked) {
        localStorage.removeItem("hideBetaMenu");
    } else {
        localStorage.setItem("hideBetaMenu", "true");
    }
    updateMenu();
});

/* Initialization */

systemOpt.innerText = systemOpt.innerText += ` ${isTablet ? "📱" : isPhone ? "📱" : "💻"
    }`;

colorSchemePreference.value = localStorage.getItem("dark-mode-toggle") || "system";

toggle.checked = !localStorage.getItem("hideHashAnimation");

betaToggle.checked = !localStorage.getItem("hideBetaMenu");

// When the user clicks on <span> (x), close the modal
span.onclick = () => {
    settingsModal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
document.onclick = (event) => {
    if (event.target == settingsModal) {
        settingsModal.style.display = "none";
    }
};

const updateMenu = () => {
    for (const li of document.querySelectorAll("#menu li")) {
        if (li?.children[0]?.children[0]?.classList.contains("beta")) {
            li.style.display = localStorage.getItem("hideBetaMenu") ? "none" : "block";
        }
    }
}

if (loggedIn) {
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    document.getElementById("load").innerText = `Load: ${performance.now()}ms`;

    /* Retrieve data from the Interclip file API */
    if (!localStorage.getItem("file_stat_expires") || parseInt(localStorage.getItem("file_stat_expires")) > Date.now()) {
        fetch("https://interclip.app/includes/size.json").then(res => res.json()).then(res => {
            document.getElementById("files").innerText = `Total files: ${res.count} (${formatBytes(res.bytes)})`;
            localStorage.setItem("file_stat_expires", new Date() + (60 * 60));
            localStorage.setItem("file_stat", JSON.stringify(res));
        });
    /* Retrieving API data from cache */
    } else {
        const fileStat = JSON.parse(localStorage.getItem("file_stat"));
        document.getElementById("files").innerText = `Total files: ${fileStat.count} (${formatBytes(fileStat.bytes)})`;
    }
}

updateMenu();
