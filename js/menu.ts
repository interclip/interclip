
import sweetAlert, { SweetAlertOptions } from "sweetalert2";
import * as DarkModeToggle from 'dark-mode-toggle';

// Get the modal
const settingsModal = document.getElementById("settingsModal")!;
const darkModeToggle = document.querySelector("dark-mode-toggle")!;

// Get the button that opens the modal
const btn = document.getElementById("triggerModal")!;

// The delete data button
const removeBtn = document.getElementById("removeData")!;

// Get the <span> element that closes the modal
const closeSettingsModalButton = document.getElementsByClassName("closeBtn")[0] as HTMLSpanElement;

// Get the toggle checkbox
const colorSchemePreference = document.getElementById("slct") as HTMLInputElement;
const toggle = document.querySelector("#hashanimation") as HTMLInputElement;
const betaToggle = document.querySelector("#betafeatures") as HTMLInputElement;
const fileServer = document.getElementById("file-slct") as HTMLSelectElement;

// Get the system value
const systemOpt = document.getElementById("systemOption") as HTMLInputElement;

const userAgent = navigator.userAgent.toLowerCase();

const isTablet =
  /(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/.test(
    userAgent
  );

declare global {
  const isAdmin: boolean;
  const loggedIn: boolean;
  const root: string;
  const version: string
}

const adminBar = isAdmin ? document.getElementById("adminbar") : null;
if (isAdmin) {
  if (!adminBar) {
    throw new DOMException("Admin bar not found");
  }

  adminBar.style.display = localStorage.getItem("adminBarVisible") || "flex";
}

const isPhone = !isTablet
  ? "ontouchstart" in document.documentElement &&
  /mobi/i.test(navigator.userAgent)
  : false;

function showPaintTimings() {
  if (!isAdmin) {
    return null;
  }

  const paintElement = document.getElementById("load");

  if (!paintElement) return;

  if (window.performance) {
    const { performance } = window;
    const performanceEntries = performance.getEntriesByType("paint");

    performanceEntries.forEach((performanceEntry) => {
      if (performanceEntry.name === "first-contentful-paint") {
        const paintTime = performanceEntry.startTime;
        paintElement.innerText = `Paint: ${Math.round(paintTime)}ms`;
      }
    });
  } else {
    paintElement.style.display = "none";
  }
}

export const alertUser = async (opts: SweetAlertOptions<any, any>, reload = false) => {
  await sweetAlert.fire(opts);
  if (reload) {
    location.reload();
  }
};

function a11yClick(event) {
  if (event.type === "keydown") {
    if (event.code === "Space" || event.code === "Enter") {
      return true;
    }
  } else {
    return false;
  }
}

// When the user clicks the button, open the modal
btn.onclick = () => {
  settingsModal.classList.add("settings-shown");
};

btn.onkeydown = (evt) => {
  if (a11yClick(evt)) {
    settingsModal.classList.toggle("settings-shown");
  }
};

if (colorSchemePreference) {
  colorSchemePreference.addEventListener("change", () => {

    if (!darkModeToggle) {
      throw new DOMException("Dark mode toggle does not exist");
    }

    if (colorSchemePreference.value === "system") {
      const isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
      darkModeToggle.mode = isDark ? "dark" : "light";
      localStorage.removeItem("dark-mode-toggle");
    } else {
      darkModeToggle.mode = colorSchemePreference.value as DarkModeToggle.ColorScheme;
      localStorage.setItem("dark-mode-toggle", colorSchemePreference.value);
    }
  });
}

if (fileServer) {
  fileServer.addEventListener("change", (e: any) => {
    localStorage.setItem("fileServer", e.target.value);
  });
}

toggle.addEventListener("change", () => {
  if (toggle.checked) {
    localStorage.removeItem("hideHashAnimation");
  } else {
    localStorage.setItem("hideHashAnimation", "true");
  }
});

betaToggle.addEventListener("change", () => {
  if (betaToggle.checked) {
    localStorage.removeItem("hideBetaMenu");
  } else {
    localStorage.setItem("hideBetaMenu", "true");
  }
  updateMenu();
});

/* Initialization */

systemOpt.innerText = systemOpt.innerText += ` ${isTablet ? "📱" : isPhone ? "📱" : "💻"
  }`;

const updateOptions = () => {
  colorSchemePreference.value =
    localStorage.getItem("dark-mode-toggle") || "system";
  toggle.checked = !localStorage.getItem("hideHashAnimation");
  fileServer.value = localStorage.getItem("fileServer") || "iclip";
  betaToggle.checked = !localStorage.getItem("hideBetaMenu");
};

// When the user clicks on the (x) <span>, close the modal
closeSettingsModalButton.onclick = () => {
  settingsModal.classList.remove("settings-shown");
};

// When the user clicks anywhere outside of the modal, close it
document.onclick = (event) => {
  if (event.target === settingsModal) {
    settingsModal.classList.remove("settings-shown");
  }
};

const updateMenu = () => {
  for (const li of document.querySelectorAll("#menu li")) {
    if (li?.children[0]?.children[0]?.classList.contains("beta")) {
      (li as HTMLDataListElement).style.display = localStorage.getItem("hideBetaMenu")
        ? "none"
        : "block";
    }
  }
};

console.log(
  `%c
    ▄▄▄ ▄▄    ▄ ▄▄▄▄▄▄▄ ▄▄▄▄▄▄▄ ▄▄▄▄▄▄   ▄▄▄▄▄▄▄ ▄▄▄     ▄▄▄ ▄▄▄▄▄▄▄ 
    █   █  █  █ █       █       █   ▄  █ █       █   █   █   █       █
    █   █   █▄█ █▄     ▄█    ▄▄▄█  █ █ █ █       █   █   █   █    ▄  █
    █   █       █ █   █ █   █▄▄▄█   █▄▄█▄█     ▄▄█   █   █   █   █▄█ █
    █   █  ▄    █ █   █ █    ▄▄▄█    ▄▄  █    █  █   █▄▄▄█   █    ▄▄▄█
    █   █ █ █   █ █   █ █   █▄▄▄█   █  █ █    █▄▄█       █   █   █    
    █▄▄▄█▄█  █▄▄█ █▄▄▄█ █▄▄▄▄▄▄▄█▄▄▄█  █▄█▄▄▄▄▄▄▄█▄▄▄▄▄▄▄█▄▄▄█▄▄▄█    %c ${version}`,
  "color:#FF9800",
  "color:green;font-weight:bold"
);

if (loggedIn && isAdmin) {
  const isStaging = adminBar!.classList.contains("staging");

  if (isStaging) {
    document.getElementById("branch-select")!.addEventListener("change", (e: any) => {
      const targetBranch = e.target.value.replace(/\s/g, "");
      if (targetBranch !== "-") {
        fetch(`${root}/staging/change-branch?branch=${targetBranch}`)
          .then((res) => res.json())
          .then(() => {
            location.reload();
          })
          .catch((err) => {
            alertUser({
              title: "Something's went wrong",
              text: err.toString(),
              icon: "error",
            });
          });
      }
    });
  }

  document.addEventListener("keydown", (e) => {
    // Let the user disable the modal by pressing Escape
    if (e.code === "Escape") {
      e.preventDefault();
      settingsModal.classList.remove("settings-shown");
    }

    if (e.shiftKey && e.code === "KeyB") {
      e.preventDefault();
      const displayStatus = adminBar!.style.display === "flex" ? "none" : "flex";

      let newColor = "#262626";
      if (displayStatus === "flex") {
        newColor = isStaging ? "#F15922" : "#588D6A";
      }

      document
        .querySelector("meta[name=theme-color]")!
        .setAttribute("content", newColor);
      adminBar!.style.display = displayStatus;
      localStorage.setItem("adminBarVisible", displayStatus);
    }
  });
} else if (loggedIn && !isAdmin) {
  document.addEventListener("keydown", (e) => {
    e.preventDefault();
    if (e.shiftKey && e.code === "KeyB") {
      alertUser({
        title: "Permission error",
        text: "Yikes! It seems you have to be an admin to view the admin bar. Want to be an admin? Tweet me @filiptronicek",
        icon: "error",
      });
    }
  });
}

removeBtn.onclick = () => {
  const isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  localStorage.clear();
  updateOptions();
  darkModeToggle.mode = isDark ? "dark" : "light";

  alertUser({
    icon: "success",
    title: "All local data has been deleted",
    showConfirmButton: false,
    timer: 1700,
  });
};

updateMenu();

window.addEventListener("load", () => {
  showPaintTimings();
  updateOptions(); // Rerender the option values
});
