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

// LaSalle Software class
use Lasallesoftware\Librarybackend\APP_KEY_rotation\ReEncryption;
use Lasallesoftware\Librarybackend\Profiles\Models\Website;

// Laravel classes
use Tests\TestCase;

// Laravel Facades
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// Third party classes
use Carbon\Carbon;
use Faker\Factory as Faker;


class WebsiteFactoryCheckTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->artisan('lslibrarybackend:customseed');

        // delete the seeded records in the websites database table
        Schema::disableForeignKeyConstraints();
        Website::truncate();

        config(['app.key' => 'base64:/ERQiBVmBk7mY9DHQEp+IvAw6mVe6Y4YZQwSi0g8K+E=']);
        config(['lasallesoftware-librarybackend.lasalle_previous_app_key' => 'base64:75yEn0Nt9KuFCOuslfJ6F1n4Eu5F1Tm6LslyVJ0YTV4=']);
    }


    /**
    * Test that the app.key set in setUp() above is reported by the ReEncryption class.
    *
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationWebsitefactorycheck
    * @group LibraryAPP_KEY_rotationWebsitefactorycheckCurrentappkeyvalue
    *
    * @return void
    */
   public function testCurrentAppKeyValue()
   {
       echo "\n**Now testing Tests\Unit\Library\APP_KEY_rotation\WebsiteFactoryCheckTest**";

       // new ReEncryption object
       $reencryption = new ReEncryption;

       // get the config value of 'app.key' returned by the ReEncryption class
       $result = $reencryption->getCurrent_APP_KEY();

       // assert!
       $this->assertEquals($result, 'base64:/ERQiBVmBk7mY9DHQEp+IvAw6mVe6Y4YZQwSi0g8K+E=', 'The config values do *not* equal!');
   }


    /**
    * Test that the lasallesoftware-librarybackend.lasalle_previous_app_key set in setUp() above is reported by the ReEncryption class.
    *
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationWebsitefactorycheck
    * @group LibraryAPP_KEY_rotationWebsitefactorycheckPreviousappkeyvalue
    *
    * @return void
    */
   public function testPreviousAppKeyValue()
   {
       // new ReEncryption object
       $reencryption = new ReEncryption;

       // get the config value of 'lasallesoftware-librarybackend.lasalle_previous_app_key' returned by the ReEncryption class
       $result = $reencryption->getPrevious_APP_KEY();

       // assert!
       $this->assertEquals($result, 'base64:75yEn0Nt9KuFCOuslfJ6F1n4Eu5F1Tm6LslyVJ0YTV4=', 'The config values do *not* equal!');
   }

    /**
    * This is a double check of sorts that the website model's factory is encrypting the comments field ok.
    * 
    * I am sure that this test is not necessary. But the website model's boot method includes encrypting the
    * comments field, and after discovering that I double encrypted this field in the factory, I seek some 
    * assurance, however nominal. 
    * 
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationWebsitefactorycheck
    * @group LibraryAPP_KEY_rotationWebsitefactorycheckIswebsitemodelfactoryencryptingcommentsfield
    *
    * @return void
    */
   public function testIsWebsiteModelFactoryEncryptingCommentsField()
   {
       $testValue = "Casablanca: I remember every detail. The Germans wore grey, you wore blue.";

       factory(Website::class, 3)->create([
           'comments' => $testValue,
       ]);

       $websites = Website::all();

       foreach ($websites as $website) {
           $this->assertEquals($testValue, Crypt::decrypt($website->comments), 'The testValue does not equal the decrypted value');
       }
   }
}