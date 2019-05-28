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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
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

class CreationLookupTableTest extends DuskTestCase
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

        $this->newLookupTableData = [
            'title'              => 'Blues Boy King',
            'description'        => 'Lucille',
            'toolongdescription' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim metus, tristique pulvinar cursus in, pretium condimentum erat. Sed commodo  hendrerit nisi, eu faucibus felis molestie eget. Donec nec nisl mauris. Suspendisse potenti. Nunc massa est, egestas vitae hendrerit at, vestibulum in justo. Proin luctus ex vitae nisl ultrices, id semper eros interdum. In scelerisque nisl id tortor consequat placerat.',
        ];
    }

    /**
     * Test that the lookup table record creation is successful
     *
     * @group nova
     */
    public function testInsertNewRecordToLookupTableSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\CreationLookupTableTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newLookupTableData  = $this->newLookupTableData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newLookupTableData) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Lookup Address Types')
                ->pause(500)
                ->assertSee('Create Lookup Address Type')
                ->clickLink('Create Lookup Address Type')
                ->pause(500)
                ->assertSee('New Lookup Address Type')
                ->type('@title', $newLookupTableData['title'])
                ->type('@description', $newLookupTableData['description'])
                ->click('@create-button')
                ->pause(500)
            ;

            $lookup_address_type = Lookup_address_type::orderBy('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/lookup_address_types/'.$lookup_address_type->id);
            $this->assertEquals('Blues Boy King', $lookup_address_type->title);
            $this->assertEquals('Lucille', $lookup_address_type->description);
        });

        $this->assertDatabaseHas('lookup_address_types', ['title' => 'Blues Boy King']);
        $this->assertDatabaseHas('lookup_address_types', ['description' => 'Lucille']);
    }


    /**
     * Test that the lookup table record creation fails due to the title field not being filled in
     *
     * @group nova
     */
    public function testInsertNewRecordToLookupTableExpectRequireValidationToFail()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $newLookupTableData  = $this->newLookupTableData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newLookupTableData) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Lookup Address Types')
                ->pause(500)
                ->assertSee('Create Lookup Address Type')
                ->clickLink('Create Lookup Address Type')
                ->pause(500)
                ->assertSee('New Lookup Address Type')
                ->pause(500)
                ->click('@create-button')
                ->pause(500)
                ->assertSee('The title field is required.')
            ;
        });
    }

    /**
     * Test that the lookup table record creation fails due to the description exceeding 255 characters
     *
     * @group nova
     */
    public function testInsertNewRecordToLookupTableExpectDescriptionValidationToFail()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $newLookupTableData  = $this->newLookupTableData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newLookupTableData) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Lookup Address Types')
                ->pause(500)
                ->assertSee('Create Lookup Address Type')
                ->clickLink('Create Lookup Address Type')
                ->pause(1000)
                ->assertSee('New Lookup Address Type')
                ->type('@title', $newLookupTableData['title'])
                ->type('@description', $newLookupTableData['toolongdescription'])
                ->click('@create-button')
                ->pause(500)
                ->assertSee('The description may not be greater than 255 characters.')
            ;
        });
    }

    /**
     * Test that the lookup table record creation fails due to the domainbyperson (aka "The User")
     * not having the owner role
     *
     * @group nova
     */
    public function testInsertNewRecordToLookupTableExpectNoCreationButtonDueToNotHavingOwnerRole()
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
                ->pause(5000)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertDontSeeLink('Lookup Address Types')
                ->visit('/nova/resources/lookup_address_types')
                ->pause(500)
                ->assertDontSee('Create Lookup Address Type')
            ;
        });
    }
}
