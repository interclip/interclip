addEventListener("fetch", (event) => {
  event.respondWith(
    new Response(
      JSON.stringify({
        status: "error",
        result: "this endpoint has been retired",
      }),
      {
        status: 410,
        headers: {
          "Access-Control-Allow-Origin": "*",
          "Cache-Control": "no-store",
          "Content-Type": "application/json;charset=UTF-8",
          "X-Content-Type-Options": "nosniff",
        },
      },
    ),
  );
});
