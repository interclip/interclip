import { exec } from "https://deno.land/x/execute/mod.ts";

const CLOUDFLARE_API_KEY = Deno.env.get("CLOUDFLARE_API_KEY");
const CLOUDFLARE_ZONE_ID = Deno.env.get("CLOUDFLARE_ZONE_ID");
const CLOUDFLARE_DOMAIN = "interclip.app";

// Get the list of changed files from git
exec("git diff HEAD~1 --name-only").then((output: string) => {
  // Split the output into an array of file names
  const changedFiles = output.split("\n").filter((f) => f);

  if (changedFiles.length === 0) {
    console.log("No files changed, skipping cache purge");
    return;
  }

  const skipFiles = [
    "README.md",
    "LICENSE",
    "package.json",
    "package-lock.json",
    "tsconfig.json",
    "scripts/",
    ".github/",
    ".gitignore",
    ".gitpod.yml",
    "gitpod.Dockerfile",
    "apache.conf",
    ".vscode/",
    ".git/",
  ];

  // Filter out files that we don't want to purge
  const filesToPurge = changedFiles.filter((f) => {
    return !skipFiles.some((g) => f.startsWith(g));
  });

  if (filesToPurge.some((f) => f.endsWith(".ts"))) {
    console.log("Typescript files changed, purging all js files");
    filesToPurge.push("*.js");
  }

  // Purge the cache for each changed file
  filesToPurge.forEach((file: string) => {
    const fileUrl = new URL(`https://${CLOUDFLARE_DOMAIN}`);
    fileUrl.pathname = file;

    console.log(`Purging cache for ${fileUrl}`);
    fetch(
      `https://api.cloudflare.com/client/v4/zones/${CLOUDFLARE_ZONE_ID}/purge_cache`,
      {
        headers: {
          Authorization: `Bearer ${CLOUDFLARE_API_KEY}`,
        },
        body: JSON.stringify({ files: [fileUrl] }),
        method: "DELETE",
      }
    ).then((response) => {
      if (response.ok) {
        console.log(`Purged cache for ${file}`);
      } else {
        console.error(
          `Failed to purge cache for ${file} (HTTP ${response.status})`
        );
      }
    });
  });
});

