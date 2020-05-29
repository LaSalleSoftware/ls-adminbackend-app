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

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_address_types\Delete;


// LaSalle Software
use Lasallesoftware\Library\Profiles\Models\Lookup_address_type;
use Tests\Browser\Nova\LookupTables\LookupTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel classes
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;

// Laravel facade
use Illuminate\Support\Facades\DB;

class OwnersDeleteNotAvailableBecauseUsedInTheAddressTableTest extends LookupTablesBaseDuskTestCase
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
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookupaddresstypes
     * @group novaLookuptablesPoliciesLookupaddresstypesDelete
     * @group novaLookuptablesPoliciesLookupaddresstypesDeleteOwnersdeletenotavailablebecauseusedintheaddresstable
     */
    public function testOwnersDeleteNotAvailableBecauseUsedInTheAddressTable()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookupaddresstypes\Delete\TestOwnersDeleteNotAvailableBecauseUsedInTheAddressTable**";


        // ARRANGE
        // (i) need a brand new lookup record, because the first 5 records are in the "do not delete" list
        $this->insertGenericLookupRecord('lookup_address_types');

        // (ii) create a record in the address db table, using the new lookup_address_types record
        $this->insertTestRecordIntoLookup();


        // ACT and ASSERT
        // The deletion icon should display for owners
        $login        = $this->loginOwnerBobBloom;
        $pause        = $this->pause();
        $lookupLastId = $this->getLookupTableLastId();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $lookupLastId) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->assertSee('Lookup Address Types')
                ->clickLink('Lookup Address Types')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Address Type')
                ->assertVisible('@'.$lookupLastId.'-row')
                ->assertMissing('@'.$lookupLastId.'-delete-button')
            ;
        });
    }

    private function insertTestRecordIntoLookup()
    {
        DB::table('addresses')->insert([
            'lookup_address_type_id' => $this->getLookupTableLastId(),
            'address_calculated'     => 'A specially inserted record for testing!',
            'address_line_1'         => 'A specially inserted record for testing!',
            'address_line_2'         => 'A specially inserted record for testing!',
            'address_line_3'         => 'A specially inserted record for testing!',
            'address_line_4'         => 'A specially inserted record for testing!',
            'city'                   => 'Chicago',
            'province'               => 'IL',
            'country'                => 'US',
            'postal_code'            => '60654',
            'latitude'               => null,
            'longitude'              => null,
            'description'            => null,
            'comments'               => null,
            'profile'                => null,
            'featured_image'         => null,
            'map_link'               => '',
            'uuid'                   => null,
            'created_at'             => Carbon::now(),
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);
    }

    private function getLookupTableLastId()
    {
        $lookupRecord = Lookup_address_type::orderBy('id', 'desc')->first();
        return $lookupRecord->id;
    }
}
