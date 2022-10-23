addEventListener("fetch", (event) => {
  event.respondWith(handleRequest(event.request));
});

/**
 * Respond to the request
 * @param {Request} request
 */
async function handleRequest() {
  const res = await fetch(url); // Fetch the text file
  const result = await res.text(); // Load the resulting text

  const messageArray = result.split("\n"); // Split the text lines to an array
  const randomItem =
    messageArray[Math.floor(Math.random() * messageArray.length)]; // Get a random item from the array

  return new Response(randomItem, {
    headers: { "Access-Control-Allow-Origin": "*" },
    status: 200,
  });
}
