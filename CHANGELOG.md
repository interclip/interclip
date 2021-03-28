# v3.2.0
## Features ✨
- change most references to interclip.app https://github.com/aperta-principium/Interclip/commit/5066c4d3c921a0698618bd3dff06167c4e3bda3c  https://github.com/aperta-principium/Interclip/commit/39ea54348a8569375d3f70009abaca3e10c77101
- update the embed.js library https://github.com/aperta-principium/Interclip/commit/5bcc30ea19da7ad66803185db7bf8bbcf067cd6e
- add a number of total clips to the about page https://github.com/aperta-principium/Interclip/commit/ece0bf48b517dd0982e00d735e53add5b36fcd85
- make the privacy page less wide on desktop https://github.com/aperta-principium/Interclip/commit/c03a9a00182458bfe126841c7c1db5c696ea7b08
- change dark mode toggle styling https://github.com/aperta-principium/Interclip/commit/faaadcd5388a74b8e8acbbcb6fa3dd1aeb088a3c
- delete old hosting service ad tag https://github.com/aperta-principium/Interclip/commit/5f9452155104608d641d4de518b45ee6f946dd23
## Bugs 🐛
- fix an uploading bug in `file.js` https://github.com/aperta-principium/Interclip/commit/7c07ace51fe8ea59669ae6a41b1c683d7c9d5946
- fix file menu margin https://github.com/aperta-principium/Interclip/commit/ab5bd5563c7cb9b9b27deda8961227a01ee16e96

# v3.2.0
## Features ✨
- API now returns JSON cause it's the future (although it's about 20 years old)
## Bugs 🐛
- Made the page not so large in height with no content

# v3.1.1
## Features ✨
- CodeQL scanning Action
- Added automatic changing themes for QR codes

## Code 🎨
- Reformatted code style

# v3.1.0
## Features ✨
- GitHub Actions - linter
- CORS header
- Made README.md look better
- Interclip Desktop download page
## Code 🎨
- A lot of formatting
- A lot of indentation changes

# v3.0.6
## Features ✨
* Automatic QR code generation upon clip creation

# v3.0.5
## Features ✨
* We got an MIT license.
* We got error pages
* Added the whole process to the tutorial in README
* Added analytics
* Made the API better
* Added a GET API
* Added a proxy for the files
* Added a mobile version of the website (mobile.php)
* Added isProd() and whatPage() functions
* Got a site-wide dark mode (see #16 and #18)
* Moved favicon to the img subfolder 

# v3.0.4
## Features ✨
* Made a function which automatically redirects you to the desired URL of a code
* Added a smart menu, which houses the list of all pages and updates on a page-basis
* Added the ability to upload files using [put.re](https://put.re/)
* Added embedding also to the [new clip](https://github.com/aperta-principium/Interclip/blob/master/includes/new.php) section
* Moved the whole embedding process to [aperta-principium/Embed](https://github.com/aperta-principium/Embed)

## Code 🎨
* Redid the whole embed script
* Checked if the argument passed to api.php is an URL

## Bugs 🐛
* Fixed a bug with embedding files with uppercase file extensions 

# v3.0.3
## Features ✨
- Added a logo
- Better mobile menu

## Bugs 🐛
- Fixed a bug with the YouTube embed

# v3.0.2
## Features ✨
- This version added everything about embedding
- Videos, Vimeo videos, Documents, Sound files and Images can all be embedded

# v3.0.1
## Features ✨
- In version 3.0.1 there has been added the ability to upload images and directly get a code.
- Also, when you then go to the receive link page and enter a code that contains a link to jpeg, jpg, gif or a png (this might get changed in the future) there is an image preview

# v3.0.0
## Features ✨
- Redesigned the whole thing
- Added random alphanumeric codes to every URL