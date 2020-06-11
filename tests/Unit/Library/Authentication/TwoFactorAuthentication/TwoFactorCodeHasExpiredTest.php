<?php

namespace Tests\Unit\Library\Authentication\TwoFactorAuthentication;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\Authentication\Models\TwoFactorAuthentication;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

// Third party classes
use Carbon\CarbonImmutable;


class TwoFactorCodeHasExpiredTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * Test that a two factor code has expired
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationTwofactorauthentication
     * @group LibraryAuthenticationTwofactorauthenticationTwofactorcodehasexpired
     * @group LibraryAuthenticationTwofactorauthenticationTwofactorcodehasexpiredHasexpired
     *
     * @return void
     */
    public function testHasExpired()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\TwoFactorAuthenticationt\TwoFactorCodeHasExpiredTest**";


        // Arrange       
        $data = ['email' => 'bob.bloom@lasallesoftware.ca', 'two_factor_code' => 'hello'];
        $twofactorauthentication = new TwoFactorAuthentication;
        $twofactorauthentication->createNewTwoFactorAuthenticationRecord($data);

        // double-check that the records exist
        $this->assertDatabaseHas('twofactorauthentication', ['email' => $data['email']]);


        $now = CarbonImmutable::now('America/New_York');
        DB::table('twofactorauthentication')
              ->where('email', $data['email'])
              ->update(['created_at' => $now->subMinutes(70)])
        ;

        config(['lasallesoftware-librarybackend.number_of_minutes_until_a_two_factor_code_expires' => 10]);


        // Act
        $isExpired = $twofactorauthentication->isTwoFactorCodeExpired($data['email']);
        

        // Assert
        $this->assertTrue($isExpired);
    }


    /**
     * Test that a two factor code has not expired
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationTwofactorauthentication
     * @group LibraryAuthenticationTwofactorauthenticationTwofactorcodehasexpired
     * @group LibraryAuthenticationTwofactorauthenticationTwofactorcodehasexpiredHasnotexpired
     *
     * @return void
     */
    public function testHasNotExpired()
    {
        // Arrange       
        $data = ['email' => 'bob.bloom@lasallesoftware.ca', 'two_factor_code' => 'hello'];
        $twofactorauthentication = new TwoFactorAuthentication;
        $twofactorauthentication->createNewTwoFactorAuthenticationRecord($data);

        // double-check that the records exist
        $this->assertDatabaseHas('twofactorauthentication', ['email' => $data['email']]);

        config(['lasallesoftware-librarybackend.number_of_minutes_until_a_two_factor_code_expires' => 10]);


        // Act
        $isExpired = $twofactorauthentication->isTwoFactorCodeExpired($data['email']);
        

        // Assert
        $this->assertFalse($isExpired);
    }
}
