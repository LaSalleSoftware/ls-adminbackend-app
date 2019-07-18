# Installing LaSalle Software's Administrative Back-end Application 

There are a lot of ways to deploy to production. What I describe here is a very plain way to go about it.

I am assuming that you use [Laravel Forge](https://forge.laravel.com). 

Forge deploys from a repository, and I am assuming that you use GitHub.com. 

So the way I do it is to set up my repository locally, then push to GitHub.com, then have Forge deploy from the GitHub.com repository. 


## Setting Up Your Local Repository

I use a Mac. On my Mac, I use [MAMP Pro](https://www.mamp.info/en/mamp-pro/). 

Come on out to [The York Region PHP Meet-up](https://www.meetup.com/York-Region-PHP-User-Group/) or to the [GTA PHP User Group](https://www.meetup.com/GTA-PHP-User-Group-Toronto/) to 'splain-me why I should be using {fill-in-name-here} -- looking forward to meeting you!

I am assuming that you have set up a local site with your favourite OS/server/etc. 

- go to your local set-up
- run following command, which will create a "lasalleadmin" folder, and not install the "require-dev" packages:

```php
composer create-project lasallesoftware/lsv2-adminbackend-app lasalleadmin --no-dev
```


composer create-project lasallesoftware/lsv2-adminbackend-app hackintosh.lsv2-southlasalle.com --no-dev

## Laravel Forge

I assume that you use [Laravel Forge](https://forge.laravel.com).

- if you haven't done so already, create your server
- click Servers | the-name-of-your-server
- you should see "New Site"
- set up your new Laravel site, then click "Add Site"
- you should see your new site listed in Active Sites
- click your new site
- you should be in "Site Details"
- 


