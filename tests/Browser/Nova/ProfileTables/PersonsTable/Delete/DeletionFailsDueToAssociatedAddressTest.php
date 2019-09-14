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
 * @link       https://lasallesoftware.ca \lookup_social_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\Delete;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Person;
use Tests\Browser\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeletionFailsDueToAssociatedAddressTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];
    }

    /**
     * Test that a deletion fails because an address is associated with the person.
     * Actually, we cannot delete the person because there is an address associated with this person. So,
     * we should not even see the delete icon (suppressed by the policy at Lasallesoftware\Library\Policies\PersonPolicy).
     *
     * @group nova
     * @group novaprofiletables
     * @group novaperson
     * @group novapersondeletefailsassociatedaddress
     */
    public function testDeletionFailsDueToAssociatedAddress()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\Delete\DeletionFailsDueToAssociatedAddress**";

        // create a new person in the database, and at the same time create a new address in the database that is
        // associated (in Nova's parlance, "attached") to this person
        $people = factory(\Lasallesoftware\Library\Profiles\Models\Person::class, 1)
            ->create()
            ->each(function($person){
                $person->address()->save(factory(\Lasallesoftware\Library\Profiles\Models\Address::class)->make());
            })
        ;

        // grab the newly created person's database ID
        $person = Person::orderBy('id', 'desc')->first();

        $personTryingToLogin = $this->personTryingToLogin;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $person, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('People')
                ->waitFor('@search')
                ->type('@search', $person->name_calculated)
                ->waitFor('@' . $person->id . '-row')
                ->assertVisible('@' . $person->id . '-row')
                ->assertMissing('@' . $person->id . '-delete-button')
             ;
        });

        $this->assertDatabaseHas('persons', ['name_calculated' => $person->name_calculated]);
    }
}
