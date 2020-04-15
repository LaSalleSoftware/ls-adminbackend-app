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

/**
 * BTW, There is no deletion test for Admins & Super Admin because the index listing where the delete button displays
 * is already tested in the "Index" tests. Since the index listing is not available, the delete button is not available.
 */

namespace Tests\Browser\Nova\ProfileTables\Policies\TelephonesTable\Delete;


// LaSalle Software
use Tests\Browser\Nova\ProfileTables\ProfileTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersTest extends ProfileTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an owner can delete.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaprofiletablesPolicies
     * @group novaprofiletablesPoliciesTelephones
     * @group novaprofiletablesPoliciesTelephonesDelete
     * @group novaprofiletablesPoliciesTelephonesDeleteOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\Policies\Telephones\Delete\TestOwners**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertSee('Telephone Numbers')
                ->clickLink('Telephone Numbers')
                ->pause($pause['long'])
                ->assertSee('Create Telephone Number')

                // The first record is not delete-able!
                ->assertMissing('@1-delete-button')

                ->assertVisible('@2-delete-button')
            ;
        });
    }
}
