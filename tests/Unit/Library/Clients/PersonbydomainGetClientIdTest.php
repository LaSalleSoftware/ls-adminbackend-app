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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Unit\Library\Clients;

// LaSalle Software
use Lasallesoftware\Librarybackend\Profiles\Models\Client;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

// App
use Tests\TestCase;

class PersonbydomainGetClientIdTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
    * Test the Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain->getClientId($personbydomain_id) method.
    * Scenario: there is a client associated with a personbydomain in the personbydomain_client pivot table. Return the client_id.
    * 
    * @group Client
    * @group ClientPersonbydomaingetclientid
    * @group ClientPersonbydomaingetclientidIssuccessful
    *
    * @return void
    */
   public function testIsSuccessfull()
   {
        echo "\n**Now testing Tests\Unit\Library\Clients\PersonbydomainGetClientIdTest**";

        // ARRANGE: set up test data
        factory(Client::class, 1)->create();

        DB::table('personbydomain_client')->insert([
            'personbydomain_id' => 1,
            'client_id'         => 1
        ]);

       $this->assertDatabasehas('personbydomain_client', ['personbydomain_id' => 1]);
       $this->assertDatabasehas('personbydomain_client', ['client_id' => 1]);


       // ACT: execute the method being tested
       $personbydomain = new Personbydomain;
       $result = $personbydomain->getClientId(1);
       

       // ASSERT!
       $this->assertEquals($result, 1);
   }

   /**
    * Test the Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain->getClientId($personbydomain_id) method.
    * Scenario: there is no client associated with a personbydomain in the personbydomain_client pivot table. Return 0 (zero).
    * 
    * @group Client
    * @group ClientPersonbydomaingetclientid
    * @group ClientPersonbydomaingetclientidIsfails
    *
    * @return void
    */
    public function testIsFails()
    {
         // ARRANGE: set up test data
         factory(Client::class, 1)->create();
 
         DB::table('personbydomain_client')->insert([
             'personbydomain_id' => 2,
             'client_id'         => 1
         ]);
 
        $this->assertDatabasehas('personbydomain_client', ['personbydomain_id' => 2]);
        $this->assertDatabasehas('personbydomain_client', ['client_id' => 1]);
 
 
        // ACT: execute the method being tested
        $personbydomain = new Personbydomain;
        $result = $personbydomain->getClientId(1);
 
         
        // ASSERT!
        $this->assertEquals($result, 0);
    }
}