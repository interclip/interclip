const tabs = document.querySelectorAll("ul.nav-tabs > li");
const tabPanes = document.querySelectorAll(".tab-pane");

const handleTabNavigation = (e) => {
  const key = e.key;
  const currentTab = e.target.parentElement as HTMLUListElement;
  const tabsArray = Array.from(tabs) as HTMLUListElement[];

  const switchFocus = (tab: HTMLUListElement) => {
    if (tab === currentTab) return;
    tab.children[0].setAttribute("tabIndex", "0");
    tab.children[0].click();
    currentTab.children[0].setAttribute("tabIndex", "-1");
  };

  switch (key) {
    case "ArrowLeft": {
      e.preventDefault();
      const index = tabsArray.indexOf(currentTab);
      const prevTab = tabsArray[index - 1] || tabsArray[tabsArray.length - 1];
      switchFocus(prevTab);
      break;
    }
    case "ArrowRight": {
      e.preventDefault();
      const index = tabsArray.indexOf(currentTab);
      const nextTab = tabsArray[index + 1] || tabsArray[0];
      switchFocus(nextTab);
      break;
    }
    case "Home": {
      e.preventDefault();
      const firstTab = tabsArray[0];
      switchFocus(firstTab);
      break;
    }
    case "End": {
      e.preventDefault();
      const lastTab = tabsArray[tabsArray.length - 1];
      switchFocus(lastTab);
      break;
    }
  }
}

const tabClick = (tabClickEvent) => {
  for (const tab of tabs) {
    tab.classList.remove("active");
    tab.children[0].setAttribute("tabIndex", "-1");
    tab.removeEventListener("keydown", handleTabNavigation);
  }

  const clickedTab = tabClickEvent.currentTarget;
  clickedTab.classList.add("active");
  clickedTab.children[0].setAttribute("tabIndex", "0");
  clickedTab.addEventListener("keydown", handleTabNavigation);

  for (const tabPane of tabPanes) {
    tabPane.classList.remove("active");
  }

  const anchorReference = tabClickEvent.target;
  const activePaneId = anchorReference.getAttribute("href");
  const activePane = document.querySelector(activePaneId);

  activePane.classList.add("active");
}

export const initTabs = () => {
  for (const tab of tabs) {
    tab.addEventListener("click", tabClick);
  }

  const hash = new URL(location.href).hash;
  const tabLinks = [
    ...document.querySelectorAll("ul.nav-tabs > li > a"),
  ] as HTMLAnchorElement[];

  tabLinks.forEach((el) => {
    if (el.getAttribute("href") === hash) {
      el.click();
    }
  });

  // Clean the URL if the tab is the default one
  if (hash === tabLinks[0].getAttribute("href")) {
    location.replace(location.href.replace(hash, ""));
  }
};

