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




namespace Tests\Unit\Library\Authentication;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\CustomGuards\LasalleGuard;
use Lasallesoftware\Library\Authentication\Models\Login;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;

class EmergencyBanAllUsersFromLoggingInTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
    }

    /**
     * The emergency ban should be enabled.
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationEmergencyban
     * @group LibraryAuthenticationEmergencybanIsenabled
     *
     * @return void
     */
    public function testIsEnabled()
    {
        // Arrange
        $lasalleguard = $this->getMockBuilder(LasalleGuard::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        config(['lasallesoftware-library.ban_all_users_from_logging_into_the_admin_backend' => true]);

        // Act
        $ban = $lasalleguard->emergencyBanAllUsersFromLoggingIn();

        // Assert
        $this->assertTrue($ban, '***The emergency ban should be enabled***');
    }

    /**
     * The emergency ban should be enabled.
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationEmergencyban
     * @group LibraryAuthenticationEmergencybanIsDisabled
     *
     * @return void
     */
    public function testIsDisabled()
    {
        // Arrange
        $lasalleguard = $this->getMockBuilder(LasalleGuard::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        config(['lasallesoftware-library.ban_all_users_from_logging_into_the_admin_backend' => false]);

        // Act
        $ban = $lasalleguard->emergencyBanAllUsersFromLoggingIn();

        // Assert
        $this->assertFalse($ban, '***The emergency ban should be disabled***');
    }
}
