const CLOUDFLARE_API_KEY = Deno.env.get("CLOUDFLARE_API_KEY");
const CLOUDFLARE_ZONE_ID = Deno.env.get("CLOUDFLARE_ZONE_ID");

if (!CLOUDFLARE_API_KEY || !CLOUDFLARE_ZONE_ID) {
  throw new Error("Cloudflare cache purge credentials are not configured");
}

const response = await fetch(
  `https://api.cloudflare.com/client/v4/zones/${CLOUDFLARE_ZONE_ID}/purge_cache`,
  {
    headers: {
      Authorization: `Bearer ${CLOUDFLARE_API_KEY}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ purge_everything: true }),
    method: "POST",
  },
);

if (!response.ok) {
  throw new Error(
    `Failed to purge the Cloudflare cache (HTTP ${response.status}): ${await response.text()}`,
  );
}

console.log("Purged the Cloudflare zone cache");
