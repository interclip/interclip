# Interclip
<a href="https://www.producthunt.com/posts/interclip?utm_source=badge-featured&utm_medium=badge&utm_souce=badge-interclip" target="_blank"><img src="https://api.producthunt.com/widgets/embed-image/v1/featured.svg?post_id=174002&theme=light" alt="Interclip - Easy peasy clipboard cross-platform sharing | Product Hunt Embed" style="width: 250px; height: 54px;" width="250px" height="54px" /></a>

![clipboard](https://img.shields.io/badge/clipboard-copied-orange) ![Clipboard](https://img.shields.io/github/repo-size/aperta-principium/Interclip) [![Maintainability](https://api.codeclimate.com/v1/badges/0a72c92a0a2da0c79ba5/maintainability)](https://codeclimate.com/github/aperta-principium/Interclip/maintainability)
![GitHub commits since latest release](https://img.shields.io/github/commits-since/aperta-principium/interclip/latest)
## Table of contents
1. [Intro](#intro)
2. [Built with](#dependencies)
3. [Developer tools](#dependencies)
4. [To-dos](#todo) 
5. [Tutorial](#howto)

<a name="intro"> </a>

## What is it?
A handy-dandy clipboard sharing tool to share URLs between devices and users

[Stable](http://uni.hys.cz)

[Development](http://unidev.hys.cz/)

![logo](https://github.com/filiptronicek/Interclip/raw/master/img/interclip_logo.png)

<a name="dependencies"> </a>
## Built with
- [aperta-principium/Embed](https://github.com/aperta-principium/Embed)
- [aperta-principium/Interclip-proxy](https://github.com/aperta-principium/Interclip-proxy)

<a name="libs"> </a>
## Developer libraries
* [Python wrapper](https://github.com/aperta-principium/Interclip-python)

## API
There is a very simple API for anybody to use. You can use it for storing URLs in your To-do app, or as a connection between order IDs and their URLs. The possibilities are endless
The API endpoint is at ```http://uni.hys.cz/includes/api```.


POST
`http://uni.hys.cz/includes/api`
body:
```json
{
  "url": "https://duckduckgo.com"
}
```
GET
`http://uni.hys.cz/includes/api?url=https://flutter.dev/`

<a name="todo"> </a>
## To-dos
- [ ] write a Ruby on Rails lib
- [ ] write a JavaScript lib
- [ ] fix text-overflow with long URLs on new.php
- [x] Text-file embed
- [x] an universal file uploader [StackOverflow](https://stackoverflow.com/questions/58153921/how-can-you-upload-to-catbox-using-javascript)

<a name="howto"> </a>
## How to clip?

Clipping is easy. Just click on the magnifying glass and paste in your link!

![how-to](https://github.com/filiptronicek/Interclip/raw/master/img/interclip-home.gif)

Then press Enter and through the magic of code here is the code to your link!

![code-preview](https://s.put.re/Jwmoc8BV.png)

## How to receive a clip?
Receiving a clip is even easier than clipping. Just get out your phone or another device and paste the code into the input.
![recieve](https://s.put.re/M1jfZZRs.png)

And now just click the button....

![get URL](https://s.put.re/ZsgUEznc.35.png)
