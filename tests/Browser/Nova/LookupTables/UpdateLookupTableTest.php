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
use Lasallesoftware\Library\Profiles\Models\Lookup_address_type;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateLookupTableTest extends DuskTestCase
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
     */
    public function testUpdateRecordToLookupTableSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\UpdateLookupTableTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $updatedLookupTableData = $this->updatedLookupTableData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $updatedLookupTableData) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->visit('/nova/resources/lookup_address_types/6/edit')
                ->pause(500)
                ->assertSee('Edit Lookup Address Type')
                ->type('@title', $updatedLookupTableData['title'])
                ->type('@description', $updatedLookupTableData['description'])
                ->click('@update-button')
                ->pause(500)
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
     */
    public function testUpdateRecordToLookupTableExpectNoUpdateDueToNotHavingOwnerRole()
    {
        $personTryingToLogin = [
            'email'    => 'bbking@kingofblues.com',
            'password' => 'secret',
        ];

        $this->browse(function (Browser $browser) use ($personTryingToLogin) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertDontSeeLink('Lookup Address Types')
                ->visit('/nova/resources/lookup_address_types/6/edit?viaResource=&viaResourceId=&viaRelationship=')
                ->pause(500)
                ->assertDontSee('Edit Lookup Address Type')
            ;
        });
    }
}
