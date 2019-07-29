# Installing LaSalle Software's Administrative Back-end Application 

There are a lot of ways to deploy to production. What I describe here is a very plain way to go about it.

I am assuming that you use [Laravel Forge](https://forge.laravel.com). 

Forge deploys from a repository, and I am assuming that you use GitHub.com. 

So the way I do it is to set up my repository locally, then push to GitHub.com, then have Forge deploy from the GitHub.com repository. 


## Setting Up Your Local Repository

I use a Mac. On my Mac, I use [MAMP Pro](https://www.mamp.info/en/mamp-pro/). 

Come on out to [The York Region PHP Meet-up](https://www.meetup.com/York-Region-PHP-User-Group/) or to the [GTA PHP User Group](https://www.meetup.com/GTA-PHP-User-Group-Toronto/) to 'splain-me why I should be using {fill-in-name-here} -- looking forward to meeting you!

I am assuming that you have set up a local site with your favourite OS/server/etc. 

IMPORTANT NOTE: You need to buy the [Laravel Nova package](https://nova.laravel.com)

- go to your local set-up
- run following command, which will create a "lsv2-adminbackup-app" folder, and install the LaSalle Software's admin app:

```php
composer create-project lasallesoftware/lsv2-adminbackend-app lsv2-adminbackup-app 
```

then ```cd lsv2-adminbackup-app ``` 

Now, make sure you have a database set-up, and update your .env with your database info.

In your .env set ```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=false```

In App\Providers\NovaServiceProvider, in gate(), delete all the email address except for "bob.bloom@lasallesoftware.ca".

Now run ```php artisan lslibrary:lasalleinstall```

Now, make this folder a git repo. Then, set up a repository of this folder in GitHub.com (or similar), so that Forge can install/update from this repo.

## Laravel Forge

I assume that you use [Laravel Forge](https://forge.laravel.com).

- if you haven't done so already, create your server
- click Servers | the-name-of-your-server
- you should see "New Site"
- set up your new Laravel site, then click "Add Site"
- you should see your new site listed in Active Sites
- click your new site
- you should be in "Site Details"
- set up your Let's Encrypt SSL
- set up your database, and update your .env with your db info
- ssh into your server and run ```php artisan lslibrary:lasalleinstall```


