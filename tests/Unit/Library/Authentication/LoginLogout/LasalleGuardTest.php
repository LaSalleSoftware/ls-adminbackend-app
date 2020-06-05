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



// *******************************************************************************************************************
// My LasalleGuard class is based verbatim on Laravel's SessionGuard. Most of the methods I am using from SessionGuard
// are untouched (or not used as I am not implementing basic auth).
//
// As well, many methods depend on the UserProvider contract. I am using the EloquentUserProvider class completely
// untouched, as-is.
// (https://github.com/laravel/framework/blob/5.8/src/Illuminate/Auth/EloquentUserProvider.php)
//
// So I am depending on Laravel a lot. Which is exactly what I am trying to achieve. I am not interested in testing
// The Framework, so I will be testing what I am customizing.
// *******************************************************************************************************************




namespace Tests\Unit\Library\Authentication\LoginLogout;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\Authentication\CustomGuards\LasalleGuard;
use Lasallesoftware\Librarybackend\Authentication\Models\Login;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;

class LasalleGuardTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * The login token should be 40 characters long.
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationLoginlogout
     * @group LibraryAuthenticationLoginlogoutLasalleguard
     * @group LibraryAuthenticationLoginlogoutLasalleguardLogintokenshouldbe40characterslong
     *
     * @return void
     */
    public function testLoginTokenShouldBe40CharactersLong()
    {
        // Arrange
        $lasalleguard = $this->getMockBuilder(LasalleGuard::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // Act
        $token = $lasalleguard->getLoginToken();

        // Assert
        $this->assertTrue(strlen($token) === 40, '***The login token string length should be 40***');
    }
}
