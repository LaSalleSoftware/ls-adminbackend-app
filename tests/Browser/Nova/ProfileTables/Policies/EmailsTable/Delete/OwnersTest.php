<?php

/**
 * This file is part of  Lasalle Software 
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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

/**
 * BTW, There is no deletion test for Admins & Super Admin because the index listing where the delete button displays
 * is already tested in the "Index" tests. Since the index listing is not available, the delete button is not available.
 */

namespace Tests\Browser\Nova\ProfileTables\Policies\EmailsTable\Delete;


// LaSalle Software
use Tests\Browser\Nova\ProfileTables\ProfileTablesBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersTest extends ProfileTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an owner can delete.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaprofiletablesPolicies
     * @group novaprofiletablesPoliciesEmails
     * @group novaprofiletablesPoliciesEmailsDelete
     * @group novaprofiletablesPoliciesEmailsDeleteOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\Policies\Emails\Delete\TestOwners**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Email Addresses')
                ->clickLink('Email Addresses')
                ->pause($pause['long'])
                ->assertSee('Create Email Address')
                ->assertMissing('@1-delete-button')
                ->assertMissing('@2-delete-button')
                ->assertMissing('@3-delete-button')
                ->assertVisible('@4-delete-button')
                ->assertMissing('@5-delete-button')
                ->assertMissing('@6-delete-button')

            ;
        });
    }
}
