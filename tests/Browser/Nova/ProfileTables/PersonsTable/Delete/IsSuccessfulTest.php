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
use Lasallesoftware\Library\Profiles\Models\Address;
use Lasallesoftware\Library\Profiles\Models\Person;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends DuskTestCase
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
     * Test that a deletion succeeds because there is no address or email or social or telephone or website.
     * associated with this person. So, we should see the delete icon!
     * (person policy at Lasallesoftware\Library\Policies\PersonPolicy)
     *
     * @group nova
     * @group novaperson
     * @group novapersondeletesucceeds
     */
    public function testDeletionFailsDueToAssociatedAddress()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\Delete\DeletionFailsDueToAssociatedAddress**";

        // create a new person in the database, with *no* address or email or social or telephone or social
        // associated (in Nova's parlance, "attached") to this person
        $people = factory(\Lasallesoftware\Library\Profiles\Models\Person::class, 1)
            ->create()
        ;

        // grab the newly created person's database ID
        $person = Person::orderBy('id', 'desc')->first();

        $personTryingToLogin = $this->personTryingToLogin;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $person) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('People')
                ->waitFor('@search')
                ->type('@search', $person->name_calculated)
                ->waitFor('@' . $person->id . '-row')
                ->assertVisible('@' . $person->id . '-row')
                ->assertVisible('@' . $person->id . '-delete-button')
                ->click('@' . $person->id . '-delete-button')
                ->pause(500)
                ->click('#confirm-delete-button')
                ->pause(500)
             ;
        });

        $this->assertDatabaseMissing('persons', ['name_calculated' => $person->name_calculated]);
    }
}
