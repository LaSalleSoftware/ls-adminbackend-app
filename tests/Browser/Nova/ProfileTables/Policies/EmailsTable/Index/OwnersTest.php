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

namespace Tests\Browser\Nova\ProfileTables\Policies\EmailsTable\Index;


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
     * Test that the an owner can see index listing.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group novaprofiletables
     * @group novaprofiletablesPolicies
     * @group novaprofiletablesPoliciesEmails
     * @group novaprofiletablesPoliciesEmailsIndex
     * @group novaprofiletablesPoliciesEmailsIndexOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\Policies\Emails\Index\TestOwners**";

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
                ->assertSee('bob.bloom@lasallesoftware.ca')
                ->assertSee('bbking@kingofblues.com')
                ->assertSee('srv@doubletrouble.com')
                ->assertSee('muddy.waters@blues.com')
                ->assertSee('sidney.bechet@blogtest.ca')
                ->assertSee('robert.johnson@blogtest.ca')
            ;
        });
    }
}
