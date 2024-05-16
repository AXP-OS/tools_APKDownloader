# apk downloader

downloads given package directly from google play store or [Evozi's](https://apps.evozi.com/apk-downloader/) cache.

based on [Evozi's](https://apps.evozi.com/apk-downloader/) API and his example [APKDownloader](https://github.com/mohammadraquib/APKDownloader)

## Usage

`php download.php -p <android-pkg-name> [-b]`

- required: `-p <android-pkg-name>` (e.g. open the play store and grab it from the URL)
- optional: `-b` the "batch" mode will print (on success) only the local saved file name + DL url in the format: `<filename>;<dl-url>`

e.g:

`php download.php -p com.android.vending`
