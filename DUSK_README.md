(May 08, 2019)

===================================================================
(Aug2019) Before doing anything, try php artisan dusk:chrome-driver
* https://github.com/laravel/dusk/blob/5.0/src/Console/ChromeDriverCommand.php
* https://github.com/laravel/dusk/pull/643
* https://github.com/laravel/dusk/issues/641
===================================================================

Dusk failed today. The chrome driver used is out of date. Huh??

Ultimately, I updated the chrome driver that Dusk uses manually.

* cd vendor/laravel/dusk/bin/

* download the latest chrome driver for MacOS at 
  https://chromedriver.storage.googleapis.com/index.html?path=73.0.3683.68/
  (for v74: https://chromedriver.storage.googleapis.com/index.html?path=74.0.3729.6/)
  (all versions: http://chromedriver.chromium.org/downloads)
  ** got this link via http://chromedriver.chromium.org/
  
* delete chromedriver-mac

* unzip chromedriver_mac64.zip that was just downloaded

* rename chromedriver to chromedriver-mac

That ought to do it. 

I did not use the command line to unzip and stuff. Sorry CLI purists.

There's a new artisan command for this, but the download failed (I'm sure it's a permissions thing). 
Here's the PR: https://github.com/laravel/dusk/pull/644

UPDATE: ```php artisan dusk:chrome-driver``` worked!  
