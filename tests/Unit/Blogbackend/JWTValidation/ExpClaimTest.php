<?php

namespace Tests\Unit\Blogbackend\JWTValidation;

// LaSalle Software
use Lasallesoftware\Blogbackend\JWT\Validation\JWTValidation;

// Laravel classes
use Tests\TestCase;

// Third party classes
use Lcobucci\JWT\Builder;

class ExpClaimTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that an incoming JWT's correct exp claim will validate.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationExpclaimtest
     * @group BlogbackendAPIJWTValidationExpclaimtestIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\ExpClaimTest**";

        // Arrange
        $time  = time();

        $token = (new Builder())
            ->issuedBy('https://lasallesoftware.ca')         // Configures the issuer (iss claim) (frontend.com)
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')  // Configures the audience (aud claim) (backend.com)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // **** Configures the expiration time of the token (exp claim) ****
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;


        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isExpClaimValid($token);


        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Test that an incoming JWT's wrong exp claim will not validate.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationExpclaimtest
     * @group BlogbackendAPIJWTValidationExpclaimtestNotvalid
     *
     * @return void
     */
    public function testNotValid()
    {
        // Arrange
        $time  = time();


        $token = (new Builder())
            ->issuedBy('https://LaSalleSoftware.ca')        // Configures the issuer (iss claim) (frontend.com)
            ->permittedFor('http://wrong_domain.ca')      // Configures the audience (aud claim) (backend.com)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time - 3600)                     // *** Configures the expiration time of the token (exp claim) ***
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();

        $result = $jwtValidation->isExpClaimValid($token);


        // Assert false
        $this->assertFalse($result);
    }
}
