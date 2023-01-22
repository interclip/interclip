const tabs = document.querySelectorAll("ul.nav-tabs > li");
const tabPanes = document.querySelectorAll(".tab-pane");

function tabClick(tabClickEvent) {
  for (const tab of tabs) {
    tab.classList.remove("active");
  }

  const clickedTab = tabClickEvent.currentTarget;
  clickedTab.classList.add("active");

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

