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

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\Update;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Person;
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class IsSuccessfulTest extends LaSalleDuskTestCase
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

        $this->newData = [
            'saluation'   => '',
            'first_name'  => 'Ella',
            'middle_name' => 'Jane',
            'surname'     => 'Fitzgerald',
            'position'    => 'The First Lady of Song',
            'birthday'    => '',
            'anniversary' => '',
            'description'           => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'              => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit. Diam quam nulla porttitor massa id neque aliquam vestibulum. Imperdiet proin fermentum leo vel orci porta non pulvinar. Consequat ac felis donec et. Eget velit aliquet sagittis id consectetur purus ut faucibus pulvinar. Tincidunt ornare massa eget egestas purus viverra accumsan. Lectus quam id leo in vitae turpis massa. Et egestas quis ipsum suspendisse. Eu nisl nunc mi ipsum faucibus vitae aliquet. Pulvinar mattis nunc sed blandit libero volutpat sed. Duis tristique sollicitudin nibh sit amet commodo nulla.',
        ];
    }

    /**
     * Test that an update is successful.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaperson
     * @group novapersonupdatesuccessful
     */
    public function testEditExistingRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\Update\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newData, $pause) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('People')

                //->waitFor('@sort-id')
                ->pause($pause['long'])
                ->assertVisible('@sort-id')
                ->click('@sort-id')
                ->pause($pause['long'])

                ->waitFor('@4-row')
                ->assertVisible('@4-edit-button')
                ->click('@4-edit-button')
                ->waitFor('@update-button')
                ->assertVisible('@update-button')
                ->assertSee('Update Person')
                ->type('@first_name', $newData['first_name'])
                ->type('@middle_name', $newData['middle_name'])
                ->type('@surname', $newData['surname'])
                ->type('@position', $newData['position'])
                ->type('@description', $newData['description'])
                ->type('@comments', $newData['comments'])
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Person Details')
            ;

            $person = Person::find(4);
            $uuid   = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/people/'.$person->id);
            $this->assertEquals($newData['first_name'],  $person->first_name);
            $this->assertEquals($newData['middle_name'], $person->middle_name);
            $this->assertEquals($newData['surname'],     $person->surname);
            $this->assertEquals($newData['position'],    $person->position);
            $this->assertEquals($newData['description'], $person->description);
            $this->assertEquals($newData['comments'],    $person->comments);

            $this->assertEquals($uuid->uuid, $person->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('persons', ['name_calculated'  => 'Ella Jane Fitzgerald']);
        $this->assertDatabaseHas('persons', ['first_name'  => $newData['first_name']]);
        $this->assertDatabaseHas('persons', ['middle_name' => $newData['middle_name']]);
        $this->assertDatabaseHas('persons', ['surname'     => $newData['surname']]);
        $this->assertDatabaseHas('persons', ['position'    => $newData['position']]);
        $this->assertDatabaseHas('persons', ['description' => $newData['description']]);
        $this->assertDatabaseHas('persons', ['comments'    => $newData['comments']]);
    }
}
