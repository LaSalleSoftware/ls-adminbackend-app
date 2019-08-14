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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\LoginsTable\Policies\Index;

// LaSalle Software
use Tests\Browser\Nova\LoginsTable\LoginsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperadminsTest extends LoginsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that a superadmin cannot see the index page.
     *
     * @group nova
     * @group novaLogin
     * @group novaLoginPolicies
     * @group novaLoginPoliciesIndex
     * @group novaLoginPoliciesIndexSuperadmins
     */
    public function testSuperadmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\LoginsTable\Policies\Index\TestSuperadmins**";

        $this->insertTestRecordIntoLoginsExcludeSuperadminLoginTable();

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertMissing('Logins')

                // Can go to the index view via direct URL, because there is no policy to prevent it. Instead,
                // we control what records are listed via IndexQuery
                ->visit('/nova/resources/logins/')
                ->pause($pause['medium'])
                ->assertMissing('@1-row')
                ->assertMissing('@2-row')
                ->assertMissing('@3-row')
                ->assertMissing('@4-row')
            ;
        });
    }
}
