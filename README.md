# Interclip
<a href="https://www.producthunt.com/posts/interclip?utm_source=badge-featured&utm_medium=badge&utm_souce=badge-interclip" target="_blank"><img src="https://api.producthunt.com/widgets/embed-image/v1/featured.svg?post_id=174002&theme=light" alt="Interclip - Easy peasy clipboard cross-platform sharing | Product Hunt Embed" style="width: 250px; height: 54px;" width="250px" height="54px" /></a>

![clipboard](https://img.shields.io/badge/clipboard-copied-orange) ![Clipboard](https://img.shields.io/github/repo-size/aperta-principium/Interclip) [![Maintainability](https://api.codeclimate.com/v1/badges/0a72c92a0a2da0c79ba5/maintainability)](https://codeclimate.com/github/aperta-principium/Interclip/maintainability)
![GitHub commits since latest release](https://img.shields.io/github/commits-since/aperta-principium/interclip/latest)
[![GitPod badge](https://img.shields.io/badge/setup-automated-blue?logo=gitpod)](https://gitpod.io/#https://github.com/aperta-principium/Interclip)

## Table of contents
1. [Intro](#intro)
2. [Built with](#dependencies)
3. [Contributing](#contribute)
4. [Tutorial](#howto)
<img src="https://github.com/filiptronicek/Interclip/raw/master/img/interclip_logo.png" alt="Interclip logo" width="150">

<a name="intro"> </a>

## What is it?
A handy-dandy clipboard sharing tool to share URLs between devices and users

[App](https://interclip.app)

<a name="clients"> </a>
## Client apps
Different apps for different platforms

- Interclip mobile [Repo](https://github.com/filiptronicek/iclip-mobile), [Google Play](https://play.google.com/store/apps/details?id=com.filiptronicek.iclip)
- Interclip desktop [Repo](https://github.com/aperta-principium/Interclip-desktop), [Downloads](https://github.com/aperta-principium/Interclip-desktop/releases)
- Interclip browser extension [Repo](https://github.com/aperta-principium/iclip-ext), [Firefox addon](https://addons.mozilla.org/en-US/firefox/addon/interclip/), [Chrome extension](https://chrome.google.com/webstore/detail/interclip-extension/mpgjjbeepoonaaeaodiadghpnaadnngg)

<a name="dependencies"> </a>
## Built with
- [aperta-principium/embed.js](https://github.com/aperta-principium/embed.js)
- [filiptronicek/iclip-external](https://github.com/filiptronicek/iclip-external)
- [aperta-principium/Interclip-proxy](https://github.com/aperta-principium/Interclip-proxy)

## API
There is a very simple API for anybody to use. Remember, that clips only last for one month! (as of [v3.2.4](https://github.com/aperta-principium/Interclip/releases/tag/v3.2.4))
### Create clips
POST
`https://interclip.app/includes/api`
body:
```json
{
  "url": "https://duckduckgo.com"
}
```
GET
`https://interclip.app/includes/api?url=https://flutter.dev/`

### Get a clip
POST
`https://interclip.app/includes/get-api`
body:
```json
{
  "code": "tasks"
}
```
GET
`https://interclip.app/includes/get-api?code=tasks`

<a name="contribute"> </a>
## Want to contribute?
For a code of conduct, see [CONTRIBUTING.md](CONTRIBUTING.md) and for setting up your environment (local or remote), see [DEVELOPMENT.md](DEVELOPMENT.md).

<a name="howto"> </a>
## How to clip?

Clipping is easy. Just click on the magnifying glass and paste in your link!

![how-to](https://github.com/aperta-principium/Interclip/raw/master/img/interclip-home.gif)

Then press Enter and through the magic of code here is the code to your link!

![code-preview](https://s.put.re/Jwmoc8BV.png)

## How to receive a clip?
Receiving a clip is even easier than clipping. Just get out your phone or another device and paste the code into the input.
![recieve](https://s.put.re/M1jfZZRs.png)

And now just click the button....

![get URL](https://s.put.re/ZsgUEznc.35.png)
