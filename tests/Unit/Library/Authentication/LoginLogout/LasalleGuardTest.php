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
 * @license    http://opensource.org/licenses/MIT MIT
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
use Lasallesoftware\Library\Authentication\CustomGuards\LasalleGuard;
use Lasallesoftware\Library\Authentication\Models\Login;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator;

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
        $this->artisan('lslibrary:customseed');
    }

    /**
     * Testing that the LasalleGuard::createFullCredentials() method works, using the "email" field (key).
     *
     * The login form lacks a variable with the name of the domain the person logging in belongs to.
     * So, it is added here.
     *
     * The email field in the Personbydomains table is "email", so need to make
     * sure that the credentials array uses this field name (key)
     *
     * method: createFullCredentials($partialCredentials)
     *
     * @group authentication
     * @group loginlogout
     * @group loginlogoutLasalleguard
     * @group loginlogoutLasalleguardEqualemail
     *
     * @return void
     */
    public function testFullSetOfCredentialsForAuthenticationWithKeyEqualEmail()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\LoginLogout\LasalleGuardTest**";

        // Arrange
        // found this article to be helpful:
        // https://jtreminio.com/blog/unit-testing-tutorial-part-v-mock-methods-and-overriding-constructors/
        $lasalleguard = $this->getMockBuilder(LasalleGuard::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // *** key = "email" ***
        $partialCredentials = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ];

        // Act
        $fullCredentials = $lasalleguard->createFullCredentials($partialCredentials);

        // Assert
        $this->assertArrayHasKey('email',                  $fullCredentials);
       // $this->assertArrayHasKey('installed_domain_title', $fullCredentials); ==> not applicable
        $this->assertArrayHasKey('password',               $fullCredentials);
    }

    /**
     * Testing that the LasalleGuard::createFullCredentials() method works, using the "email" field (key).
     *
     * Similar to the test above.
     *
     * @group authentication
     * @group loginlogout
     * @group loginlogoutLasalleguard
     * @group loginlogoutLasalleguardPrimaryemail
     *
     * @return void
     */
    public function testFullSetOfCredentialsForAuthenticationWithKeyEqualPrimaryemailaddress()
    {
        // Arrange
        $lasalleguard = $this->getMockBuilder(LasalleGuard::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // *** key = "email" ***
        $partialCredentials = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ];

        // Act
        $fullCredentials = $lasalleguard->createFullCredentials($partialCredentials);

        // Assert
        $this->assertArrayHasKey('email',                  $fullCredentials);
       // $this->assertArrayHasKey('installed_domain_title', $fullCredentials); ==> not applicable
        $this->assertArrayHasKey('password',               $fullCredentials);
    }

    /**
     * The login token should be 40 characters long.
     *
     * @group login
     * @group lasalleguard
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
