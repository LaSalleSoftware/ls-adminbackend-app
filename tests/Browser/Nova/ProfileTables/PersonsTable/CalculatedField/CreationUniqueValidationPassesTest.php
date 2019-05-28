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

class CreationUniqueValidationPassesTest extends DuskTestCase
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
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->testPassesData = [
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

        $this->testFailsData = [
            'id'          => 4,
            'saluation'   => 'Mr.',
            'first_name'  => 'Stevie',
            'middle_name' => 'Ray',
            'surname'     => 'Vaughan',
            'position'    => '',
            'birthday'    => '',
            'anniversary' => '',
            'description' => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'    => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit.',
        ];
    }

    /**
     * Test that a creation succeeds when the name_calculated field is unique
     *
     * @group nova
     * @group novaperson
     * @group novapersoncalculatedfield
     * @group novapersoncalculatedfieldcreationpasses
     */
    public function testCreationUniqueValidationPasses()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\CalculatedField\CreationUniqueValidationPassesTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $testPassesData      = $this->testPassesData;
        $pause               = $this->pause;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $testPassesData, $pause ) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('People')
                ->waitFor('@create-button')
                ->assertVisible('@create-button')
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('New Person')
                ->type('@first_name', $testPassesData['first_name'])
                ->type('@middle_name', $testPassesData['middle_name'])
                ->type('@surname', $testPassesData['surname'])
                ->type('@position', $testPassesData['position'])
                ->type('@description', $testPassesData['description'])
                ->type('@comments', $testPassesData['comments'])
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('Person Details')
            ;
        });
    }
}
