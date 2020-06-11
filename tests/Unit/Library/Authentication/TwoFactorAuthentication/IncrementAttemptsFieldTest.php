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


class IncrementAttemptsFieldTest extends TestCase
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
     * @group LibraryAuthenticationTwofactorauthenticationIncrementattemptsfield
     * @group LibraryAuthenticationTwofactorauthenticationIncrementattemptsfieldIncrementssuccessfully
     *
     * @return void
     */
    public function testIncrementsSuccessfully()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\TwoFactorAuthenticationt\IncrementAttemptsFieldTest**";


        // Arrange       
        $data = ['email' => 'bob.bloom@lasallesoftware.ca', 'two_factor_code' => 'hello'];
        $twofactorauthentication = new TwoFactorAuthentication;
        $twofactorauthentication->createNewTwoFactorAuthenticationRecord($data);

        // double-check that the records exist
        $this->assertDatabaseHas('twofactorauthentication', ['email' => $data['email']]);
        $this->assertDatabaseHas('twofactorauthentication', ['two_factor_code' => $data['two_factor_code']]);
        
        // the "number_of_attempts_to_validate" field should be zero
        $this->assertDatabaseHas('twofactorauthentication', ['number_of_attempts_to_validate' => 0]);


        // Act
        $twofactorauthentication->incrementNumberOfAttemptsToValidate($data['email']);

        
        // Assert
        $this->assertDatabaseHas('twofactorauthentication', ['number_of_attempts_to_validate' => 1]);
    }
}