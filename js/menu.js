// Get the modal
const settingsModal = document.getElementById("settingsModal");
const darkModeToggle = document.querySelector("dark-mode-toggle");

// Get the button that opens the modal
const btn = document.getElementById("triggerModal");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("close")[0];

// Get the toggle checkbox
const checkbox = document.querySelector("#slct");
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

checkbox.addEventListener("change", function () {
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
    localStorage.setItem("betaMenu", "true");
  } else {
    localStorage.removeItem("betaMenu");
  }
});

/* Initialization */

systemOpt.innerText = systemOpt.innerText += ` ${
  isTablet ? "📱" : isPhone ? "📱" : "💻"
}`;

checkbox.value = localStorage.getItem("dark-mode-toggle") || "system";

toggle.checked = !localStorage.getItem("hideHashAnimation");

betaToggle.checked = !localStorage.getItem("betaMenu");

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
