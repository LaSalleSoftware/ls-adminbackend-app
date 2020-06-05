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

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_social_types\Delete;


// LaSalle Software
use Lasallesoftware\Librarybackend\Profiles\Models\Lookup_social_type;
use Tests\Browser\Nova\LookupTables\LookupTablesBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel classes
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;

// Laravel facade
use Illuminate\Support\Facades\DB;

class OwnersDeleteIsAvailableBecauseNotUsedInTheProfileTableTest extends LookupTablesBaseDuskTestCase
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
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookupsocialtypes
     * @group novaLookuptablesPoliciesLookupsocialtypesDelete
     * @group novaLookuptablesPoliciesLookupsocialtypesDeleteOwnersdeleteisavailablebecausenotusedintheprofiletable
     */
    public function testOwnersDeleteIsAvailableBecauseNotUsedInTheProfileTable()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookupsocialtypes\Delete\TestOwnersDeleteIsAvailableBecauseNotUsedInTheProfileTable**";


        // ARRANGE
        // need a brand new lookup record, because the first initial records are in the "do not delete" list
        $this->insertGenericLookupRecord('lookup_social_types');


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

                ->assertSee('Lookup Social Types')
                ->clickLink('Lookup Social Types')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Social Type')
                ->assertVisible('@'.$lookupLastId.'-row')
                ->assertVisible('@'.$lookupLastId.'-delete-button')
            ;
        });
    }

    private function getLookupTableLastId()
    {
        $lookupRecord = Lookup_social_type::orderBy('id', 'desc')->first();
        return $lookupRecord->id;
    }
}
