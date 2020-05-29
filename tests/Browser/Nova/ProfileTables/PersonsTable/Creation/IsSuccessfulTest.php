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

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\Creation;

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
            'description' => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'    => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit. Diam quam nulla porttitor massa id neque aliquam vestibulum. Imperdiet proin fermentum leo vel orci porta non pulvinar. Consequat ac felis donec et. Eget velit aliquet sagittis id consectetur purus ut faucibus pulvinar. Tincidunt ornare massa eget egestas purus viverra accumsan. Lectus quam id leo in vitae turpis massa. Et egestas quis ipsum suspendisse. Eu nisl nunc mi ipsum faucibus vitae aliquet. Pulvinar mattis nunc sed blandit libero volutpat sed. Duis tristique sollicitudin nibh sit amet commodo nulla.

Tristique magna sit amet purus gravida quis blandit. Dolor purus non enim praesent elementum facilisis. Consequat ac felis donec et odio pellentesque diam volutpat. Ac turpis egestas sed tempus urna et pharetra. Elit ullamcorper dignissim cras tincidunt lobortis feugiat. Fermentum dui faucibus in ornare quam viverra orci. Ut sem nulla pharetra diam sit. Justo donec enim diam vulputate ut pharetra. Nisi scelerisque eu ultrices vitae auctor eu augue ut lectus. Quisque non tellus orci ac. Euismod nisi porta lorem mollis aliquam. Condimentum lacinia quis vel eros donec. Eget arcu dictum varius duis at consectetur lorem. Sapien faucibus et molestie ac feugiat.

Eu ultrices vitae auctor eu. Gravida dictum fusce ut placerat orci nulla pellentesque dignissim enim. Amet dictum sit amet justo donec enim diam. Varius quam quisque id diam vel quam elementum pulvinar etiam. Velit dignissim sodales ut eu. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus ornare. Tortor id aliquet lectus proin. At elementum eu facilisis sed odio morbi quis commodo odio. Tincidunt tortor aliquam nulla facilisi. Suspendisse in est ante in nibh mauris. Bibendum ut tristique et egestas quis ipsum suspendisse ultrices gravida. Id porta nibh venenatis cras sed felis. Semper risus in hendrerit gravida rutrum quisque non tellus. Quis vel eros donec ac odio tempor. Vulputate enim nulla aliquet porttitor lacus luctus accumsan tortor posuere. Massa eget egestas purus viverra accumsan in nisl nisi. Eget aliquet nibh praesent tristique magna sit amet. Ut venenatis tellus in metus vulputate eu scelerisque felis. Nisl suscipit adipiscing bibendum est ultricies. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor aliquam nulla.

Bibendum arcu vitae elementum curabitur vitae nunc sed. Ornare quam viverra orci sagittis eu volutpat. Odio tempor orci dapibus ultrices in iaculis. Accumsan sit amet nulla facilisi morbi. Faucibus turpis in eu mi bibendum. Odio morbi quis commodo odio aenean. Lobortis feugiat vivamus at augue eget arcu dictum varius duis. Ultricies leo integer malesuada nunc. Dolor purus non enim praesent elementum facilisis leo vel fringilla. Diam phasellus vestibulum lorem sed risus. Rutrum quisque non tellus orci ac auctor augue mauris. Risus nec feugiat in fermentum. Fusce id velit ut tortor pretium viverra suspendisse potenti nullam. A diam sollicitudin tempor id eu nisl nunc. Sed egestas egestas fringilla phasellus. Vulputate odio ut enim blandit.

Id diam vel quam elementum pulvinar etiam. Pellentesque nec nam aliquam sem et tortor. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec pretium. Non enim praesent elementum facilisis leo vel fringilla est. Sodales ut eu sem integer vitae justo eget magna fermentum. Feugiat pretium nibh ipsum consequat nisl vel pretium. Leo in vitae turpis massa sed elementum tempus egestas. Elementum nibh tellus molestie nunc non blandit. Pretium vulputate sapien nec sagittis aliquam malesuada. Viverra orci sagittis eu volutpat. Gravida dictum fusce ut placerat orci nulla pellentesque dignissim enim. Viverra ipsum nunc aliquet bibendum enim facilisis. Blandit libero volutpat sed cras ornare. Interdum varius sit amet mattis vulputate enim nulla aliquet.

