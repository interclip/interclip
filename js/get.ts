const urlElement = document.getElementById("urlLink") as HTMLElement | null;

if (urlElement instanceof HTMLAnchorElement) {
  const shownUrl = new URL(urlElement.href);
  const showUrl = (url: string) => {
    urlElement.innerText = url;
  };

  // Remove the protocol and optionally a trailing slash
  showUrl(
    shownUrl
      .toString()
      .replace(/https?:\/\//, "")
      .replace(/\/$/, ""),
  );

  const defaultFilesEndpoint = "files.interclip.app";

  if (shownUrl.hostname === defaultFilesEndpoint) {
    const fileNamePart = shownUrl.pathname.split("/").at(-1);
    if (fileNamePart) {
      showUrl(decodeURIComponent(fileNamePart));
      document.getElementById("clipType")!.innerText = "file";
    }
  }
}
