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

// Laravel classes
use Tests\TestCase;

// Laravel facades
use Illuminate\Support\Facades\Crypt;


class AppKeyRotationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['app.key' => 'base64:/ERQiBVmBk7mY9DHQEp+IvAw6mVe6Y4YZQwSi0g8K+E=']);
        config(['lasallesoftware-librarybackend.lasalle_previous_app_key' => 'base64:75yEn0Nt9KuFCOuslfJ6F1n4Eu5F1Tm6LslyVJ0YTV4=']);
    }


    /**
    * Test that the app.key set in setUp() above is reported by the ReEncryption class.
    *
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationAppKeyRotation
    * @group LibraryAPP_KEY_rotationAppKeyRotationCurrentappkeyvalue
    *
    * @return void
    */
   public function testCurrentAppKeyValue()
   {
       echo "\n**Now testing Tests\Unit\Library\APP_KEY_rotation\AppKeyRotationTest**";

       // new AppKeyRotation object
       $reencryption = new ReEncryption;

       // get the config value of 'app.key' returned by the AppKeyRotation class
       $result = $reencryption->getCurrent_APP_KEY();

       // assert!
       $this->assertEquals($result, 'base64:/ERQiBVmBk7mY9DHQEp+IvAw6mVe6Y4YZQwSi0g8K+E=', 'The config values do *not* equal!');
   }


    /**
    * Test that the lasallesoftware-librarybackend.lasalle_previous_app_key set in setUp() above is reported by the ReEncryption class.
    *
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationAppKeyRotation
    * @group LibraryAPP_KEY_rotationAppKeyRotationPreviousappkeyvalue
    *
    * @return void
    */
   public function testPreviousAppKeyValue()
   {
       // new AppKeyRotation object
       $reencryption = new ReEncryption;

       // get the config value of 'lasallesoftware-librarybackend.lasalle_previous_app_key' returned by the AppKeyRotation class
       $result = $reencryption->getPrevious_APP_KEY();

       // assert!
       $this->assertEquals($result, 'base64:75yEn0Nt9KuFCOuslfJ6F1n4Eu5F1Tm6LslyVJ0YTV4=', 'The config values do *not* equal!');
   }

    /**
    * Test that reEncryptValue() method works. 
    * This method decrypts a value using the previous app.key, and then re-encrypts it using the current app.key.
    *
    * @group Library
    * @group LibraryAPP_KEY_rotation
    * @group LibraryAPP_KEY_rotationAppKeyRotation
    * @group LibraryAPP_KEY_rotationAppKeyRotationTestreencryptionvalue
    *
    * @return void
    */
   public function testReEncryptValue()
   {
       // *****************************************************************
       // ARRANGE
       // *****************************************************************

       // We need a value for testing
       $testValue = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";

       // Instantiate a ReEncryption object
       $reencryption = new ReEncryption;

       // Encrypt the test value with the previous key
       $encryptedValueWithPreviousKey = $reencryption->getEncryptedValueUsingPreviousAPP_KEY($testValue);


       // *****************************************************************
       // ACT
       // *****************************************************************
       $result = $reencryption->reEncryptValue($encryptedValueWithPreviousKey);


       // *****************************************************************
       // ASSERT
       // *****************************************************************

       // decrypted result must equal the test value
       $decryptedResult = $reencryption->getDecryptedValueUsingCurrentAPP_KEY($result);
       $this->assertEquals($testValue, $decryptedResult, 'The testValue does not equal the decryptedResult');

       // The encrypted values should be 276 characters in length
       $this->assertEquals(strlen($encryptedValueWithPreviousKey), 276, 'The encrypted value using the previous key is not 276 characters');
       $this->assertEquals(strlen($result), 276, 'The encrypted value using the current key is not 276 characters');       
   }
}