Posuere urna nec tincidunt praesent semper feugiat. Id venenatis a condimentum vitae sapien pellentesque habitant. Adipiscing enim eu turpis egestas pretium aenean. Faucibus turpis in eu mi. Enim neque volutpat ac tincidunt vitae semper quis. Tempus egestas sed sed risus pretium. Sed lectus vestibulum mattis ullamcorper velit sed ullamcorper morbi tincidunt. Interdum posuere lorem ipsum dolor sit amet. Eget velit aliquet sagittis id consectetur. Sit amet cursus sit amet dictum sit amet justo. Non pulvinar neque laoreet suspendisse. Consectetur a erat nam at lectus urna duis convallis. Eros donec ac odio tempor orci dapibus.

Tempor nec feugiat nisl pretium fusce id. Sed vulputate odio ut enim blandit volutpat maecenas volutpat. Mollis aliquam ut porttitor leo a diam sollicitudin tempor. Ut sem viverra aliquet eget sit amet tellus. Pellentesque massa placerat duis ultricies lacus sed. Feugiat pretium nibh ipsum consequat nisl. Lorem ipsum dolor sit amet consectetur adipiscing elit. Viverra nam libero justo laoreet sit amet cursus sit. Sit amet risus nullam eget felis eget nunc. Vel pretium lectus quam id leo in vitae turpis. Vehicula ipsum a arcu cursus vitae. Eu feugiat pretium nibh ipsum consequat nisl vel. Odio ut sem nulla pharetra. Rutrum tellus pellentesque eu tincidunt. Id eu nisl nunc mi. Ut lectus arcu bibendum at. Amet facilisis magna etiam tempor orci eu lobortis elementum nibh. Odio eu feugiat pretium nibh ipsum consequat nisl vel pretium. Duis at tellus at urna condimentum mattis pellentesque. Amet tellus cras adipiscing enim eu.

Sodales ut eu sem integer. Velit aliquet sagittis id consectetur purus ut faucibus pulvinar elementum. Neque vitae tempus quam pellentesque. Sed odio morbi quis commodo. Diam quis enim lobortis scelerisque fermentum. Morbi leo urna molestie at elementum eu facilisis sed. Sapien eget mi proin sed libero enim sed faucibus. Cursus metus aliquam eleifend mi in nulla posuere. In dictum non consectetur a. Amet dictum sit amet justo donec enim diam. Sagittis eu volutpat odio facilisis mauris sit amet massa vitae. Nisi porta lorem mollis aliquam ut porttitor leo a. Vitae sapien pellentesque habitant morbi tristique senectus et netus. Ac ut consequat semper viverra. Sagittis vitae et leo duis. Porta lorem mollis aliquam ut porttitor leo a diam. Aenean euismod elementum nisi quis eleifend quam adipiscing. Rutrum tellus pellentesque eu tincidunt.',
        ];
    }

    /**
     * Test that the creation is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novaperson
     * @group novapersoncreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\Creation\IsSuccessfulTest**";

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
                ->pause($pause['long'])
                ->assertVisible('@create-button')
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Create Person')
                ->type('@first_name', $newData['first_name'])
                ->type('@middle_name', $newData['middle_name'])
                ->type('@surname', $newData['surname'])
                ->type('@position', $newData['position'])
                ->type('@description', $newData['description'])
                ->type('@comments', $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Person Details')
            ;

            $person = Person::orderBy('id', 'desc')->first();
            $uuid   = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/people/'.$person->id);
            $this->assertEquals($newData['first_name'],  $person->first_name);
            $this->assertEquals($newData['middle_name'], $person->middle_name);
            $this->assertEquals($newData['surname'],     $person->surname);
            $this->assertEquals($newData['position'],    $person->position);
            $this->assertEquals($newData['description'], $person->description);
            $this->assertEquals($newData['comments'],    $person->comments);

            $this->assertEquals($uuid->uuid, $person->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 7);
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
