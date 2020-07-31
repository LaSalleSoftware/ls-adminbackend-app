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
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;
use Lasallesoftware\Librarybackend\Profiles\Models\Client;
use Lasallesoftware\Librarybackend\Common\Policies\CommonPolicy;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

// App
use Tests\TestCase;

class CommonPolicyDoesUserHaveClientIdTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        //$this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
    * Test the CommonPolicy class' "public function doesUserHaveClientId($user)"
    * Scenario: is true
    * 
    * @group Client
    * @group ClientCommonpolicydoesuserhaveclientid
    * @group ClientCommonpolicydoesuserhaveclientidIstrue
    *
    * @return void
    */
   public function testIsTrue()
   {
        echo "\n**Now testing Tests\Unit\Library\Clients\CommonPolicyDoesUserHaveClientIdTest**";

        // ARRANGE: set up test data
        $user = Personbydomain::find(1);
        factory(Client::class, 1)->create();

        // assign the podcast_show record client_id #1
        DB::table('personbydomain_client')
              ->insert(['personbydomain_id' => 1, 'client_id' => 1])
        ;


       // ACT: execute the method being tested
       $commonPolicy = new CommonPolicy;
       $result = $commonPolicy->doesUserHaveClientId($user);
      
       

       // ASSERT!
       $this->assertTrue($result);
   }

   /**
    * Test the CommonPolicy class' "public function doesUserHaveClientId($user)"
    * Scenario: is false
    * 
    * @group Client
    * @group ClientCommonpolicydoesuserhaveclientid
    * @group ClientCommonpolicydoesuserhaveclientidIsfalse
    *
    * @return void
    */
    public function testIsFalse()
    { 
         // ARRANGE: set up test data
         $user = Personbydomain::find(1);
         factory(Client::class, 1)->create();
 
 
        // ACT: execute the method being tested
        $commonPolicy = new CommonPolicy;
        $result = $commonPolicy->doesUserHaveClientId($user);
        
 
        // ASSERT!
        $this->assertFalse($result);
    }
}