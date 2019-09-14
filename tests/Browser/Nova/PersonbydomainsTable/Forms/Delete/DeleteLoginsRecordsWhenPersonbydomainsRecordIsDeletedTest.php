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

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Delete;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;


class DeleteLoginsRecordsWhenPersonbydomainsRecordIsDeletedTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // Yes, I am using the blog seeds!
        $this->artisan('lslibrary:customseed');
        $this->artisan('lslibrary:installeddomainseed');
    }

    /**
     * Test that a personbydomain is successfully deleted. Using an owner user.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainForms
     * @group novaPersonbydomainFormsDelete
     * @group novaPersonbydomainFormsDeletedeleteloginsrecordswhenpersonbydomainsrecordisdeleted
     */
    public function testDeleteLoginsRecordsWhenPersonbydomainsRecordIsDeleted()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Delete\DeleteLoginsRecordsWhenPersonbydomainsRecordIsDeletedTest**";

        // Need a test persons who is not in the personbydomains db table, and not faker generated. So let's create one!
        $this->insertTestRecordIntoEmailsTable();
        $this->insertTestRecordIntoPersonsTable();
        $this->insertTestRecordIntoPerson_emaiTable();
        $this->insertTestRecordIntoPersonbydomainTable();

        // Create logins records. New personbydomain is ID#6
        DB::table('logins')->insert([
            ['id' => null, 'personbydomain_id' => 2, 'token' => 'token2', 'uuid' => 'uuid2', 'created_at' => now(), 'created_by' => 1, 'updated_at' => now(), 'updated_by' => 1 ],
            ['id' => null, 'personbydomain_id' => 3, 'token' => 'token3', 'uuid' => 'uuid3', 'created_at' => now(), 'created_by' => 1, 'updated_at' => now(), 'updated_by' => 1 ],
            ['id' => null, 'personbydomain_id' => 4, 'token' => 'token4', 'uuid' => 'uuid4', 'created_at' => now(), 'created_by' => 1, 'updated_at' => now(), 'updated_by' => 1 ],
            ['id' => null, 'personbydomain_id' => 5, 'token' => 'token5', 'uuid' => 'uuid5', 'created_at' => now(), 'created_by' => 1, 'updated_at' => now(), 'updated_by' => 1 ],
            ['id' => null, 'personbydomain_id' => 6, 'token' => 'token6', 'uuid' => 'uuid6', 'created_at' => now(), 'created_by' => 1, 'updated_at' => now(), 'updated_by' => 1 ],
        ]);

        // Double check that the new logins records were inserted
        $loginsRecords = DB::table('logins')->where('token', 'token2')->first();
        $this->assertEquals(2,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token2', $loginsRecords->token);
        $this->assertEquals('uuid2',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token3')->first();
        $this->assertEquals(3,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token3', $loginsRecords->token);
        $this->assertEquals('uuid3',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token4')->first();
        $this->assertEquals(4,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token4', $loginsRecords->token);
        $this->assertEquals('uuid4',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token5')->first();
        $this->assertEquals(5,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token5', $loginsRecords->token);
        $this->assertEquals('uuid5',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token6')->first();
        $this->assertEquals(6,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token6', $loginsRecords->token);
        $this->assertEquals('uuid6',  $loginsRecords->uuid);




        $personTryingToLogin  = $this->loginOwnerBobBloom;
        $pause                = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['shortest'])
                ->waitFor('@6-row')
                ->click('@6-delete-button')
                ->pause($pause['shortest'])
                ->click('#confirm-delete-button')
                ->pause($pause['medium'])
            ;
        });

        // Are the newly inserted logins records still in the logins db table that should still be there?
        $loginsRecords = DB::table('logins')->where('token', 'token2')->first();
        $this->assertEquals(2,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token2', $loginsRecords->token);
        $this->assertEquals('uuid2',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token3')->first();
        $this->assertEquals(3,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token3', $loginsRecords->token);
        $this->assertEquals('uuid3',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token4')->first();
        $this->assertEquals(4,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token4', $loginsRecords->token);
        $this->assertEquals('uuid4',  $loginsRecords->uuid);

        $loginsRecords = DB::table('logins')->where('token', 'token5')->first();
        $this->assertEquals(5,        $loginsRecords->personbydomain_id);
        $this->assertEquals('token5', $loginsRecords->token);
        $this->assertEquals('uuid5',  $loginsRecords->uuid);

        // Is the newly inserted logins record deleted, that should be deleted when its personsbydomains parent record was deleted?
        $this->assertDatabaseMissing('logins', ['id'    => 5]);
        $this->assertDatabaseMissing('logins', ['token' => 'token6']);
        $this->assertDatabaseMissing('logins', ['uuid'  => 'uuid6']);
    }
}
