<img src="https://github.com/filiptronicek/Interclip/raw/HEAD/img/interclip_logo.png" alt="Interclip logo" width="150">

# Interclip

<a href="https://www.producthunt.com/posts/interclip?utm_source=badge-featured&utm_medium=badge&utm_souce=badge-interclip" target="_blank"><img src="https://api.producthunt.com/widgets/embed-image/v1/featured.svg?post_id=174002&theme=light" alt="Interclip - Easy peasy clipboard cross-platform sharing | Product Hunt Embed" style="width: 250px; height: 54px;" width="250px" height="54px" /></a>

![clipboard](https://img.shields.io/badge/clipboard-copied-orange) ![Clipboard](https://img.shields.io/github/repo-size/interclip/interclip) [![Maintainability](https://api.codeclimate.com/v1/badges/0a72c92a0a2da0c79ba5/maintainability)](https://codeclimate.com/github/interclip/interclip/maintainability)
![GitHub commits since latest release](https://img.shields.io/github/commits-since/interclip/interclip/latest)
[![GitPod badge](https://img.shields.io/badge/setup-automated-blue?logo=gitpod)](https://gitpod.io/#https://github.com/interclip/interclip)
[![Uptime](https://img.shields.io/endpoint?url=https%3A%2F%2Fraw.githubusercontent.com%2Faperta-principium%2Fstatus%2FHEAD%2Fapi%2Fhomepage%2Fuptime.json)](https://status.interclip.app)

---

<img width="1552" alt="Screenshot_2021-06-13_at_7 38 02_PM" src="https://user-images.githubusercontent.com/29888641/121811025-4700de80-cc63-11eb-9e4a-35bedea6066c.png">

<a name="intro"> </a>

## What is it

A handy-dandy clipboard sharing tool to share URLs between devices and users.

Check it out at [interclip.app](https://interclip.app).

<a name="clients"> </a>

## Client apps

Different apps for different platforms

- Interclip mobile [Repo](https://github.com/interclip/mobile), [App Store](https://apps.apple.com/cz/app/interclip/id1546777494), [Google Play](https://play.google.com/store/apps/details?id=com.filiptronicek.iclip)
- Interclip desktop [Repo](https://github.com/interclip/desktop), [Downloads](https://github.com/interclip/desktop/releases)
- Interclip browser extension [Repo](https://github.com/aperta-principium/iclip-ext), [Firefox addon](https://addons.mozilla.org/en-US/firefox/addon/interclip/), [Chrome extension](https://chrome.google.com/webstore/detail/interclip-extension/mpgjjbeepoonaaeaodiadghpnaadnngg)
- Interclip CLI (`npm i -g interclip`), [Repo](https://github.com/interclip/cli)

<a name="dependencies"> </a>

## Built with

- [aperta-principium/embed.js](https://github.com/aperta-principium/embed.js)
- [filiptronicek/iclip-external](https://github.com/filiptronicek/iclip-external)
- [aperta-principium/Interclip-proxy](https://github.com/aperta-principium/Interclip-proxy)

<a name="api"> </a>

## API

For using the Interclip API, see the [API Docs](https://github.com/interclip/interclip/wiki/API).
<a name="contribute"> </a>

## Contributing

For a code of conduct, see [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) and for setting up your environment (local or remote), see [CONTRIBUTING.md](CONTRIBUTING.md).

<a name="howto"> </a>

## How to clip

Clipping is easy. Just click on the magnifying glass and paste in your link!
Then press Enter and through the magic of code here is the code to your link!

![how-to](https://user-images.githubusercontent.com/29888641/131836539-60614c94-c9a3-4ad7-9fae-18ae8ba9db18.gif)

## How to receive a clip

**💡 ProTip: you can receive a clip even faster by scanning the QR code or going to `interclip.app/yourcode`**

Receiving a clip is even easier than clipping. Just get out your phone or another device and paste the code into the input.
![receive page](https://user-images.githubusercontent.com/29888641/131837516-c8db158b-6eeb-477f-96bd-98e7c4de3286.gif)

## Domain resolution troubles

For some users, we have observed that some ISPs block Interclip's traffic over the [interclip.app](https://interclip.app) domain. No idea why, but maybe because of the files hosted by the users? If you encounter such trouble please use the alternate domain, [iclip.fufik.eu](https://iclip.fufik.eu), which can also be accessed with HTTP if you plan to use ultra-old browsers with outdated TLS requirements.

_Note_: I know it's a bad idea to allow the access over HTTP, but if you use this alternate domain it should be a last resort. If you encounter any further issues, shoot me an email - [filip@interclip.app](mailto:filip@interclip.app).
