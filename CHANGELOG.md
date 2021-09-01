# v3.3.9 (April 25, 2021)
## Bug fixes 🐛
- Prevent undefined session token https://github.com/interclip/interclip/commit/3aca9ff7385c2d5b0f9f795589b69deecd37eca9
- Use the whole commit hash for the sentry release https://github.com/interclip/interclip/commit/df55fc61ddf9f3bfe915b9c3473cf335250f0e74
- Prevent an undefined user agent https://github.com/interclip/interclip/commit/8f9500278186fc30fde9fe7e587b17523a5af64a
- Fix duplicate URL on the new clip page https://github.com/interclip/interclip/commit/a4aec49ef704b7fcbb433898c6edd14c3233c99a
- Fix weird logout logic https://github.com/interclip/interclip/commit/8fcbc37c432dc822a9b527ab8170220ccd9ac11a
- Fix submenu hover https://github.com/interclip/interclip/commit/25f196a67257c029a21051656e68aad4f5c26d98
- Fix broken alt icons https://github.com/interclip/interclip/commit/74841e3210060263f437cf1d0caab1f2b600743c
- Fix mocking account login https://github.com/interclip/interclip/commit/ba44390f8e4c23fda7db70d8ff427e6ea69a76e4

## Features ✨ 
- Add Sentry performance monitoring https://github.com/interclip/interclip/commit/90cd064609fa3649dcc90b7e243e6db202ee6ad7
- Add the ability to log out from the admin bar https://github.com/interclip/interclip/commit/c23746f4da4ebc08caa8b25f4d6405a624021b62
- Add a staging admin bar https://github.com/interclip/interclip/commit/f52f2efe4300ca2b6f5e347365c364d9223c7fe4
- Give the link color in the admin bar more contrast https://github.com/interclip/interclip/commit/29c15eab16fcc66337711b1ee5fda3a329452482
- Make the receive button's text white in dark mode https://github.com/interclip/interclip/commit/febd9857f0bd7859e85b25c0502280d372dd61e4

## Performance ⚡ 
- Add HTTP pre-connects https://github.com/interclip/interclip/commit/66c632804e3b7168a49a5c4ee51c34c7631c8033 https://github.com/interclip/interclip/commit/2a77de5420b82f218266ecb2745c47c198d8624d
- Add a non-jQuery dependent QR code generation https://github.com/interclip/interclip/commit/0da2dc25fa659e3d34e7990fded736786011602a

## Code 🎨 
- Add GitPod prebuilds https://github.com/interclip/interclip/commit/75a848e552a6561444a49c36d92a9b5d03bb2cf8 https://github.com/interclip/interclip/commit/2efd144f90db978e91054c3884d2954c99bb99f0
- Delete unused code https://github.com/interclip/interclip/commit/e47e3384c372797f98f8528cc76743ca2805dfe7
### Refactors ♻️ 
- https://github.com/interclip/interclip/commit/7cf04464fc641876043729e8ce880741c5752f49
- https://github.com/interclip/interclip/commit/20511c73bc7dc6caf2e06aadf90ad9b10976272f
- https://github.com/interclip/interclip/commit/0eeb5eca7ed7c8888577089f60866d82c06dae24
- https://github.com/interclip/interclip/commit/dc7103b1e920750f4b2abe70e64e5f1eb7bc3cd6
- https://github.com/interclip/interclip/commit/f41f38504f1d2fcc57d878a5a80811c23834515b
- https://github.com/interclip/interclip/commit/6c165d551665a5b905205b92b610e6026ba7d04b
- https://github.com/interclip/interclip/commit/eb48078c5a93ccf8459b6dcb05a780080e72400b
- https://github.com/interclip/interclip/commit/4231995f764b79f237b5993b04dc994986bceff9
- https://github.com/interclip/interclip/commit/7990ead816bb12f4f5b521627189479bcc917065
- https://github.com/interclip/interclip/commit/3118f42875b75a46574f12d69ca77be597e4e098
- https://github.com/interclip/interclip/commit/16f0dc068db5b6cd6cfdbeb7d7319198d6786f2c
- https://github.com/interclip/interclip/commit/614e47dfac0e8c22e56b8405a43608808efdd42a

# v3.3.8 (April 20, 2021)
## Bug fixes 🐛 
- Disable echoing of `$url` where it is not needed https://github.com/interclip/interclip/commit/6564ad2882885e935e537270fe291e90a1e7623f 
- Add better `<HTML>` structure to the home page https://github.com/interclip/interclip/commit/3702fec2bf4eb9878e5a434dd7cf8cc396276793
- Fix an overflow issue on the receive page https://github.com/interclip/interclip/commit/797e5734d0243ec344b7022a30a01a46bab4220f
- Don't start multiple PHP sessions if one is already active https://github.com/interclip/interclip/commit/2efc793749e41e49af46cf6cefa5f5300c3d343c
- Prevent undefined `$release` https://github.com/interclip/interclip/commit/5aae7dae5a3ed109d92b632ac43bb1798d6c2007
- Prevent undefined `$user` https://github.com/interclip/interclip/commit/32e2fc484d181db0c0d159e2ce88a699ac72c50e
- Add a default value to `$isStaff` https://github.com/interclip/interclip/commit/6c1ccf955875f50634da36bf07644146703196c1
- Change the name of the env var `SERVER_NAME` to `DB_SERVER` because Apache didn't really like the name https://github.com/interclip/interclip/commit/59d4ac1cd2a4f662cf402cef8dc320758929e743

