addEventListener("fetch", (event) => {
  event.respondWith(handleRequest(event.request));
});

/**
 * Respond to the request
 * @param {Request} request
 */

async function handleRequest(request) {
  const method = request.url.split("/")[3];
  const { searchParams } = new URL(request.url);
  const urlRegex = new RegExp(
    /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/
  );

  if (method.match(new RegExp(/get(\?code=.{5}|)/g))) {
    const code = searchParams.get("code");
    if (!code) {
      return new Response(
        JSON.stringify({ status: "error", result: "no code provided" }),
        {
          headers: {
            "content-type": "application/json;charset=UTF-8",
          },
          status: 404,
        }
      );
    }

    const value = await iclip.get(code);
    if (value === null) {
      return new Response(
        JSON.stringify({
          status: "error",
          result: "there is no URL assosiated with that code",
        }),
        {
          headers: {
            "content-type": "application/json;charset=UTF-8",
          },
          status: 404,
        }
      );
    } else {
      return new Response(
        JSON.stringify({
          status: "success",
          result: value,
        }),
        {
          headers: {
            "content-type": "application/json;charset=UTF-8",
          },
          status: 200,
        }
      );
    }
  } else if (method.match(new RegExp(/set\?url=.{3,256}/))) {
    const url = searchParams.get("url");
    if (!url) {
      return new Response(
        JSON.stringify({ status: "error", result: "no URL provided" }),
        {
          headers: {
            "content-type": "application/json;charset=UTF-8",
          },
          status: 404,
        }
      );
    } else if (!url.match(urlRegex)) {
      return new Response(
        JSON.stringify({ status: "error", result: "invalid URL specified" }),
        {
          headers: {
            "content-type": "application/json;charset=UTF-8",
          },
          status: 400,
        }
      );
    }

    const value = await iclip.get(url);

    const now = Date.now();
    const mSecondMonth = 30 * 24 * 60 * 60 * 1000;

    const expirationEpoch = parseInt(((now + mSecondMonth) / 1000).toFixed(0));

    const isInDB = async(cd) => await iclip.get(cd) !== null;

    var code = Math.random().toString(36).substr(2, 5);
    while (!isInDB(code)) {
        code = Math.random().toString(36).substr(2, 5); // random 5 letter (base 36) string
    }
    console.log(code);
    await iclip.put(code, url, { expiration: expirationEpoch });

    return new Response(JSON.stringify({ status: "success", result: code }), {
      headers: {
        "content-type": "application/json;charset=UTF-8",
      },
      status: 200,
    });
  } else {
    return new Response(JSON.stringify({ status: "error", result: "there is nothing to do, specify a method" }), {
      headers: {
        "content-type": "application/json;charset=UTF-8",
      },
      status: 400,
    });
  }
}
