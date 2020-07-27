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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\Clients;

// LaSalle Software
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class OwnerCanSeeClientsTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Only an owner can see the clients Nova resource
     *
     * @group Client
     * @group ClientOwnercanseeclient
     * @group ClientOwnercanseeclientOwnercansee
     */
    public function testOwnerCanSee()
    {
        echo "\n**Now testing Tests\Browser\Nova\Clients\OwnerCanSeeClientsTest**";  

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Clients')
                ->clickLink('Clients')
                ->pause($pause['short'])
                ->assertSee('Clients')
                ->clickLink('Create Client')
            ;
        });
    }    
}