## Features ✨ 
- Add a server storage field to the admin bar https://github.com/interclip/interclip/commit/9322d42a4798c5d3b249995d535628fdc3987209
- Add a sum of all DB rows to the admin bar https://github.com/interclip/interclip/commit/d922774f83f7d8e7186be95bca3a54a91c39de9f
- Added a skip-link for better accessibility https://github.com/interclip/interclip/commit/9f82561a87ccc5d7b6e1d837144eb4ee95b82e3e
- **Added Sentry to monitor errors** https://github.com/interclip/interclip/commit/01c5d47aebefe138c1b074336d3f646ee2b57896
- Added a Lighthouse CI Action https://github.com/interclip/interclip/commit/cd32cde9e756c656775c12e26b6596d85254fc43

## Code 🎨 
- Refactored `desktop.js` to migrate jQuery get to native `window.fetch()` https://github.com/interclip/interclip/commit/8511e6b8cbf108bf96d2ec12a080c7edaf3d9dba
- Removed jQuery from more places https://github.com/interclip/interclip/commit/2d794894bcad110ac22df1bc75eaef7911ac35bb
- Disabled non-working embed.js for now https://github.com/interclip/interclip/commit/257af42d12b5a9b712330d28de6df145e8d3d6c2

## Docs 📝 
- Updated the sitemap https://github.com/interclip/interclip/commit/0650abd83019f4c1d1a705b588fe000fbb4f93ac
- Deleted the Google Play link from the README https://github.com/interclip/interclip/commit/43f68c4713b2c8d83aab4e1be0b0177f46979417

## Performance ⚡ 
- Make the LCP faster https://github.com/interclip/interclip/commit/9c0a6064f17813ea8e817f7a27bb7ea514a52399
- Disable the initial animation on mobile https://github.com/interclip/interclip/commit/bd0f99b475147ce0e5ba1bbb495fa62f3386f0d0

## Security 🔒 
- Add the Cross-Origin-Opener-Policy header https://github.com/interclip/interclip/commit/a20aabeb57aeb1057fc1e78b9367ce66449fb4ae

# v3.3.7 (April 17, 2021)
## Bug fixes 🐛 
- Typo in the admin bar: thanks, Grammarly! https://github.com/interclip/interclip/commit/0fd3d0f0a653dfcc260695e928f613e820c28137
- Fixed a domain resolution issue with some domains https://github.com/interclip/interclip/commit/04aca79340f5e777dea91fc570f949d65d9d9464
- Fix a bug with the saved color scheme not being loaded correctly https://github.com/interclip/interclip/commit/7909dc6930c8323f6633ffe2ad4cc7df729bcd50
- Round the paint time in the admin bar https://github.com/interclip/interclip/commit/3f71a4030ae0603830c07f5cf67a7608a4014b58

## Features ✨ 
- Hide some admin bar metrics on mobile https://github.com/interclip/interclip/commit/921d22bf6531be849cca0ddb9bdb4b0f7792a2a4 https://github.com/interclip/interclip/commit/f4ae0ffffa7ea6a9f8e1899c043eaffb2e2929dd https://github.com/interclip/interclip/commit/992918ef7cc6f655408e4600a564a8daa788f5ff https://github.com/interclip/interclip/commit/f0a9e72936a646a2c959d38399a6db4a93f6d34d
- Make the client metric more reliable - rename it to paint and use the Pain API https://github.com/interclip/interclip/commit/c11fc28ff5e7f3717723139ba32fb238e3d961ac

## Dependencies ⬆️
- Updated Pest PHP (thanks, Dependabot!) https://github.com/interclip/interclip/commit/e9ec2c4fd64bfe24464edfdbe838b4437457e259

## Docs 📝 
- Fix README.md images https://github.com/interclip/interclip/commit/bfd3a15829bc5c014b407a73c67f4792bc333caa https://github.com/interclip/interclip/commit/50cf5d38dfd12a6f2c38f5ba4e2050e7eb88fe05

