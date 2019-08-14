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

namespace Tests\Browser\Nova\LoginsTable\Policies\Menu;

// LaSalle Software
use Tests\Browser\Nova\LoginsTable\LoginsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuSuppressedForSuperadminsTest extends LoginsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the a super admin cannot see the Logins menu item.
     *
     * @group nova
     * @group novaLogin
     * @group novaLoginPolicies
     * @group novaLoginPoliciesMenu
     * @group novaLoginPoliciesMenuSuppressedforsuperadmins
     */
    public function testSuppressedForSuperadmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\LoginsTable\Policies\Menu\TestSuppressedForSuperadmins**";

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
            ;
        });
    }
}
