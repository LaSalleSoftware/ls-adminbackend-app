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

namespace Tests\Unit\Library\Authentication\BanUsers;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\Models\Personbydomain;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class PersonbydomainModelIsBannedMethodTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
    }

    /**
     * Does the IsUserBanned() method in the Personbydomain model return true (is banned)?
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationBanusers
     * @group LibraryAuthenticationBanusersPersonbydomainmodelisbannedmethod
     * @group LibraryAuthenticationBanusersPersonbydomainmodelisbannedmethodIsbanned
     *
     * @return void
     */
    public function testIsBanned()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\BanUsers\PersonbydomainModelIsBannedMethodTest**";


        // Arrange
        DB::table('personbydomains')
              ->where('id', 1)
              ->update(['banned_enabled' => 1])
        ;

        // Act
        $user = new Personbydomain;
        $isBanned = $user->isBanned(1);

        // Assert
        $this->assertTrue($isBanned, "This user should be banned");
    }


    /**
     * Does the IsUserBanned() method in the Personbydomain model return false (is not banned)?
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationBanusers
     * @group LibraryAuthenticationBanusersPersonbydomainmodelisbannedmethod
     * @group LibraryAuthenticationBanusersPersonbydomainmodelisbannedmethodIsnotbanned
     *
     * @return void
     */
    public function testIsNotBanned()
    {
        // Arrange
        DB::table('personbydomains')
              ->where('id', 1)
              ->update(['banned_enabled' => 0])
        ;

        // Act
        $user = new Personbydomain;
        $isBanned = $user->isBanned(1);

        // Assert
        $this->assertFalse($isBanned, "This user should be NOT banned");
    }
}