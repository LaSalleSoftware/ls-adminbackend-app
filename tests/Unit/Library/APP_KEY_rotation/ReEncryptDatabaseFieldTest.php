<?php

/**
 * This file is part of the Lasalle Software Basic Frontend App
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-basicfrontend-app Packagist
 * @link       https://github.com/lasallesoftware/lsv2-basicfrontend-app GitHub
 *
 */

namespace Tests\Unit\Library\APP_KEY_rotation;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\APP_KEY_rotation\ReEncryptDatabaseFields;
use Lasallesoftware\Librarybackend\Profiles\Models\Website;

// Laravel classes
use Illuminate\Encryption\Encrypter;
use Tests\TestCase;

// Laravel Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// Third party class
use Carbon\Carbon;
use Faker\Factory as Faker;


class ReEncryptDatabaseFieldTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->artisan('lslibrarybackend:customseed');

        // delete the seeded records in the websites database table
        Schema::disableForeignKeyConstraints(); 
        Website::truncate();
    }


    /**
    * Test the ReEncryptDatabaseFields class. Does it really re-encrypt an encrypted field with a new APP_KEY?
    * 
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationReencryptdatabasefield
    * @group LibraryAPP_KEY_rotationReencryptdatabasefieldReencryptthedatabasefield
    *
    * @return void
    */
   public function testReEncryptTheDatabaseField()
   {
        echo "\n**Now testing Tests\Unit\Library\APP_KEY_rotation\ReEncryptDatabaseFieldTest**";


        // **************************************************************************************
        // STEP 1: Seed the database table. The encrypted field uses the newly generated APP_KEY
        // **************************************************************************************

        // Set the original APP_KEY
        // https://github.com/laravel/framework/blob/0b12ef19623c40e22eff91a4b48cb13b3b415b25/src/Illuminate/Encryption/Encrypter.php#L68
        $app_key = $this->getNewAppKey();

        // Instantiate Laravel's encrypter class
        $encrypter = new Encrypter($this->decodeAppKey($app_key), $this->getCipher());

        // Create a couple of websites records using the same test data
        $testValue = 'From Casablanca: I remember everything. The Germans wore gray. You wore blue.';
        $this->createWebsitesRecord($encrypter->encrypt($testValue, false));
        $this->createWebsitesRecord($encrypter->encrypt($testValue, false));
        $this->createWebsitesRecord($encrypter->encrypt($testValue, false));


        // **************************************************************************************
        // STEP 2: Change the APP_KEY
        // **************************************************************************************

        // copy the APP_KEY to LASALLE_PREVIOUS_APP_KEY
        config(['lasallesoftware-librarybackend.lasalle_previous_app_key' => $app_key]);

        // Generate a fresh APP_KEY
        $new_app_key = $this->getNewAppKey();
        config(['app.key' => $new_app_key]);

        // Re-encrypt the database field
        $reencryptdatabasefields = new ReEncryptDatabaseFields;
        $reencryptdatabasefields->reEncryptTheWebsiteCommentsField();


        // **************************************************************************************
        // STEP 3: Assert!
        // **************************************************************************************
        
        // Instantiate a new encrypter object using the new APP_KEY
        $encrypter = new Encrypter($this->decodeAppKey($new_app_key), $this->getCipher());

        // Grab the records
        $websites = Website::all();

        // For each record, assert that the new encrypted value equals the test value
        foreach ($websites as $website) {
            $this->assertEquals($encrypter->decrypt($website->comments, false), $testValue, 'The re-encrypted value does not equal ' . $testValue);
        }
   }

   

   /**
    * Insert a record into the websites database table, given an encrypted value for the comments field.
    *
    * It is imperative not to use Eloquent because the website model encrypts the comments field in its boot() method. This
    * test is using its own generated APP_KEY, not the one in the .env.
    *
    * @param  string  $encryptedComment
    * @return void
    */
   private function createWebsitesRecord($encryptedComment)
   {
       $faker = Faker::create();

       DB::table('websites')->insert([
            [
                'lookup_website_type_id' => $faker->numberBetween($min = 1, $max = 6),
                'url'                    => $faker->unique($reset = false)->url(),
                'description'            => $faker->sentence($nbWords = 6, $variableNbWords = false) ,
                'comments'               => $encryptedComment,
                'uuid'                   => (string)Str::uuid(),
                'created_at'             => Carbon::now(null),
                'created_by'             => 1,
                'updated_at'             => null,
                'updated_by'             => null,
                'locked_at'              => null,
                'locked_by'              => null,
            ],
       ]);
   } 

   
   private function getNewAppKey()
   {
       return 'base64:'.base64_encode(Encrypter::generateKey($this->getCipher()));
   }


   private function decodeAppKey($app_key)
   {
       return base64_decode(substr($app_key, 7));
   }


   private function getCipher()
   {
       return config('app.cipher');
   }
}