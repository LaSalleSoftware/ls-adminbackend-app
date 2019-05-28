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

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\CalculatedField;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Person;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateUniqueValidationFailsWithNoMiddlenameFieldTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;
    protected $updatedData;

    /*
     * Dusk will pause its browser traversal by this value, in ms
     *
     * @var int
     */
    protected $pause = 1500;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email' => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->testNameWithMiddleNameData = [
            'saluation'   => '',
            'first_name'  => 'Ella',
            'middle_name' => 'Jane',
            'surname'     => 'Fitzgerald',
            'position'    => 'The First Lady of Song',
            'birthday'    => '',
            'anniversary' => '',
            'description' => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'    => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit.',
        ];
    }

    /**
     * Test that an update SUCCEEDS when a middle_name is nulled-out. See the steps below.
     *
     * @group nova
     * @group novaperson
     * @group novapersoncalculatedfield
     * @group novapersoncalculatedfieldupdatenomiddlenamefieldfails
     */
    public function testUpdateUniqueValidationFailsWithNoMiddlenameField()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\CalculatedField\UpdateUniqueValidationFailsWithNoMiddlenameFieldTest**";

        $personTryingToLogin        = $this->personTryingToLogin;
        $testNameWithMiddleNameData = $this->testNameWithMiddleNameData;
        $pause                      = $this->pause;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $testNameWithMiddleNameData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('People')
                ->waitFor('@create-button')

                // STEP 1: Create the new person with no middle_name
                ->assertVisible('@create-button')
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('New Person')
                ->type('@first_name',  $testNameWithMiddleNameData['first_name'])
                ->type('@surname',     $testNameWithMiddleNameData['surname'])
                ->type('@position',    $testNameWithMiddleNameData['position'])
                ->type('@description', $testNameWithMiddleNameData['description'])
                ->type('@comments',    $testNameWithMiddleNameData['comments'])
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('Person Details')

                // STEP 2: Create a new person with the the same first_name and surname, and with a middle_name
                ->clickLink('People')
                ->waitFor('@create-button')
                ->assertVisible('@create-button')
                ->click('@create-button')
                ->pause($pause)
                ->pause($pause)
                ->assertSee('New Person')
                ->type('@first_name',  $testNameWithMiddleNameData['first_name'])
                ->type('@middle_name', $testNameWithMiddleNameData['middle_name'])
                ->type('@surname',     $testNameWithMiddleNameData['surname'])
                ->type('@position',    $testNameWithMiddleNameData['position'])
                ->type('@description', $testNameWithMiddleNameData['description'])
                ->type('@comments',    $testNameWithMiddleNameData['comments'])
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('Person Details')
            ;
        });

        // STEP 3: Update the person with the middle_name, by changing the middle_name to null
        //         Just so happens, this middle_name causes a unique error -- how 'bout that!
        $person = Person::orderby('id', 'desc')->first();

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $person, $pause) {
            $browser
                ->clickLink('People')
                ->waitFor('@create-button')

                ->waitFor('@' . $person->id . '-row')
                ->assertVisible('@' . $person->id . '-edit-button')
                ->click('@' . $person->id . '-edit-button')
                ->waitFor('@update-button')
                ->assertVisible('@update-button')
                ->assertSee('Edit Person')
                ->type('@middle_name', '  ')
                ->click('@update-button')
                ->pause($pause)
                ->assertSee('This person already exists')

             //   ->pause(5000)
             //   ->pause(5000)
            ;
        });
    }
}
