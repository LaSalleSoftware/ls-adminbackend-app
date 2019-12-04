# Installing LaSalle Software's Administrative Back-end Application 

There are a lot of ways to deploy to production. What I describe here is a very plain way to go about it.

I am assuming that you use [Laravel Forge](https://forge.laravel.com). 

Forge deploys from a repository, and I am assuming that you use GitHub.com. 

So the way I do it is to set up my repository locally, then push to GitHub.com, then have Forge deploy from the GitHub.com repository. 


## Setting Up Your Local Repository

I am assuming that you have set up a local site with your favourite OS/server/etc. 

I use a Mac. On my Mac, I use [MAMP Pro](https://www.mamp.info/en/mamp-pro/). 

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

Now run ```php artisan key:generate``` to generate the [application key](https://laravel.com/docs/6.x#configuration)

Now run ```php artisan lslibrary:lasalleinstall```

Now, make this folder a git repo. Then, set up a repository of this folder in GitHub.com (or similar), so that Forge can install/update from this repo.

## If You Are Using MAMP

If you are using MAMP, and your MySQL is not working, then add the third line to your .env:
```
// https://stackoverflow.com/questions/50718944/laravel-5-6-connect-refused-using-mamp-server
// uncomment this line if you use MAMP on your local environment
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock
```

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


