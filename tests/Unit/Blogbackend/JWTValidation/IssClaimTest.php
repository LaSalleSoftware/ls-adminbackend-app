<?php

namespace Tests\Unit\Blogbackend\JWTValidation;

// LaSalle Software
use Lasallesoftware\Blogbackend\JWT\Validation\JWTValidation;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Third party classes
use Lcobucci\JWT\Builder;

class IssClaimTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that an incoming JWT's correct iss claim will validate.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationIssclaimtest
     * @group BlogbackendAPIJWTValidationIssclaimtestIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\IssClaimTest**";

        // Arrange
        $time  = time();
        $token = (new Builder())
            ->issuedBy('lasallesoftware.ca')                 // *** Configures the issuer (iss claim) (frontend.com) ***
            ->permittedFor('http://backend.com')           // Configures the audience (aud claim) (backend.com)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isIssClaimValid($token);

        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Test that an incoming JWT's wrong iss claim will not validate.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationIssclaimtest
     * @group BlogbackendAPIJWTValidationIssclaimtestNotvalid
     *
     * @return void
     */
    public function testNotValid()
    {
        // Arrange
        $time  = time();
        $token = (new Builder())
            ->issuedBy('wrong_domain.ca')                    // *** Configures the issuer (iss claim) (frontend.com) ***
            ->permittedFor('http://backend.com')           // Configures the audience (aud claim) (backend.com)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();

        $result = $jwtValidation->isIssClaimValid($token);

        // Assert false
        $this->assertFalse($result);
    }
}
