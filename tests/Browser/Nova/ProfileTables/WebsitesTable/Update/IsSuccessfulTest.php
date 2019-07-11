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

namespace Tests\Browser\Nova\ProfileTables\WebsitesTable\Update;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Website;
use Tests\Browser\LaSalleDuskTestCase;
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
            'url'                    => 'https://www.allaboutbluesmusic.com/blues-artists/',
            'lookup_website_type_id' => 6,
            'description'            => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'               => 'eyJpdiI6InFkNFJsXC83bGRWbHM5NTlyUDdQeXhRPT0iLCJ2YWx1ZSI6IkplQzYrYUtQUjgrNjB3YllZWFdnNDhFMWFlZUF5cytVeGtEdWJiMUFlQjZ6MkFsUEdvQWdyWDBwQkY2NENqd0p0eXFzdUNzZWNNSnZXTmdHVlJkQVc2RWN0WDRzXC81aGtZR1BhNUJIZnFabXBkMSt0ZEtxVUVQUXhmM1NBSjhySHBzWDMwZzZ0aVRWWUxaRktEbmh3OFwvQlM0S3dmYU80UVBBYU56VVdDVUtBUWpCT1dpSFdyVjNubzVZTHJRQ1ZOeXhhNzFWUHdkZFJVczBQZmQ5RDBxTWhlSnloT2M3ZlgreHJlZGhMa05XT2hPdDlPWWNUaHlPTlpGRnB4MHk1K083NEFmVEJCbXlxaDdQRmF3eDZQZFdWQjg4TERsR25RbnlSWjh5bmxQTHlwNjVsc1ZzZGtJcTBXV2NLS0xrZGdScUJvNGIydFVJS1FvcnFzQ0QxMDBacytoaGRZMVBWbzNScUhrQnNPTlJrNEEwekFpWmsyOHJ3ZnlqZ1RnUm5zQ2E3RmtDbnB0UkxsUGNBUG84cTZVMFwvNWVReUgxNExFN2I4ZlQ5ZnUrU0NoU2k1bnllSEczUjlsOW1lQzJPU3ZXazY0NnFHUGtWajg2cFJFV09FS2wwMUU0eXQwVFlwV0xsc2dFRHRzcHJkSmM3NmU2aDd4alZuRTNENStsWEdCaXdNeVRmOHNkZGdiUFBcL2NJWHNXd1hOc0kyUXBEdW10RnNXOWdIeFdHaGVcL2E5YWR3WXBGcXJ5c2phNTVYS2lKeE16VWo5T0t3cFd3b1NvYlpXbExzZFg0WFwvMGVxSm1BTmhLQnlDcVwvbXlsblFZTWwyUlpBMWVOTXJualVcL1hESVhUdDZlQ09yS3NcLzJHN2llZmZBaEh3PT0iLCJtYWMiOiIyODlkNmM0YTVhYTJjNDc4MzZiZTgxZWUxOTJlOTNjYWYzZThjYWY1YTRhZGE4NGJhNGRkNzllOGQ4YzYyZjU4In0=',
        ];
    }

    /**
     * Test that an update is successful.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaswebsite
     * @group novawebsiteupdate
     * @group novaswebsiteupdatesuccessful
     */
    public function testEditExistingRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\WebsitesTable\Update\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData            = $this->newData;
        $pause              = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newData, $pause) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Websites')
                ->waitFor('@1-row')
                ->assertVisible('@2-edit-button')
                ->click('@2-edit-button')
                ->waitFor('@update-button')
                ->assertVisible('@update-button')
                ->assertSee('Update Website')
                ->type('@description', $newData['description'])
                ->type('@comments', $newData['comments'])
                ->select('@lookup_website_type', $newData['lookup_website_type_id'])
                ->click('@update-button')
                ->pause($pause['short'])
                ->assertSee('Website Details')
            ;

            $website = Website::orderBy('id', 'desc')->first();
            $uuid    = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/websites/'.$website->id);
            $this->assertEquals($newData['lookup_website_type_id'], $website->lookup_website_type_id);
            $this->assertEquals($newData['description'],            $website->description);
            //$this->assertEquals($newData['comments'],               $website->comments);  **not equal due to encryption**

            $this->assertEquals($uuid->uuid, $website->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('websites', ['lookup_website_type_id' => $newData['lookup_website_type_id']]);
        $this->assertDatabaseHas('websites', ['description'            => $newData['description']]);
        //$this->assertDatabaseHas('websites', ['comments'               => $newData['comments']]);  **not equal due to encryption**
    }
}
