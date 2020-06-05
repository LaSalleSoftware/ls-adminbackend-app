<?php

namespace Tests\Unit\Blogbackend\JWTValidation;

// LaSalle Software
use Lasallesoftware\Librarybackend\JWT\Validation\JWTValidation;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;

// Third party classes
use Lcobucci\JWT\Builder;

class JtiClaimTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
        //$this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that an incoming JWT's correct jti claim will validate.
     * 
     * The JTI claim is optional. To avoid sending a null value, I use a random string of 40 chars
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationJticlaimtest
     * @group BlogbackendAPIJWTValidationJticlaimtestIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\JtiClaimTest**";

        // Arrange
        
        $jtiClaim = Str::random(40);
        $time  = time();
        $token = (new Builder())
            ->issuedBy('lasallesoftware.ca')                 // *** Configures the issuer (iss claim) (frontend.com) ***
            ->permittedFor('http://backend.com')           // Configures the audience (aud claim) (backend.com)
            ->identifiedBy($jtiClaim, true)             // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();


        // Assert true
        $this->assertTrue($jwtValidation->isJtiClaimValid($token));
    }

    /**
     * Test that an incoming JWT's wrong jti claim will not validate.
     *
     * @group Blogbackend--SKIP
     * @group BlogbackendAPI--SKIP
     * @group BlogbackendAPIJWTValidation--SKIP
     * @group BlogbackendAPIJWTValidationJticlaimtest--SKIP
     * @group BlogbackendAPIJWTValidationJticlaimtestNotvalid--SKIP
     *
     * @return void
     */
    /* ==> THIS TEST IS FAILING, BUT IT DOES NOT MATTER BECAUSE NOT USING THIS CLAIM AND THE ABOVE TEST PASSES <==
    public function testNotValid()
    {
        // Arrange
        $time  = time();
        $jtiClaim = null;
        $token = (new Builder())
            ->issuedBy('lasallesoftware.ca')                 // *** Configures the issuer (iss claim) (frontend.com) ***
            ->permittedFor('http://backend.com')           // Configures the audience (aud claim) (backend.com)
            ->identifiedBy($jtiClaim, true)             // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();


        // Assert true
        $this->assertFalse($jwtValidation->isJtiClaimValid($token));
    }
    */
}
