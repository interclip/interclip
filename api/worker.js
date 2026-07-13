const canonicalApiOrigin = "https://interclip.app";
const corsHeaders = {
  "Access-Control-Allow-Origin": "*",
  "Cache-Control": "no-store",
  "X-Content-Type-Options": "nosniff",
};

addEventListener("fetch", (event) => {
  event.respondWith(handleRequest(event.request));
});

const jsonResponse = (status, result, extraHeaders = {}) =>
  new Response(JSON.stringify({ status: "error", result }), {
    status,
    headers: {
      ...corsHeaders,
      "Content-Type": "application/json;charset=UTF-8",
      ...extraHeaders,
    },
  });

async function handleRequest(request) {
  const requestUrl = new URL(request.url);
  const action = requestUrl.pathname.split("/").filter(Boolean).at(-1);

  if (request.method === "OPTIONS") {
    return new Response(null, {
      status: 204,
      headers: {
        ...corsHeaders,
        "Access-Control-Allow-Headers": "Content-Type",
        "Access-Control-Allow-Methods": "GET, POST, OPTIONS",
      },
    });
  }

  if (action !== "get" && action !== "set") {
    return jsonResponse(404, "unknown API method");
  }

  if (action === "set" && request.method !== "POST") {
    return jsonResponse(405, "method not allowed", { Allow: "POST" });
  }

  if (action === "get" && !["GET", "POST"].includes(request.method)) {
    return jsonResponse(405, "method not allowed", { Allow: "GET, POST" });
  }

  const canonicalUrl = new URL(`/api/${action}`, canonicalApiOrigin);
  canonicalUrl.search = requestUrl.search;

  return new Response(null, {
    status: 307,
    headers: {
      ...corsHeaders,
      Location: canonicalUrl.toString(),
    },
  });
}
