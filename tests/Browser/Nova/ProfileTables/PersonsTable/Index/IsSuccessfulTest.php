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
 * @link       https://lasallesoftware.ca \lookup_social_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\Index;

// LaSalle Software
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];
    }

    /**
     * Test that the index view is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novaperson
     * @group novapersonindexissuccessful
     */
    public function testIndexViewIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\Index\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;

        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('People')
                ->pause($pause['long'])
                ->assertVisible('@create-button')

                //->waitFor('@sort-id')
                ->pause($pause['long'])

                ->assertVisible('@sort-id')
                ->click('@sort-id')
                ->pause($pause['long'])


                /* keeping this code for reference! I couldn't figure out how to select "per page" to
                   be "100", so I just next-ed through the pagination. Ugh, until it dawned on me
                   that I could click the "sort-id". Doh!

                ->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])
                ->click('@next')->pause($pause['long'])

                */

                // there should be four data rows displaying
                ->assertVisible('@4-row')
                ->assertVisible('@3-row')
                ->assertVisible('@2-row')
                ->assertVisible('@1-row')

                // Row 4 should display as follows:
                ->assertVisible('@4-view-button')
                ->assertVisible('@4-edit-button')
                ->assertMissing('@4-delete-button')

                // Row 3 should display as follows:
                ->assertVisible('@3-view-button')
                ->assertVisible('@3-edit-button')
                ->assertMissing('@3-delete-button')

                // Row 2 should display as follows:
                ->assertVisible('@2-view-button')
                ->assertVisible('@2-edit-button')
                ->assertMissing('@2-delete-button')

                // Row 1 should display as follows:
                ->assertVisible('@1-view-button')
                ->assertMissing('@1-edit-button')
                ->assertMissing('@1-delete-button')
            ;
        });
    }
}
