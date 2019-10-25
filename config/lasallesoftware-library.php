<?php

/**
 * This file is part of the Lasalle Software library (lasallesoftware/library)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
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
    'lasalle_number_of_minutes_allowed_before_deleting_the_logins_record' => env('LASALLE_HOW_MANY_MINUTES_UNTIL_LOGINS_INACTIVITY', 10),

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
    | Default Social Media Tag Image
    |--------------------------------------------------------------------------
    |
    | When no social media image is specified, use this image.
    |
    | Specify the full URL!
    |
    | A social media tag is a Twitter card meta tag, or an Open Graph meta card, or something of that nature.
    |
    | According to https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup,
    | "URL of image to use in the card. Images must be less than 5MB in size. JPG, PNG, WEBP and GIF
    | formats are supported. Only the first frame of an animated GIF will be used. SVG is not supported."
    |
    */
    'lasalle_social_media_meta_tag_default_image'  => 'https://lasallesoftware.ca/buffalo-bills.jpg',

    /*
	|--------------------------------------------------------------------------
	| Default Featured Image
	|--------------------------------------------------------------------------
	|
	| When no featured image is specified, use this image.
	|
	| Specify the type of image by commenting out the types that do not apply.
    |  * "external_file" = what you want in the "src" of the IMG html tag (<img src="" >)
    |                      such as a full URL where the image resides, or base64 code
    |  * "code" = the entire html code encompassing the image, that will be rendered in html literally
	|
	*/
    'lasalle_featured_image_default_type'  => 'external_file',
    //'lasalle_featured_image_default_type'  => 'code',
    'lasalle_featured_image_default_image' => '/buffalo-bills.jpg',

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
    'how_many_initial_chars_of_content_field_for_excerpt' => '250',

];
