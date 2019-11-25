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
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\LookupTables;

// LaSalle Software classes
use Lasallesoftware\Library\Dusk\LaSalleBrowser;
use Lasallesoftware\Library\Profiles\Models\Lookup_address_type;
use Tests\LaSalleDuskTestCase;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateLookupTableTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newLookupTableData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->updatedLookupTableData = [
            'title'              => 'Stevie Ray Vaughan',
            'description'        => 'Double Trouble',
        ];
    }

    /**
     * Test that the lookup table record update is successful
     *
     * @group nova
     * @group novalookuptables
     */
    public function testUpdateRecordToLookupTableSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\UpdateLookupTableTest**";

        $personTryingToLogin    = $this->personTryingToLogin;
        $updatedLookupTableData = $this->updatedLookupTableData;
        $pause                  = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $updatedLookupTableData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->visit('/nova/resources/lookup_address_types/6/edit')
                ->pause($pause['long'])
                ->assertSee('Update Lookup Address Type')
                ->type('@title', $updatedLookupTableData['title'])
                ->type('@description', $updatedLookupTableData['description'])
                ->click('@update-button')
                ->pause($pause['long'])
            ;

            $lookup_address_type = Lookup_address_type::find(6);
            $browser->assertPathIs('/nova/resources/lookup_address_types/'.$lookup_address_type->id);
            $this->assertEquals($updatedLookupTableData['title'], $lookup_address_type->title);
            $this->assertEquals($updatedLookupTableData['description'], $lookup_address_type->description);

        });

        $this->assertDatabaseHas('lookup_address_types', ['title' => $updatedLookupTableData['title']]);
        $this->assertDatabaseHas('lookup_address_types', ['description' => $updatedLookupTableData['description']]);
    }

    /**
     * Test that the lookup table record update fails due to the domainbyperson (aka "The User")
     * not having the owner role
     *
     * @group nova
     * @group novalookuptables
     */
    public function testUpdateRecordToLookupTableExpectNoUpdateDueToNotHavingOwnerRole()
    {
        $personTryingToLogin = [
            'email'    => 'bbking@kingofblues.com',
            'password' => 'secret',
        ];
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertDontSeeLink('Lookup Address Types')
                ->visit('/nova/resources/lookup_address_types/6/edit?viaResource=&viaResourceId=&viaRelationship=')
                ->pause($pause['long'])
                ->assertDontSee('Edit Lookup Address Type')
            ;
        });
    }
}