## Code 🎨 
- Fix the Apache startup script for GitPod https://github.com/interclip/interclip/commit/946ff3f24c525f2856c6a55f44c64e91630da02d https://github.com/interclip/interclip/commit/9e03e1d57139afd64a3b0f945839d18d0c3ef3f6
- Make the `.gitignore` look awesome https://github.com/interclip/interclip/commit/6c3b85bcd6ad708396d30b9286dfbd8c168cbc60
- Delete some blank lines in the PHP https://github.com/interclip/interclip/commit/922b72d3685ae9a7a6a0ff672abaa628151fa301
- Delete some `.svg` animations that are not used anymore https://github.com/interclip/interclip/commit/d67d0c4a45e85b94ff6e2727f98bea2a2c67dbb3

## Performance ⚡️
- Improve the load by ~400ms by changing the CDN of `dark-mode-toggle` https://github.com/interclip/interclip/commit/0ff28cd4eb8a0ae88a5e85d7b2d04d948745aa60

# v3.3.6 (April 14, 2021)
## Features ✨ 
- Admin bar https://github.com/interclip/interclip/commit/751f798c0fd7dde573f922def5b20ff019fcea66
- Login with Auth0 (doesn't provide any features asides from the admin bar for staff) https://github.com/interclip/interclip/commit/751f798c0fd7dde573f922def5b20ff019fcea66
- Add a very nice ASCII art in the browser console https://github.com/interclip/interclip/commit/fc2f0fb428ec8cace24a092222901d8c7a7b3747

## Bugs 🐛 
- Fix a bug where ping request to files.interclip.app would fail https://github.com/interclip/interclip/commit/929f564af8c26d75f613c6e31aba2975584de47f
- Fix places with iclip.netlify.app, because that doesn't work anymore https://github.com/interclip/interclip/commit/a13939a88389b256621a23e8818d1716f8c26d1a
- Add CORS header to the size API https://github.com/interclip/interclip/commit/22945309a2e655e26667c6a0fee943d0d3ba2524

## Code 🎨 
- Make GitPod ignore MySQL port when it opens https://github.com/interclip/interclip/commit/12ccf9094ec890036e0e6bad96cea865b2749298
- Change the GitPod deploy Action message https://github.com/interclip/interclip/commit/f77a24d9e50f0a777d081376c2b9f5938a1dc928
- Delete a very old CSS file reference https://github.com/interclip/interclip/commit/e1d0e64bc144e48b2ee182143ffb3b40754a47d6

## Docs 📝 
- Fix a broken Interclip logo in the README https://github.com/interclip/interclip/commit/36680e9f76af2d24a85b757c8db581b22b78c6af

## Screenshots 📷 
The new admin bar
![image](https://user-images.githubusercontent.com/29888641/114776334-a67d6380-9d72-11eb-8c84-281f6079bbb1.png)

The browser console ASCII art
![image](https://user-images.githubusercontent.com/29888641/114776656-01af5600-9d73-11eb-88d7-0625e1655f28.png)

# v3.3.5 (April 9, 2021)
## Bugs 🐛
- Made the <kbd>BETA</kbd> items in the menu not flash so much https://github.com/interclip/interclip/commit/46446cda74bb129897daf0775773cec0e0a4acee
- Vertically align avatar on the about page https://github.com/interclip/interclip/commit/cad075a59f9e65b73e43e595f208004a601340a8
- Fix weird scrolling in the settings modal on mobile viewports https://github.com/interclip/interclip/commit/f7f79fd56f37994b76a99dbd3ef9ee3116747c1c
- Fixed a bug where the cog icon was under the content on the receive page https://github.com/interclip/interclip/commit/5a97da7f3da6a048321c7b1b30a5c87fad7c850e
- Fixed the placeholder color on the receive page https://github.com/interclip/interclip/commit/9b2455179a53e514b4f3f48a8ccf45f3526243fa
- Fixed a bug with a cursor stuck in the magnifying glass on the home page https://github.com/interclip/interclip/commit/ef7f93eeb09b59159dcf1cfd0ed19c270d060bd0

## Features ✨ 
- Added a progress bar in the file uploading screen instead of a weird loading screen https://github.com/interclip/interclip/commit/c728167b434a2ca13c47f12ada1c4d1db77c5a98

## Code 🎨
- Small indentation of CSS https://github.com/interclip/interclip/commit/4960f84528c210ae6c6dbe3a326f4988409920ef
- Added some magical JS semicolons https://github.com/interclip/interclip/commit/c9309250272317894303dbfbed52dd6bab91185b
- Moved the header code to its own file https://github.com/interclip/interclip/commit/5610d6a3811f37ca67bb3e0036b0594419a0736f
- Removed references to `db.php` https://github.com/interclip/interclip/commit/2ed9e6217939d5ead260803eb4b3e63831278bbf

## Security 🔒 
- Reject requests to the `.env` file https://github.com/interclip/interclip/commit/469f70bc80e10e337b2bd94c78b646e967241ea1
- Set an X-Frame-Options header https://github.com/interclip/interclip/commit/52b6e1405849663e9ea75e27d85bf078fb59a221

## Docs 📝 
- Add a link back to GitPod in `CONTRIBUTING.md` https://github.com/interclip/interclip/commit/ea7078928652e00f639165cc1db18c6cf300a1ff


# v3.3.4 (April 1, 2021)
## Features ✨
- Added security.txt, because it's awesome! Check it out: https://securitytxt.org/ https://github.com/interclip/interclip/commit/d686c00f58233dc4628675303a85e97187c4af55
- Made the cursor a pointer when hovering over the file upload box https://github.com/interclip/interclip/commit/7c6954e4eff0d855cf98286b05d4bff3e57a7f5c
- Made setup a lot easier using a single .env file https://github.com/interclip/interclip/commit/3c0b377ddf24d876209e5b6ba6bc454f7a34e4fd
- File uploading now reports errors in popups https://github.com/interclip/interclip/commit/161acd3f13ef14af1572101f492eba3739495e8b

## Bug fixes 🐛
- Fixed `/customcode`, which returned a 404 https://github.com/interclip/interclip/commit/3048805fb605efc8e0c0e598d59ac903b013a62f
- Fixed the file upload error message https://github.com/interclip/interclip/commit/199cb846e8f45be45659e13fe91dff9d0dbd1932
- Fix undefined anti-CSRF token on the homepage preventing file upload to succeed https://github.com/interclip/interclip/commit/1b2c81d957cdb407d2900b1d67a9ce2481d0921f
- Fix a bug with headings being not centered, [GL#8](https://gitlab.com/filiptronicek/Interclip/-/issues/8) for details https://github.com/interclip/interclip/commit/0e6e1267984683e88d0be53f0314449fdd26feb5
- Fixed the margin of the settings icon, [GL#7](https://gitlab.com/filiptronicek/Interclip/-/issues/7) for more details https://github.com/interclip/interclip/commit/36315212cbb3fe5d15eb9ea320ee72c6802471b0
- Fixed the CodeQL scanning workflow https://github.com/interclip/interclip/commit/fbb68bb34e2a7ca422f04f66d99c50181d1a55e4
- Fixed anti-CSRF https://github.com/interclip/interclip/commit/56c700c89ca5f4f8c6b3c1272de4f1ed1ba71b84

## Code 🎨
- Remove some unused HTML https://github.com/interclip/interclip/commit/d0a85189429c16854a3b1f506d2daf67b42f6087 https://github.com/interclip/interclip/commit/c42516ddc66ee959b1a7dbac821e0bcf3b9aed0e
- Remove deleted JS script https://github.com/interclip/interclip/commit/f5cc0505d6d3cb62a3417958a313947072ba308b
- Added a PHP IntelliSense GitPod extension https://github.com/interclip/interclip/commit/cac7d3d4132072125c2c89fac6af72f8656761b0

## Security 🔒 
- Fixed a security vulnerability that allowed RCE from the file upload page (`/upload/`) when using a malformed file extension https://github.com/interclip/interclip/commit/e5dba737def936b61b539526dcb1523ecc1dbd28

## Acknowledgments ❤️  
Special thanks to [@yo](https://gitlab.com/yo) over at the [GitLab repo](https://gitlab.com/filiptronicek/Interclip) for reporting a lot of bugs, here is a list of bugs he reported so far: https://gitlab.com/filiptronicek/Interclip/-/issues?scope=all&utf8=%E2%9C%93&state=all&author_username=yo

# v3.3.3 (March 29, 2021)
## Features ✨
- Added new alerts https://github.com/interclip/interclip/commit/7dfd3e9ed3d7f795ba966ddd46544493b6382978
- Alt text for a loading GIF https://github.com/interclip/interclip/commit/b0430105c0c89e7ecf5e54692126b4a448455819

## Performance ⚡️
- Deleted 50kB of icons https://github.com/interclip/interclip/commit/3ca7cef7fe280dc9e37378f5c0608790dbb0515a

## Security 🔒
- Prevent Generic Object Injection Sinks https://github.com/interclip/interclip/commit/3ca7cef7fe280dc9e37378f5c0608790dbb0515a
- Prevent request XSS on backend https://github.com/interclip/interclip/commit/3755a565cb09438062075617ca77e81e857ef4b1
- **Add Anti-CSRF tokens https://github.com/interclip/interclip/commit/8ca382d5a96c7094f697ef59617066df541f311d**
- Better way to escape HTML chars https://github.com/interclip/interclip/commit/e1b8f23a097f34ef8d0c68e2ed0f86f80614e007

# v3.3.2 (March 29, 2021)
## Features ✨ 
- Added metatags for better social previews https://github.com/interclip/interclip/commit/d586a8dc108ace7f6d7ab2b36f3090868d5dd255

## Bug fixes 🐛
- Fixed a bug where the favicon wouldn't display on Firefox https://github.com/interclip/interclip/commit/9aabc1b56aa208a9cc383e0f36023721c40d1c6e
- Fixed a bug with a non-working menu, which may still break on some local installations, but this will be resolved in the next version https://github.com/interclip/interclip/commit/3757872641005c8355543268cef6e4374a5ec208
- Made the Beta features shown by default https://github.com/interclip/interclip/commit/7a1bc7cc2fd6867925f105f7612ac62451869ed3

## Code 🎨
- Purge Cloudflare cache on every deployment https://github.com/interclip/interclip/commit/97b3a533f8d85a9c3243759b9365ad5e5541b7ae
- Deploy code also when a new release is published https://github.com/interclip/interclip/commit/29ecde7ef732e2d47c1734818ceb4f6c31b4b78d
- Delete the local copy of jQuery QR code https://github.com/interclip/interclip/commit/528b938498224abc45bc2fe3cf35a0b1542b2d0c

## Security 🔒
- Hide `.git/` for visitors https://github.com/interclip/interclip/commit/a0692b7a2282aa55171f8b61d8b498645dd79c4d

## Docs 📝
- Added [docs.interclip.app](https://docs.interclip.app/) https://github.com/interclip/interclip/commit/9551ed5b2c13528823d7782a6ac0e8a2e85106c7
- Add a CHANGELOG.md https://github.com/interclip/interclip/commit/1cef3387acfaa24f36451ea02e191459ebb73d64
- A ProTip in the README.md https://github.com/interclip/interclip/commit/0116f8378b6072d0483f67c67d21fa56118c9f01
- Changed logo position in the README.md https://github.com/interclip/interclip/commit/2015d75f076647ddf8cfe37af847bc38ab4f6695

# v3.3.1 (March 26, 2021)
## Features ✨ 
- Added support for a lot more browsers in image pasting https://github.com/interclip/interclip/commit/5b472757f3030217225e5f521d81bd9705824c1d
- Made the release display on the about page more reliable with server-side rendering instead of requesting the GitHub API https://github.com/interclip/interclip/commit/bc85a31a73113349a7e3242997626882f6a32313
- Make backend uploading more performant https://github.com/interclip/interclip/commit/623f6960ffc5224b06c515c10d471d6fc4e37db4
- Added the `curl` API https://github.com/interclip/interclip/commit/8918b5d7bc0c030d3004d75823838db16974f108
- Added a new about page https://github.com/interclip/interclip/commit/a2e038a4df8547da83f6b5392ee3a3b4b25063bc
- Dropped Nfogix analytics in favor of Cloudflare analytics, which really improved the load speed https://github.com/interclip/interclip/commit/9528be7ccbd948a4a28eb9a3ff6de106e11a14f4
## Bugs 🐛 
- Check if the pasted file is an image first before uploading https://github.com/interclip/interclip/commit/541bb2e9f646205389fae008080ca4fbcff91ee9
- Fixed QR code colors when there is a color theme collision https://github.com/interclip/interclip/commit/f4bd0fd72382ba283afcc1145ca2d5486b0eb0eb
- Fixed a bug where the UI would freak out when the user didn't put in a code https://github.com/interclip/interclip/commit/de741ca81d88a47c3e1a1bd9d3d2a70c335c1ca1
- New settings modal https://github.com/interclip/interclip/commit/7f4dea64247d0dd1d491d6379d2f4f7c8e412e93

## Code 🎨
- Indentation https://github.com/interclip/interclip/commit/4aa2f13ba74b57f73e9e57f4e224cd29d0372f4f https://github.com/interclip/interclip/commit/e1e1d0e92bd951554efcdf0d89cb7a096b3c7c15 https://github.com/interclip/interclip/commit/3111eca61f1f012a260e4126a5d48858551fba0b https://github.com/interclip/interclip/commit/271ec3dede2266483bcd491949c8f1695a7aceaa https://github.com/interclip/interclip/commit/2b5784d92e69d5a933b7bf3faf3fc7bfbf3227eb https://github.com/interclip/interclip/commit/9bbb94b835a5f0de7cb2a26e57985fb8ea0f08c4
- Shortened hex colors https://github.com/interclip/interclip/commit/fbbb2561afb5e2b0b7aedfdeabf6bd465efde4ec https://github.com/interclip/interclip/commit/64f248975b98b691cbe7f08b9e9495974829aa37
- Updated `.gitignore` https://github.com/interclip/interclip/commit/c83544819f1d2f83f59bc0eae6f33ba18e8f8516
- Removed a duplicate jQuery import https://github.com/interclip/interclip/commit/8b7645d9c9edd66d3e7b2b5fcc3e223f8cd2d4ca

### Accessibility ♿️
- Added lang attribute to <html> tags https://github.com/interclip/interclip/commit/0e65b824281eea9cb6cc9189728f5af5376eb80b
- Added `aria-label` attributes to buttons https://github.com/interclip/interclip/commit/245d64e82f62c77eb574492fd7171519eec25c8a


## Docs 📝 
- Update screenshots to be all on a PC screen and not mobile https://github.com/interclip/interclip/commit/e9dd4af0b20582c3a8d867dc366530b992557dc0
- Added file upload stats to the API doc https://github.com/interclip/interclip/commit/ebf7150b5eed84d0b79bc79982c46807dc88e7da
- Deleted the ToC from the README https://github.com/interclip/interclip/commit/c165cefff9cd587ba737b2c1735d9d7b7cbb4ed5
- Added documentation for the `curl` API https://github.com/interclip/interclip/commit/7f1d7474bde65e630ac93be616f585c473de7476
- Update CONTRIBUTING.md https://github.com/interclip/interclip/commit/10b8e165b475b2c2d201f55267b171133f9f0c2e
- Updated Code Of Conduct to Contributor Covenant, version 2.0 https://github.com/interclip/interclip/commit/35b977038a0f67d384e6f93f48641ec3916f7c25
- Added git as an installation prerequisite https://github.com/interclip/interclip/commit/4d06783c503a75b2563207b79a469a3db26a3b65
- Changed wording in the API doc https://github.com/interclip/interclip/commit/21fbd321b97a42e5fc8f0aec016e7854407e2d20

# v3.3.0 (February 25, 2021)
## Docs 📝
- Stripped some things from the privacy policy https://github.com/interclip/interclip/commit/77976e1b9c16b083d47fe11eecfa567b933b2170
- Updated some instructions in the CONTRIBUTING.md doc https://github.com/interclip/interclip/commit/3a45b2c377a5520a1336951298b86122fab807fe

## Code 🎨
- rewrote some PHP if syntax https://github.com/interclip/interclip/commit/3a45b2c377a5520a1336951298b86122fab807fe
- made the HTML in `new.php` a bit nicer to read https://github.com/interclip/interclip/commit/a034f8a1c239ad5ce2102c51a18507bf202dfb65

## Features ✨ 
- new file upload tool! https://github.com/interclip/interclip/commit/9961059c8936f291bb0aff434940ab4e34f5889e

# v3.2.6 (February 23, 2021)
## Bug fixes 🐛
- When the client IP reaches GitHub's API limit, don't even bother showing the info in the layout https://github.com/interclip/interclip/commit/0e83560448d80d20c7d29f155a20ac4db9d6dfa4

## Features ✨
- GitPodify Interclip so everyone can contribute! https://github.com/interclip/interclip/commit/b5a4db5425b9a44a901c98db339bfd409603b476
- Make a GitHub action that comments on every PR with a link to open that PR with GitPod https://github.com/interclip/interclip/commit/6cc2b45f16e73188671cb7b3f786a9239a6d5549
- Disable file uploads for the time being https://github.com/interclip/interclip/commit/24375bc568508328be16a0884cda1feadc64591f - see issue #59 
- Checked the clipped domain to prevent from saving unregistered domains https://github.com/interclip/interclip/commit/c5a0821bcc201e8e940b7a8aba4e3a2186882100
- Create a new backend for the new clip layout to show errors in a more fancy way https://github.com/interclip/interclip/commit/bf9768c6991a8edacf973674d00cbf92540553b2

## Code 🎨
- Add tests (with Pest) https://github.com/interclip/interclip/commit/ca94c2299f81e3a82fdde729fa057d06087d15df
- Remove the `cron.php` script https://github.com/interclip/interclip/commit/7c8b44342847448473cc3f751bfb6f5b3c9d7f79
- Make composer (in GitPod) also install all the dependencies https://github.com/interclip/interclip/commit/0ffd674e660f94add05bfa53da5a2ca568186e2a
- Shorten the link shortening function https://github.com/interclip/interclip/commit/7b00e7f37456d19e37fd15c5bf7515d06de0dd49

## Docs 📝
- Separate API.md from README.md https://github.com/interclip/interclip/commit/d4571a8ec35379549ae7c485a635d40a46aeacef https://github.com/interclip/interclip/commit/79bcfddf18b9fc01ebc93ff2d9ee78a2a6f34541
- Add some sample API responses to the doc https://github.com/interclip/interclip/commit/7ca0f25d48f31e767289ffcad4763601e6af8cf9
- Add an uptime badge to the README https://github.com/interclip/interclip/commit/bb0b0fa526b8f7228ccc001ec8d49551c4251cfd
- Change the position of the Interclip logo in the README https://github.com/interclip/interclip/commit/4d27818dd7f7bc09a696afa4459e262b2102f76b
- Remove the developer libraries section from the README https://github.com/interclip/interclip/commit/4d18a72c1e7991d5cdbb6f8f4d2a1ce41c1f2574
- Add a Code of Conduct and development setup doc https://github.com/interclip/interclip/commit/6d551951c476237f107d8cdb23874a24d020921e https://github.com/interclip/interclip/commit/6b51bd931dd505928dd03b34369c0546929fe297 https://github.com/interclip/interclip/commit/90dc18623a2525d6abc60c05e572444ad3751b2a https://github.com/interclip/interclip/commit/bcafa82f5207d0a1869091c510bdfdd45270d8e8 https://github.com/interclip/interclip/commit/a71917802512cf5c5ae2cfd2b9afd54900c20a64 https://github.com/interclip/interclip/commit/c41939dba6c27f4f08e2b60c75618f4b0dc6a3da 
- Add a link to the Firefox Interclip extension to the README https://github.com/interclip/interclip/commit/e93f708d5dc767ceac570bd4be620b9496fbb16f
- Add MySQL cron jobs to the Dev setup docs https://github.com/interclip/interclip/commit/5034ce3c842fec681fde6fa11266c276e962f090

# v3.2.5 (January 29, 2021)
## Features ✨
- get the id of the commit from which Interclip is currently deployed https://github.com/interclip/interclip/commit/3e24720ac107ca4f4046fb65fc49ca73b12ce92d

## Bug fixes 🐛
- API was acting a bit weird with URLs like about:config https://github.com/interclip/interclip/commit/6539b5a8fc73526568f40673f67d4f5e67abe57b
- menu page lacked menu styling https://github.com/interclip/interclip/commit/ae2c6d298ec5998ea11bef131c384029f9f3f363
- fix the URL of the favicon https://github.com/interclip/interclip/commit/52584c023b5c8d3933ad6190684d567af411acf5
- correctly import menu.css everywhere https://github.com/interclip/interclip/commit/7cc8f3df2ee8fa96d69849d4bdff5622e76bbd4b

## Code updates ♻️
-  Move menu CSS into a single file https://github.com/interclip/interclip/commit/84553c42a26cdc988ea60a3f05f4ac042894a762
- Also include rate-limiting in the about page https://github.com/interclip/interclip/commit/1747335bc4d8985ef87587cb03b0e1f5bfc8d5d7
- HTML rewrites https://github.com/interclip/interclip/commit/38502fb606736da908ac4a585562f00ef6b30e00 https://github.com/interclip/interclip/commit/9d449c2c815142bc58aae6a6d1418337fc5f05eb 
- jQuery => vanilla JS https://github.com/interclip/interclip/commit/d73784f85515fa5fa8adeba8d93f21c14a8a1dae https://github.com/interclip/interclip/commit/6f27cf41d55d5719798cfb7731b7582912f605e6
- JS updates https://github.com/interclip/interclip/commit/17ad60f8d1d0eaa16a51bcc57c44dbc521a8a57f https://github.com/interclip/interclip/commit/9a69e4e1db7ad8cbd4a469e57ef6730d7345a990
- fix a CSS anti-pattern https://github.com/interclip/interclip/commit/ce119c6358231947e8a0b7b835fcb6a7fb390c64
### File management 📦
- move icon files to `img/` https://github.com/interclip/interclip/commit/b84c81828123991462cd2891d5b1dedd78a85620 https://github.com/interclip/interclip/commit/9a69e4e1db7ad8cbd4a469e57ef6730d7345a990 
- format site manifest https://github.com/interclip/interclip/commit/e07842ef3f62931ea8d8b0b5500eb4e420fe4890

## Performance⚡️
- made the API 10x faster [tweet](https://twitter.com/filiptronicek/status/1354356645522976768)
- delete external sources that were unused or duplicate https://github.com/interclip/interclip/commit/103a2bb5b157b6c2ebd297623ed9c1d315ea250e


# v3.2.4 (January 19, 2021)
## UI 💄
- Make the site-wide font match https://github.com/interclip/interclip/commit/5f533bc277a2cc2c651b789456be9a64a186f36c
- Removed the mobile.php page and make the new catch-all index.php more responsive https://github.com/interclip/interclip/commit/94ed8ad6b448dc3b21d1218e6e83d55bfc092432
- Make the URL input autofocus to reduce the time to clip yo' stuff https://github.com/interclip/interclip/commit/593daf82903cbc1ba38041fd58fb03aee07ac87e
- Make the code input field have a min length of 5 https://github.com/interclip/interclip/commit/9e00f1e14cd4af0f35ca8476e41d157dc98eee45

## Security 🔒
- Fix HTTP image https://github.com/interclip/interclip/commit/9c1e351a7c26b1888d48f8795d21da487655b912
- **Add rate limiting https://github.com/interclip/interclip/commit/29b5872d5b38093d004c57675d2ab66fbf10b8c5**

## Features ✨
- **Expiring clips - clips now last just a month to keep everything running well https://github.com/interclip/interclip/commit/f3d912888e9d45b2c1c4393e6e86477481db624fl**

## Documentation 📝
- Add client apps to README https://github.com/interclip/interclip/commit/64c64cfb325fc4ec8cbcc61e958b459b0a720873
- Add Firefox add-on link to the README https://github.com/interclip/interclip/commit/10a19b716c96ad94579817429dfa2f68144c6415

# v3.2.3 (January 17, 2021)
## Security 🔒
-  Update CORS header in API https://github.com/interclip/interclip/commit/d6ff3db191755810877facb12a5e54999ea9d9e1
- Better sanitizing of SQL injections https://github.com/interclip/interclip/commit/596535a9e9f3f23be06e9c0319cc133d3bc238d5
- Better RegEx for URLs https://github.com/interclip/interclip/commit/7a8ffc0e7ded44bd4d62990f1f4c7853c04b3c49

## UI 💄
- Fix the copy code button in file.php and index.php https://github.com/interclip/interclip/commit/b856ac2f117d924f1ba88d27c889f5f463300356
- Hide loading animation with CSS instead of JS https://github.com/interclip/interclip/commit/95438e80b6aa80505abf32ccf72fcb5dccdddfb9
- Fix dark mode bug in file.php https://github.com/interclip/interclip/commit/c004cd1dc15e4b0df1d7077bfdaaa56812e301fb
- Fix modal colors on file upload https://github.com/interclip/interclip/commit/53e5aacb0734049737498f426ce622f9673cfbad

## Documentation 📝
- Delete ToDos section https://github.com/interclip/interclip/commit/d7122cd74b2257efecea1c20229f7b4ed2ba21cb
- Update libraries section with new ones and update embed.js name https://github.com/interclip/interclip/commit/8c548c5982d93e79b2e6eb896ef7113f8eec648d

## Bugs 🐛
- Make it impossible to generate the same code twice https://github.com/interclip/interclip/commit/138bf2cf11369080b9c53ec8f159e80b6fef694c
- Upgrade to APIv2 on file upload code copy https://github.com/interclip/interclip/commit/9ff2994cd1664fb00ab52d87105f23a9b7971caa

# v3.2.2 (January 16, 2021)
## Bugs 🐛
* Fix styling issues with menus on the receive and about pages https://github.com/interclip/interclip/commit/1612902ffec651663104d5e6fc4371f047792395
## Code 🎨
* Make the button on the receive page smaller and better https://github.com/interclip/interclip/commit/2fbdc33197d8f3d40ac185ebf69fb3907132cccd https://github.com/interclip/interclip/commit/00ecd9f7f152ed6bf75f1a034a716af1b9211f3e
* Update input bubble #34 https://github.com/interclip/interclip/commit/d2d9c43e05702b6f94cfa987f8d82b87320b93fa
* Update the sitemap https://github.com/interclip/interclip/commit/cfa5c228eb1fc5215392f83d5ba559a794f358ea
* Delete some files that are not needed anymore https://github.com/interclip/interclip/commit/70224f6a8373dc29eff8f0c3776a789ae672a9e5 https://github.com/interclip/interclip/commit/b6158676717a663bedd05e93335ef441f178aa1a
* Some CSS formatting https://github.com/interclip/interclip/commit/6af15e26fd3bc66ca672090802d4a41cf69003f4 https://github.com/interclip/interclip/commit/3f8ed51ac00a0d6767b5eed2e965e0eaa08ceb17

## Docs
* Updated privacy policy - just removed some stuff because Interclip doesn't collect any data about the user https://github.com/interclip/interclip/commit/217dacbaa67c710addff86792ecd62e15f1b802c
* Fix more non-interclip.app references https://github.com/interclip/interclip/commit/c33a63d2b2d1c7e4b766a2e76c71ca216d284073 https://github.com/interclip/interclip/commit/94581d78939d948c6c559e22397e8026a3c6ba3c


# v3.2.1 (January 15, 2021)
## Features ✨
- change most references to interclip.app https://github.com/interclip/interclip/commit/5066c4d3c921a0698618bd3dff06167c4e3bda3c  https://github.com/interclip/interclip/commit/39ea54348a8569375d3f70009abaca3e10c77101
- update the embed.js library https://github.com/interclip/interclip/commit/5bcc30ea19da7ad66803185db7bf8bbcf067cd6e
- add a number of total clips to the about page https://github.com/interclip/interclip/commit/ece0bf48b517dd0982e00d735e53add5b36fcd85
- make the privacy page less wide on desktop https://github.com/interclip/interclip/commit/c03a9a00182458bfe126841c7c1db5c696ea7b08
- change dark mode toggle styling https://github.com/interclip/interclip/commit/faaadcd5388a74b8e8acbbcb6fa3dd1aeb088a3c
- delete old hosting service ad tag https://github.com/interclip/interclip/commit/5f9452155104608d641d4de518b45ee6f946dd23
## Bugs 🐛
- fix an uploading bug in `file.js` https://github.com/interclip/interclip/commit/7c07ace51fe8ea59669ae6a41b1c683d7c9d5946
- fix file menu margin https://github.com/interclip/interclip/commit/ab5bd5563c7cb9b9b27deda8961227a01ee16e96

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
* Added embedding also to the [new clip](https://github.com/interclip/interclip/blob/master/includes/new.php) section
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
