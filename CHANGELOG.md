# v3.3.2 (Match 29, 2021)
## Features ✨ 
- Added metatags for better social previews https://github.com/aperta-principium/Interclip/commit/d586a8dc108ace7f6d7ab2b36f3090868d5dd255

## Bug fixes 🐛
- Fixed a bug where the favicon wouldn't display on Firefox https://github.com/aperta-principium/Interclip/commit/9aabc1b56aa208a9cc383e0f36023721c40d1c6e
- Fixed a bug with a non-working menu, which may still break on some local installations, but this will be resolved in the next version https://github.com/aperta-principium/Interclip/commit/3757872641005c8355543268cef6e4374a5ec208
- Made the Beta features shown by default https://github.com/aperta-principium/Interclip/commit/7a1bc7cc2fd6867925f105f7612ac62451869ed3

## Code 🎨
- Purge Cloudflare cache on every deployment https://github.com/aperta-principium/Interclip/commit/97b3a533f8d85a9c3243759b9365ad5e5541b7ae
- Deploy code also when a new release is published https://github.com/aperta-principium/Interclip/commit/29ecde7ef732e2d47c1734818ceb4f6c31b4b78d
- Delete the local copy of jQuery QR code https://github.com/aperta-principium/Interclip/commit/528b938498224abc45bc2fe3cf35a0b1542b2d0c

## Security 🔒
- Hide `.git/` for visitors https://github.com/aperta-principium/Interclip/commit/a0692b7a2282aa55171f8b61d8b498645dd79c4d

