[![Build status](https://travis-ci.org/mcartoixa/synology-dlm-rarbg.svg?branch=master)](https://travis-ci.org/mcartoixa/synology-dlm-rarbg)
# Installation
- Download [the latest version of the module](https://github.com/mcartoixa/synology-dlm-rarbg/releases/latest) and install it in the [BT Search section of Download Station](https://www.synology.com/en-global/knowledgebase/DSM/help/DownloadStation/download_setup#torrent).

# Development
## Build

- Run the following command from this directory: `build.bat`
- Prerequisites:
    * PHP 5.6:
        + Make sure the following extensions are activated:
            - php_curl
            - php_mbstring
            - php_openssl
        + Set an environment variable `PHP_HOME` that points to the PHP installation folder (e.g `C:\PROGRA~2\PHP\5.6`).
