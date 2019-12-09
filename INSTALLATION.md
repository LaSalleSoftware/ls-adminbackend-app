# Installing LaSalle Software's Administrative Back-end Application 

There are a lot of ways to deploy to production. What I describe here is a very plain way to go about it.

I am assuming that you use [Laravel Forge](https://forge.laravel.com). 

Forge deploys from a repository, and I am assuming that you use GitHub.com. 

So the way I do it is to set up my repository locally, then push to GitHub.com, then have Forge deploy from the GitHub.com repository. 

## VERY IMPORTANT NOTE: You need to buy the [Laravel Nova package](https://nova.laravel.com). No, I do not earn any commissions!

## Installing On Your Local Development Computer

#### Set up your new Laravel site

You will need the usual server stuff for local development. Here's [Laravel's server requirements](https://laravel.com/docs/6.x#server-requirements).

On my Mac, I use [MAMP Pro](https://www.mamp.info/en/mamp-pro/). 

Run this command from the command line, which will create a "lsv2-adminbackup-app" folder, and install LaSalle Software's admin app:

```composer create-project lasallesoftware/lsv2-adminbackend-app lsv2-adminbackup-app```

From the command line, run ```cd lsv2-adminbackup-app ``` to go into the local app's root folder.

#### Set up your local database

You need a database, so if you have not set up your local database for this local deployment, the please do so now.

#### Edit your local .env file

If you want to seed your database with test data, then in your .env set ```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true```

If APP_KEY is blank, then from the command line, run ```php artisan key:generate``` to generate the [application key](https://laravel.com/docs/6.x#configuration)

Now, scroll down to the database environment variables:
- modify "DB_DATABASE" 
- modify "DB_USERNAME"
- modify "DB_PASSWORD"

Now save your modified local .env file.

#### If You Are Using MAMP

If you are using MAMP, your MySQL may not work so you should add the following "DB_SOCKET" variable to your .env:
```
// https://stackoverflow.com/questions/50718944/laravel-5-6-connect-refused-using-mamp-server
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock
```

#### Run my custom installation artisan command

From the command line, run ```php artisan lslibrary:lasalleinstall```

#### Edit the Nova Service Provider

In App\Providers\NovaServiceProvider, in gate(), delete all the email address except for "bob.bloom@lasallesoftware.ca".

#### Login!

You should be able to log in with these credentials: 
- user = bob.bloom@lasallesoftware.ca
- password = secret

Update these credentials when you log in.

## Deploying on Laravel Forge

I use [Laravel's Forge](https://forge.laravel.com) and [Digital Ocean](https://www.digitalocean.com), so here are the steps...

Since I use webhooks to trigger a Forge deployment, I need a repository somewhere that Forge can ```git pull origin master``` from. I pull from a [Github](https://github.com) repository. 

I install a local app first (see the steps above). Then I make this installation a git repository. Then, I create a new Github repository and push my local to it. 

Now, it's time to set up things on Forge.

I assume that you have your server already set up. Make sure that when you set up your server that you set up your database server as well [Forge: Creating a Server With a Database](https://forge.laravel.com/docs/1.0/resources/databases.html#creating-a-server-with-a-database).

#### Set up your new Laravel site

- click Servers | the-name-of-your-server
- you should see "New Site"
- set up your new Laravel site, then click "Add Site"
- you should see your new site listed in Active Sites

#### Set up Let's Encrypt SSL

- click Sites | the-name-of-your-site
- click SSL
- click the "LetsEncrypt" box
- make sure that "Domains" is correct, then click "Obtain Certificate". 
- that's it!

#### Set up your database for your new Laravel site

It's time to set up your database. Your database server should already be installed!

- click Servers | the-name-of-your-server
- click Database

You must see your database server listed in the second box: "Databases | Name". 

Personally, I prefer creating a database user for each database individual database. Instead of using the default "forge" database users. As a catch-up type of link to suggest for background, here's a link to [MySQL 8.0 Reference Manual: Chapter 6 Security](https://dev.mysql.com/doc/refman/8.0/en/security.html).

Also, personally, I suggest using a monster crazy 256 character database user password. I grab random strings from sites like [PasswordsGenerator](https://passwordsgenerator.net/). BTW, sometimes I edit the strings before actually using them. Also, some people use super duper crazy strings for the database user name. Not me. Come to one of the Toronto area meet-ups I co-organize/attend and we'll talk about it!

- in the "Add Database User" box, fill in the "Name" and "Password" fields. Then click the "Can Access" check-box for the database the user pertains.
- click "Add User"
- done!

#### Edit your .env

- click Sites | the-name-of-your-site
- click Environment
- click "Edit Environment"

You see the stuff with the word "Dummy"? This is for my custom installation artisan command. Same idea as you see [in this Laravel "stub"](https://github.com/laravel/framework/blob/6.x/src/Illuminate/Foundation/Console/stubs/channel.stub). Please do not touch!

If you want to populate your database with test data, then set this variable to "true":
```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true```

If you want to enable IP address "whitelisting", then set this variable to "yes",
```LASALLE_WEB_MIDDLEWARE_DO_WHITELIST_CHECK=yes```
AND
enumerate your "whitelisted" IP addresses:
```LASALLE_WEB_MIDDLEWARE_WHITELIST_IP_ADDRESSES=ipaddress1,ipaddress2,ipaddress3```

If you have a lot of IP addresses to "whitelist", then please enumerate them in the "lasallesoftware-library" config file. Really, the only reason I have an .env variable for the IP addresses is the get around having to update the config file. It is easier to update the .env in Forge. My LaSalle Software uses the IP address specified in BOTH the .env and the config. 

When you want to allow an IP address temporarily, you have the option of specifying that IP address in the .env, and then when you logout of this app, you can delete the IP address from the .env. 

Now, scroll down to the database environment variables. They should look like this:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forge
DB_USERNAME=forge
DB_PASSWORD=abcdefghijklmnopqrst
```
- modify "DB_DATABASE" 
- modify "DB_USERNAME"
- modify "DB_PASSWORD"
- click "Save"
- done!

#### Run my custom installation artisan command

- ssh into your server 

- run ```php artisan lslibrary:lasalleinstall```

#### Login!

You should be able to log in with these credentials: 
- user = bob.bloom@lasallesoftware.ca
- password = secret

Update these credentials when you log in.


