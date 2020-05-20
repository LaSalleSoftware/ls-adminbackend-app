# Installing LaSalle Software's Administrative Back-end Application 

## Preamble

There are a lot of ways to deploy to production. What I describe here is a very plain way to go about it.

I am assuming that you use [Laravel Forge](https://forge.laravel.com) to deploy to your production server. 

I have yet to deploy my LaSalle Software to [Laravel Vapor](https://vapor.laravel.com), but that day is drawing nearer.

Forge deploys from a repository, and I am assuming that, like me, you use GitHub.com. 

The way I do it is to set up my repository locally, then push to GitHub.com, then have Forge deploy from the GitHub.com repository.

I use [MAMP Pro](https://www.mamp.info/en/mamp-pro/) (not an affiliate link) for my local installations. Whatever local environment you use, it comes down to the same things: composer install, creating your database, updating the .env, and then firing up your up locally. 

## The Laravel Nova First Party Commercial Administration Package

My administrative app needs [Laravel Nova](https://nova.laravel.com) to run. 

So you need to buy Nova. No, I do not have an affiliate link nor an arrangement. 

Nova is installed via composer. The documentation for doing this is [here](https://nova.laravel.com/docs/2.0/installation.html#installing-nova-via-composer).

## Installing On Your Local Development Computer

#### Set up your new Laravel site

You will need the usual server stuff for local development. Here's [Laravel's server requirements](https://laravel.com/docs/6.x#server-requirements).

Run this command from the command line, which will create a "ls-adminbackup-app" folder, and install LaSalle Software's admin app. Create a folder name that suits your individual needs:

```composer create-project lasallesoftware/ls-adminbackend-app ls-adminbackend-app```

From the command line, run ```cd ls-adminbackup-app ``` to go into the local app's root folder.

#### If you are setting up this local admin app for production, then change the composer.json

Generally, but especially when you are using Forge for production deployments, paste "composer.forge.json" to your composer.json.

Then run ```composer update```

#### Set up your local database

You need a database, so if you have not set up your local database for this local deployment, the please do so now.

#### Run lslibrary:lasalleinstallenv

Run my custom installation artisan command for setting a few environment variables in your .env file:

```php artisan lslibrary:lasalleinstallenv```

"lasalleinstallenv" = a kind of a short form of "LaSalle Installation for Environment Variables". Well, only a few env vars.

#### Edit your local .env file

At this point, please review your local .env file. 

If APP_KEY is blank, then run ```php artisan key:generate``` to generate the [application key](https://laravel.com/docs/6.x#configuration)

You should not see any values beginning with the characters "Dummy", which are placeholder values used in lslibrary:lasalleinstallenv for string substitution.

If you want to seed your database with test data, then in your .env set ```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true```. Please note that test data will not seed in production.

Are your database environment variables correct? They should be, but please double check!

If you are using MAMP, your MySQL may not work so you should add the following "DB_SOCKET" variable to your .env:
```
// https://stackoverflow.com/questions/50718944/laravel-5-6-connect-refused-using-mamp-server
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock
```

Now save your modified local .env file.

#### Run lslibrary:lasalleinstalladminapp

Run my custom installation artisan command for my admin app only:

```php artisan lslibrary:lasalleinstalladminapp```

This command will prepare Laravel Nova, perform an optional database drop, and do the database migration and seeding.

The database seed is necessary to run the admin app. 

If you want to include my test data with the seeding, set ```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true``` in your .env. 

#### Edit the Nova Service Provider

In App\Providers\NovaServiceProvider, in gate() when you are not including my test data in the seeding, delete all the email addresses except for "bob.bloom@lasallesoftware.ca".

Please note that the emails you specify here will be pushed to GitHub.com for use on your Forge deployment. 

#### Fire up your local LaSalle Software admin app in your browser!

You should see the familiar Laravel welcome view. 

Click "login". These credentials are set up so you can log in:
- user = bob.bloom@lasallesoftware.ca
- password = secret

Change these credentials!

## Deploying on Laravel Forge

#### Preamble

I use [Laravel's Forge](https://forge.laravel.com) and [Digital Ocean](https://www.digitalocean.com), so here are the steps...

Since I use webhooks to trigger a Forge deployment, I need a repository somewhere that Forge can ```git pull origin master``` from. I pull from a [Github](https://github.com) repository. 

I install a local app first (see the steps above). Then I make this installation a git repository. Then, I create a new Github repository and push my local to it. 

Now, it's time to set up things on Forge.

I assume that you have your server already set up. Make sure that when you set up your server that you set up your database server as well [Forge: Creating a Server With a Database](https://forge.laravel.com/docs/1.0/resources/databases.html#creating-a-server-with-a-database).

#### Set up your new Laravel site in Forge

- click Servers | the-name-of-your-server
- you should see "New Site"
- set up your new Laravel site, then click "Add Site"
- you should see your new site listed in Active Sites

#### Set up Let's Encrypt SSL in Forge

- click Sites | the-name-of-your-site
- click SSL
- click the "LetsEncrypt" box
- make sure that "Domains" is correct, then click "Obtain Certificate". 
- that's it!

#### Set up your database, and optionally your database user, in Forge

It's time to set up your database. Your database server should already be installed!

- click Servers | the-name-of-your-server
- click Database

In the "Add Database" box:
- type in the "Name" of your database
- type in new "User (Optional)" of your database, ONLY IF YOU WANT TO CREATE A NEW DATABASE USER
- type the "Password (Optional)" for your new database user, ONLY IF YOU WANT TO CREATE A NEW DATABASE USER 
- click "Add Database"
- done!

#### Run lslibrary:lasalleinstallenv

SSH into your cloud (Digital Ocean) server (droplet), and cd into your app's root folder. 

Run my custom installation artisan command for setting a few environment variables in your .env file:

```php artisan lslibrary:lasalleinstallenv```

Please note that Forge has likely changed the values of your database environment variables. So my custom artisan command will not be able to change them if the values are incorrect.

#### Edit your .env

- click Sites | the-name-of-your-site
- click Environment
- click "Edit Environment"

If you want to populate your database with test data, then set this variable to "true" -- leave it "false" in production:
```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true```

If you want to enable IP address "whitelisting", then set this variable to "yes",
```LASALLE_WEB_MIDDLEWARE_DO_WHITELIST_CHECK=yes```
AND
enumerate your "whitelisted" IP addresses:
```LASALLE_WEB_MIDDLEWARE_WHITELIST_IP_ADDRESSES=ipaddress1,ipaddress2,ipaddress3```

If you have a lot of IP addresses to "whitelist", then please enumerate them in the "lasallesoftware-library" config file. Really, the only reason I have an .env variable for the IP addresses is the get around having to update the config file. It is easier to update the .env in Forge. My LaSalle Software uses the IP address specified in BOTH the .env and the config. 

When you want to allow an IP address temporarily, you have the option of specifying that IP address in the .env, and then when you logout of this app, you can delete the IP address from the .env. 

Now, scroll down to the database environment variables. Please double check that they are correct!

#### The "APP_KEY" environment variable

If APP_KEY is blank, then run ```php artisan key:generate``` to generate the [application key](https://laravel.com/docs/6.x#configuration).

Please save this key somewhere because it is used for encryption. You probably won't need to worry about it, but just in case. 

My suggestion where to save your key: [AWS Key Management Service](https://aws.amazon.com/kms/).

#### Run lslibrary:lasalleinstalladminapp

Return to your app's root folder on your cloud server instance.

Run my custom installation artisan command for my admin app only:

```php artisan lslibrary:lasalleinstalladminapp```

This command will prepare Laravel Nova, perform an optional database drop, and do the database migration and seeding.

The database seed is necessary to run the admin app. 

I highly recommend that you set ```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=false``` in your .env. 

When the ```LASALLE_POPULATE_DATABASE_WITH_TEST_DATA``` environment variable is false, you will be prompted to create the first user (with the "owner" role). Please follow the prompts to enter your first user's first name, surname, and email address.  

#### Fire up your local LaSalle Software admin app in your browser!

You should see the familiar Laravel welcome view. 

Click "login". 

If you were prompted to create your first user, then login with the email address that you entered. Your initial password defaults to "secret". Please change this password upon your initial login.

If you seeded your database with test data, then use these credentials to log in:
- user = bob.bloom@lasallesoftware.ca
- password = secret

## Using Cloud Storage

In production, you should use cloud storage for your images, especially when you are using multiple domains. 

See [notes on setting up Amazon Web Services S3](AWS_S3_NOTES_README.md)