## Docs 📝
- Added [docs.interclip.app](https://docs.interclip.app/) https://github.com/aperta-principium/Interclip/commit/9551ed5b2c13528823d7782a6ac0e8a2e85106c7
- Add a CHANGELOG.md https://github.com/aperta-principium/Interclip/commit/1cef3387acfaa24f36451ea02e191459ebb73d64
- A ProTip in the README.md https://github.com/aperta-principium/Interclip/commit/0116f8378b6072d0483f67c67d21fa56118c9f01
- Changed logo position in the README.md https://github.com/aperta-principium/Interclip/commit/2015d75f076647ddf8cfe37af847bc38ab4f6695

# v3.3.1 (March 26, 2021)
## Features ✨ 
- Added support for a lot more browsers in image pasting https://github.com/aperta-principium/Interclip/commit/5b472757f3030217225e5f521d81bd9705824c1d
- Made the release display on the about page more reliable with server-side rendering instead of requesting the GitHub API https://github.com/aperta-principium/Interclip/commit/bc85a31a73113349a7e3242997626882f6a32313
- Make backend uploading more performant https://github.com/aperta-principium/Interclip/commit/623f6960ffc5224b06c515c10d471d6fc4e37db4
- Added the `curl` API https://github.com/aperta-principium/Interclip/commit/8918b5d7bc0c030d3004d75823838db16974f108
- Added a new about page https://github.com/aperta-principium/Interclip/commit/a2e038a4df8547da83f6b5392ee3a3b4b25063bc
- Dropped Nfogix analytics in favor of Cloudflare analytics, which really improved the load speed https://github.com/aperta-principium/Interclip/commit/9528be7ccbd948a4a28eb9a3ff6de106e11a14f4
## Bugs 🐛 
- Check if the pasted file is an image first before uploading https://github.com/aperta-principium/Interclip/commit/541bb2e9f646205389fae008080ca4fbcff91ee9
- Fixed QR code colors when there is a color theme collision https://github.com/aperta-principium/Interclip/commit/f4bd0fd72382ba283afcc1145ca2d5486b0eb0eb
- Fixed a bug where the UI would freak out when the user didn't put in a code https://github.com/aperta-principium/Interclip/commit/de741ca81d88a47c3e1a1bd9d3d2a70c335c1ca1
- New settings modal https://github.com/aperta-principium/Interclip/commit/7f4dea64247d0dd1d491d6379d2f4f7c8e412e93

## Code 🎨
- Indentation https://github.com/aperta-principium/Interclip/commit/4aa2f13ba74b57f73e9e57f4e224cd29d0372f4f https://github.com/aperta-principium/Interclip/commit/e1e1d0e92bd951554efcdf0d89cb7a096b3c7c15 https://github.com/aperta-principium/Interclip/commit/3111eca61f1f012a260e4126a5d48858551fba0b https://github.com/aperta-principium/Interclip/commit/271ec3dede2266483bcd491949c8f1695a7aceaa https://github.com/aperta-principium/Interclip/commit/2b5784d92e69d5a933b7bf3faf3fc7bfbf3227eb https://github.com/aperta-principium/Interclip/commit/9bbb94b835a5f0de7cb2a26e57985fb8ea0f08c4
- Shortened hex colors https://github.com/aperta-principium/Interclip/commit/fbbb2561afb5e2b0b7aedfdeabf6bd465efde4ec https://github.com/aperta-principium/Interclip/commit/64f248975b98b691cbe7f08b9e9495974829aa37
- Updated `.gitignore` https://github.com/aperta-principium/Interclip/commit/c83544819f1d2f83f59bc0eae6f33ba18e8f8516
- Removed a duplicate jQuery import https://github.com/aperta-principium/Interclip/commit/8b7645d9c9edd66d3e7b2b5fcc3e223f8cd2d4ca

### Accessibility ♿️
- Added lang attribute to <html> tags https://github.com/aperta-principium/Interclip/commit/0e65b824281eea9cb6cc9189728f5af5376eb80b
- Added `aria-label` attributes to buttons https://github.com/aperta-principium/Interclip/commit/245d64e82f62c77eb574492fd7171519eec25c8a


## Docs 📝 
- Update screenshots to be all on a PC screen and not mobile https://github.com/aperta-principium/Interclip/commit/e9dd4af0b20582c3a8d867dc366530b992557dc0
- Added file upload stats to the API doc https://github.com/aperta-principium/Interclip/commit/ebf7150b5eed84d0b79bc79982c46807dc88e7da
- Deleted the ToC from the README https://github.com/aperta-principium/Interclip/commit/c165cefff9cd587ba737b2c1735d9d7b7cbb4ed5
- Added documentation for the `curl` API https://github.com/aperta-principium/Interclip/commit/7f1d7474bde65e630ac93be616f585c473de7476
- Update CONTRIBUTING.md https://github.com/aperta-principium/Interclip/commit/10b8e165b475b2c2d201f55267b171133f9f0c2e
- Updated Code Of Conduct to Contributor Covenant, version 2.0 https://github.com/aperta-principium/Interclip/commit/35b977038a0f67d384e6f93f48641ec3916f7c25
- Added git as an installation prerequisite https://github.com/aperta-principium/Interclip/commit/4d06783c503a75b2563207b79a469a3db26a3b65
- Changed wording in the API doc https://github.com/aperta-principium/Interclip/commit/21fbd321b97a42e5fc8f0aec016e7854407e2d20

# v3.3.0 (February 25, 2021)
## Docs 📝
- Stripped some things from the privacy policy https://github.com/aperta-principium/Interclip/commit/77976e1b9c16b083d47fe11eecfa567b933b2170
- Updated some instructions in the CONTRIBUTING.md doc https://github.com/aperta-principium/Interclip/commit/3a45b2c377a5520a1336951298b86122fab807fe

## Code 🎨
- rewrote some PHP if syntax https://github.com/aperta-principium/Interclip/commit/3a45b2c377a5520a1336951298b86122fab807fe
- made the HTML in `new.php` a bit nicer to read https://github.com/aperta-principium/Interclip/commit/a034f8a1c239ad5ce2102c51a18507bf202dfb65

## Features ✨ 
- new file upload tool! https://github.com/aperta-principium/Interclip/commit/9961059c8936f291bb0aff434940ab4e34f5889e

# v3.2.6 (February 23, 2021)
## Bug fixes 🐛
- When the client IP reaches GitHub's API limit, don't even bother showing the info in the layout https://github.com/aperta-principium/Interclip/commit/0e83560448d80d20c7d29f155a20ac4db9d6dfa4

## Features ✨
- GitPodify Interclip so everyone can contribute! https://github.com/aperta-principium/Interclip/commit/b5a4db5425b9a44a901c98db339bfd409603b476
- Make a GitHub action that comments on every PR with a link to open that PR with GitPod https://github.com/aperta-principium/Interclip/commit/6cc2b45f16e73188671cb7b3f786a9239a6d5549
- Disable file uploads for the time being https://github.com/aperta-principium/Interclip/commit/24375bc568508328be16a0884cda1feadc64591f - see issue #59 
- Checked the clipped domain to prevent from saving unregistered domains https://github.com/aperta-principium/Interclip/commit/c5a0821bcc201e8e940b7a8aba4e3a2186882100
- Create a new backend for the new clip layout to show errors in a more fancy way https://github.com/aperta-principium/Interclip/commit/bf9768c6991a8edacf973674d00cbf92540553b2

## Code 🎨
- Add tests (with Pest) https://github.com/aperta-principium/Interclip/commit/ca94c2299f81e3a82fdde729fa057d06087d15df
- Remove the `cron.php` script https://github.com/aperta-principium/Interclip/commit/7c8b44342847448473cc3f751bfb6f5b3c9d7f79
- Make composer (in GitPod) also install all the dependencies https://github.com/aperta-principium/Interclip/commit/0ffd674e660f94add05bfa53da5a2ca568186e2a
- Shorten the link shortening function https://github.com/aperta-principium/Interclip/commit/7b00e7f37456d19e37fd15c5bf7515d06de0dd49

## Docs 📝
- Separate API.md from README.md https://github.com/aperta-principium/Interclip/commit/d4571a8ec35379549ae7c485a635d40a46aeacef https://github.com/aperta-principium/Interclip/commit/79bcfddf18b9fc01ebc93ff2d9ee78a2a6f34541
- Add some sample API responses to the doc https://github.com/aperta-principium/Interclip/commit/7ca0f25d48f31e767289ffcad4763601e6af8cf9
- Add an uptime badge to the README https://github.com/aperta-principium/Interclip/commit/bb0b0fa526b8f7228ccc001ec8d49551c4251cfd
- Change the position of the Interclip logo in the README https://github.com/aperta-principium/Interclip/commit/4d27818dd7f7bc09a696afa4459e262b2102f76b
- Remove the developer libraries section from the README https://github.com/aperta-principium/Interclip/commit/4d18a72c1e7991d5cdbb6f8f4d2a1ce41c1f2574
- Add a Code of Conduct and development setup doc https://github.com/aperta-principium/Interclip/commit/6d551951c476237f107d8cdb23874a24d020921e https://github.com/aperta-principium/Interclip/commit/6b51bd931dd505928dd03b34369c0546929fe297 https://github.com/aperta-principium/Interclip/commit/90dc18623a2525d6abc60c05e572444ad3751b2a https://github.com/aperta-principium/Interclip/commit/bcafa82f5207d0a1869091c510bdfdd45270d8e8 https://github.com/aperta-principium/Interclip/commit/a71917802512cf5c5ae2cfd2b9afd54900c20a64 https://github.com/aperta-principium/Interclip/commit/c41939dba6c27f4f08e2b60c75618f4b0dc6a3da 
- Add a link to the Firefox Interclip extension to the README https://github.com/aperta-principium/Interclip/commit/e93f708d5dc767ceac570bd4be620b9496fbb16f
- Add MySQL cron jobs to the Dev setup docs https://github.com/aperta-principium/Interclip/commit/5034ce3c842fec681fde6fa11266c276e962f090

# v3.2.5 (January 29, 2021)
## Features ✨
- get the id of the commit from which Interclip is currently deployed https://github.com/aperta-principium/Interclip/commit/3e24720ac107ca4f4046fb65fc49ca73b12ce92d

## Bug fixes 🐛
- API was acting a bit weird with URLs like about:config https://github.com/aperta-principium/Interclip/commit/6539b5a8fc73526568f40673f67d4f5e67abe57b
- menu page lacked menu styling https://github.com/aperta-principium/Interclip/commit/ae2c6d298ec5998ea11bef131c384029f9f3f363
- fix the URL of the favicon https://github.com/aperta-principium/Interclip/commit/52584c023b5c8d3933ad6190684d567af411acf5
- correctly import menu.css everywhere https://github.com/aperta-principium/Interclip/commit/7cc8f3df2ee8fa96d69849d4bdff5622e76bbd4b

## Code updates ♻️
-  Move menu CSS into a single file https://github.com/aperta-principium/Interclip/commit/84553c42a26cdc988ea60a3f05f4ac042894a762
- Also include rate-limiting in the about page https://github.com/aperta-principium/Interclip/commit/1747335bc4d8985ef87587cb03b0e1f5bfc8d5d7
- HTML rewrites https://github.com/aperta-principium/Interclip/commit/38502fb606736da908ac4a585562f00ef6b30e00 https://github.com/aperta-principium/Interclip/commit/9d449c2c815142bc58aae6a6d1418337fc5f05eb 
- jQuery => vanilla JS https://github.com/aperta-principium/Interclip/commit/d73784f85515fa5fa8adeba8d93f21c14a8a1dae https://github.com/aperta-principium/Interclip/commit/6f27cf41d55d5719798cfb7731b7582912f605e6
- JS updates https://github.com/aperta-principium/Interclip/commit/17ad60f8d1d0eaa16a51bcc57c44dbc521a8a57f https://github.com/aperta-principium/Interclip/commit/9a69e4e1db7ad8cbd4a469e57ef6730d7345a990
- fix a CSS anti-pattern https://github.com/aperta-principium/Interclip/commit/ce119c6358231947e8a0b7b835fcb6a7fb390c64
### File management 📦
- move icon files to `img/` https://github.com/aperta-principium/Interclip/commit/b84c81828123991462cd2891d5b1dedd78a85620 https://github.com/aperta-principium/Interclip/commit/9a69e4e1db7ad8cbd4a469e57ef6730d7345a990 
- format site manifest https://github.com/aperta-principium/Interclip/commit/e07842ef3f62931ea8d8b0b5500eb4e420fe4890

## Performance⚡️
- made the API 10x faster [tweet](https://twitter.com/filiptronicek/status/1354356645522976768)
- delete external sources that were unused or duplicate https://github.com/aperta-principium/Interclip/commit/103a2bb5b157b6c2ebd297623ed9c1d315ea250e


# v3.2.4 (January 19, 2021)
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

# v3.2.3 (January 17, 2021)
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

# v3.2.2 (January 16, 2021)
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


# v3.2.1 (January 15, 2021)
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

# v3.2.0 (November 23, 2020)
## Features ✨
- API now returns JSON cause it's the future (although it's about 20 years old)
## Bugs 🐛
- Made the page not so large in height with no content

# v3.1.1 (September 11, 2020)
## Features ✨
- CodeQL scanning Action
- Added automatic changing themes for QR codes

## Code 🎨
- Reformatted code style

# v3.1.0 (September 7, 2020)
## Features ✨
- GitHub Actions - linter
- CORS header
- Made README.md look better
- Interclip Desktop download page
## Code 🎨
- A lot of formatting
- A lot of indentation changes

# v3.0.6 (August 10, 2020)
## Features ✨
* Automatic QR code generation upon clip creation

# v3.0.5 (October 16, 2019)
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

# v3.0.4 (October 6, 2019)
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

# v3.0.3 (September 27, 2019)
## Features ✨
- Added a logo
- Better mobile menu

## Bugs 🐛
- Fixed a bug with the YouTube embed

# v3.0.2 (Semptember 27, 2019)
## Features ✨
- This version added everything about embedding
- Videos, Vimeo videos, Documents, Sound files and Images can all be embedded

# v3.0.1 (September 7, 2019)
## Features ✨
- In version 3.0.1 there has been added the ability to upload images and directly get a code.
- Also, when you then go to the receive link page and enter a code that contains a link to jpeg, jpg, gif or a png (this might get changed in the future) there is an image preview

# v3.0.0 (August 10, 2019)
## Features ✨
- Redesigned the whole thing
- Added random alphanumeric codes to every URL
