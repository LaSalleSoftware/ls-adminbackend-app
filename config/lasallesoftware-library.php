<?php

/**
 * This file is part of the Lasalle Software library package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library
 * @link       https://github.com/lasallesoftware/library
 *
 */

return [

    /*
    |--------------------------------------------------------------------------
    | The name of this LaSalle Software app being installed?
    |--------------------------------------------------------------------------
    |
    | There are two right now:
    | * adminbackendapp
    | * basicfrontendapp
    |
    | There can be many front ends, but only one administrative backend.
    |
    | The admin backend is the only one with a database, and with access to certain
    | features and database tables.
    |
    | Set in the .env file.
    |
    */
    'lasalle_app_name' => env('LASALLE_APP_NAME'),

    /*
    |--------------------------------------------------------------------------
    | The app's URL, without the "https://"
    |--------------------------------------------------------------------------
    |
    | Best explained by example: if the app's URL is "https://admin.DoubleTrouble.com",
    | then this is "admin.DoubleTrouble.com".
    |
    | Set in the .env file.
    |
    */
    'lasalle_app_domain_name' => env('LASALLE_APP_DOMAIN_NAME'),

    /*
    |--------------------------------------------------------------------------
    | Default user role
    |--------------------------------------------------------------------------
    |
    | If not otherwise set, what user role should be automatically assigned to new registrants?
    |
    | There are 3 user roles (see the lookup_roles database table):
    |
    | * Owner (1) (very much not recommended as a default)
    | * Super Administrator (2) (not recommended as a default either)
    | * Administrator (3) (recommended)
    |
    */
    'lasalle_app_default_user_role' => 3,

    /*
	|--------------------------------------------------------------------------
	| Login activity duration in minutes
	|--------------------------------------------------------------------------
	|
	| After a certain number of minutes of not doing anything, a user will be logged out automatically.
	| How many minutes do you want to allow inactivity before logging a user out automatically?
	| This is a completely separate feature from Laravel's session inactivity setting (see
    | https://stackoverflow.com/questions/41983976/laravel-5-session-lifetime)
	|
	*/
    'lasalle_number_of_minutes_allowed_before_deleting_the_logins_record' => env('LASALLE_HOW_MANY_MINUTES_UNTIL_LOGINS_INACTIVITY', 60),

    /*
	|--------------------------------------------------------------------------
	| Json Web Token EXP claim duration
	|--------------------------------------------------------------------------
	|
    | How many seconds until a JWT expires?
    |
    | This EXP claim is set in the client domain.
	|
    | https://tools.ietf.org/html/rfc7519#section-4.1.4
	|
	*/
    'lasalle_jwt_exp_claim_seconds_to_expiration' => 3600,

    /*
	|--------------------------------------------------------------------------
	| Json Web Token IAT
	|--------------------------------------------------------------------------
	|
	| How many seconds should a JWT be valid after it is issued.
	|
    | The IAT claim is set automatically in the client domain.
    |
    | This duration is used in the API (back-end) domain as a time based validation.
    |
    | https://tools.ietf.org/html/rfc7519#section-4.1.6
	|
	*/
    'lasalle_jwt_iat_claim_valid_for_how_many_seconds' => 120,

    /*
	|--------------------------------------------------------------------------
	| Filesystem Disk Where Images Are Stored
	|--------------------------------------------------------------------------
	|
	| Which of the 'disks' in config\filesystems.php is used to store images? eg: 'local', 'public', 's3'.
	|
	| Beware that if you use the 'local' filesystem disk, then images will *not* be available to all
    | apps -- just the app that saved the image.
	|
	| So, generally, 's3' (or another cloud provider) is used.
	|
	*/
    'lasalle_filesystem_disk_where_images_are_stored'  => 's3',

    /*
	|--------------------------------------------------------------------------
	| Excerpt Length
	|--------------------------------------------------------------------------
	|
	| When an excerpt field is left blank, then the "content" field is used to
	| construct the excerpt. How many characters of the base "content" field
	| do you want to use for the excerpt?
	|
	*/
    'how_many_initial_chars_of_content_field_for_excerpt' => '400',

    /*
	|--------------------------------------------------------------------------
	| Ban All Users
	|--------------------------------------------------------------------------
	|
	| Ban all users from logging into the admin back-end.
	| 
	*/
    'ban_all_users_from_logging_into_the_admin_backend' => env('LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN', false),

    /*
	|--------------------------------------------------------------------------
	| Seed database with test data
	|--------------------------------------------------------------------------
	|
    | Seed database with test data? 
    |
    | Test data will seed in the "testing" environment, even when this setting is false.
    | 
    */
    'seed_database_with_test_data' => env('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA', false),

    

    /*
    | ========================================================================
    | START: MIDDLEWARE
    | ========================================================================
    */

    /*
	|--------------------------------------------------------------------------
	| Do The Whitelist Check For Web Middleware
	|--------------------------------------------------------------------------
	|
	| There is a whitelist middleware.
    |
    | This middleware allows selected IP addresses access to the site.
	|
	| This middleware is assigned to the "web" middleware group only.
    |
    | Note: this check does *not* necessarily relate to logging in. Relates to routes associated with the
    |       "web" middleware group.
    |
    | Note: for "web" middleware associated routes, when *not* on whitelist then access denied! (401 Unauthorized)
	|
	*/
    'web_middleware_do_whitelist_check' => env('LASALLE_WEB_MIDDLEWARE_DO_WHITELIST_CHECK', 'no'),

    /*
	|--------------------------------------------------------------------------
	| Whitelisted IP Addresses
	|--------------------------------------------------------------------------
	|
	| IP addresses allowed access to the "web" middleware group.
    |
    | Must be an array of IP addresses.
	|
	*/
    'web_middleware_whitelist_ip_addresses' => [],

    /*
	|--------------------------------------------------------------------------
	| Default Path for Lasallesoftware\Library\Authentication\Http\Middleware\RedirectSomeRoutes
	|--------------------------------------------------------------------------
	|
    | What path do you want Lasallesoftware\Library\Authentication\Http\Middleware\RedirectSomeRoutes
    | middleware to redirect to?
    |
    | If you are logged in, these paths will redirect to the default path
    | * home
    | * nova
    | * nova/dashboards
    | * nova/dashboards/main
    | * nova/resources
	|
    */
    //'web_middleware_default_path' => '/nova/resources/personbydomains',
    'web_middleware_default_path' => '/nova/resources/websites',

    /*
    | ========================================================================
    | END: MIDDLEWARE
    | ========================================================================
    */


    /*
   | ========================================================================
   | START: PATHS FOR FEATURED IMAGES
   | ========================================================================
   |
   | You may want to store featured image images (!) in S3 folders, and not just
   | in S3 buckets. And, you may want store Nova resource featured images in
   | their own folders. You can specify individual S3 folders here for profile
   | and blog resources.
   |
   | Must have a leading slash.
   | Must not have a trailing slash.
   |
   | Do not want to use an S3 folder at all? Just put the images in the S3 bucket?
   | Then, just specify '/',
   |
   | I designed this specifically for S3, but it applies generally because Nova
   | uses Laravel's storage facade
   | * https://laravel.com/docs/master/filesystem
   | * https://nova.laravel.com/docs/2.0/resources/file-fields.html#file-fields
   |
   | IMPORTANT!!! ****You need to set up each S3 folder in your AWS console.****
   | See https://github.com/LaSalleSoftware/ls-adminbackend-app/blob/master/AWS_S3_NOTES_README.md
   |
   */

    // for Nova resources in the novabackend package
    'image_path_for_address_nova_resource' => '/',
    //'image_path_for_address_nova_resource' => '/address',

    'image_path_for_company_nova_resource' => '/',
    //'image_path_for_company_nova_resource' => '/company',

    'image_path_for_person_nova_resource'  => '/',
    //'image_path_for_person_nova_resource'  => '/person',


    // for Nova resources in the blogbackend package
    'image_path_for_category_nova_resource' => '/',
    //'image_path_for_category_nova_resource' => '/category',

    'image_path_for_post_nova_resource'     => '/',
    //'image_path_for_post_nova_resource'     => '/post',

    /*
   | ========================================================================
   | END: PATHS FOR FEATURED IMAGES
   | ========================================================================
   */

];
