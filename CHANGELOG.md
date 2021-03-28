# v3.2.4
## UI 💄
- Make the site-wide font match https://github.com/aperta-principium/Interclip/commit/5f533bc277a2cc2c651b789456be9a64a186f36c
- Removed the mobile.php page and make the new catch-all index.php more responsive https://github.com/aperta-principium/Interclip/commit/94ed8ad6b448dc3b21d1218e6e83d55bfc092432
- Make the URL input autofocus to reduce the time to clip yo' stuff https://github.com/aperta-principium/Interclip/commit/593daf82903cbc1ba38041fd58fb03aee07ac87e
- Make the code input field have a min length of 5 https://github.com/aperta-principium/Interclip/commit/9e00f1e14cd4af0f35ca8476e41d157dc98eee45

## Security 🔒
- Fix HTTP image https://github.com/aperta-principium/Interclip/commit/9c1e351a7c26b1888d48f8795d21da487655b912
- **Add rate limiting https://github.com/aperta-principium/Interclip/commit/29b5872d5b38093d004c57675d2ab66fbf10b8c5**

## Features ✨
- **Expiring clips - clips now last just a month to keep everything running well https://github.com/aperta-principium/Interclip/commit/f3d912888e9d45b2c1c4393e6e86477481db624fl**

## Documentation 📝
- Add client apps to README https://github.com/aperta-principium/Interclip/commit/64c64cfb325fc4ec8cbcc61e958b459b0a720873
- Add Firefox add-on link to the README https://github.com/aperta-principium/Interclip/commit/10a19b716c96ad94579817429dfa2f68144c6415

# v3.2.3
## Security 🔒
-  Update CORS header in API https://github.com/aperta-principium/Interclip/commit/d6ff3db191755810877facb12a5e54999ea9d9e1
- Better sanitizing of SQL injections https://github.com/aperta-principium/Interclip/commit/596535a9e9f3f23be06e9c0319cc133d3bc238d5
- Better RegEx for URLs https://github.com/aperta-principium/Interclip/commit/7a8ffc0e7ded44bd4d62990f1f4c7853c04b3c49

## UI 💄
- Fix the copy code button in file.php and index.php https://github.com/aperta-principium/Interclip/commit/b856ac2f117d924f1ba88d27c889f5f463300356
- Hide loading animation with CSS instead of JS https://github.com/aperta-principium/Interclip/commit/95438e80b6aa80505abf32ccf72fcb5dccdddfb9
- Fix dark mode bug in file.php https://github.com/aperta-principium/Interclip/commit/c004cd1dc15e4b0df1d7077bfdaaa56812e301fb
- Fix modal colors on file upload https://github.com/aperta-principium/Interclip/commit/53e5aacb0734049737498f426ce622f9673cfbad

## Documentation 📝
- Delete ToDos section https://github.com/aperta-principium/Interclip/commit/d7122cd74b2257efecea1c20229f7b4ed2ba21cb
- Update libraries section with new ones and update embed.js name https://github.com/aperta-principium/Interclip/commit/8c548c5982d93e79b2e6eb896ef7113f8eec648d

## Bugs 🐛
- Make it impossible to generate the same code twice https://github.com/aperta-principium/Interclip/commit/138bf2cf11369080b9c53ec8f159e80b6fef694c
- Upgrade to APIv2 on file upload code copy https://github.com/aperta-principium/Interclip/commit/9ff2994cd1664fb00ab52d87105f23a9b7971caa

# v3.2.2
## Bugs 🐛
* Fix styling issues with menus on the receive and about pages https://github.com/aperta-principium/Interclip/commit/1612902ffec651663104d5e6fc4371f047792395
## Code 🎨
* Make the button on the receive page smaller and better https://github.com/aperta-principium/Interclip/commit/2fbdc33197d8f3d40ac185ebf69fb3907132cccd https://github.com/aperta-principium/Interclip/commit/00ecd9f7f152ed6bf75f1a034a716af1b9211f3e
* Update input bubble #34 https://github.com/aperta-principium/Interclip/commit/d2d9c43e05702b6f94cfa987f8d82b87320b93fa
* Update the sitemap https://github.com/aperta-principium/Interclip/commit/cfa5c228eb1fc5215392f83d5ba559a794f358ea
* Delete some files that are not needed anymore https://github.com/aperta-principium/Interclip/commit/70224f6a8373dc29eff8f0c3776a789ae672a9e5 https://github.com/aperta-principium/Interclip/commit/b6158676717a663bedd05e93335ef441f178aa1a
* Some CSS formatting https://github.com/aperta-principium/Interclip/commit/6af15e26fd3bc66ca672090802d4a41cf69003f4 https://github.com/aperta-principium/Interclip/commit/3f8ed51ac00a0d6767b5eed2e965e0eaa08ceb17

## Docs
* Updated privacy policy - just removed some stuff because Interclip doesn't collect any data about the user https://github.com/aperta-principium/Interclip/commit/217dacbaa67c710addff86792ecd62e15f1b802c
* Fix more non-interclip.app references https://github.com/aperta-principium/Interclip/commit/c33a63d2b2d1c7e4b766a2e76c71ca216d284073 https://github.com/aperta-principium/Interclip/commit/94581d78939d948c6c559e22397e8026a3c6ba3c


# v3.2.1
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