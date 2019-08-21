<?php

namespace Tests\Unit\Blogbackend\JWTValidation;

// LaSalle Software
use Lasallesoftware\Blogbackend\JWT\Validation\JWTValidation;

// Laravel classes
use Tests\TestCase;

// Laravel facade
use Illuminate\Support\Facades\Config;

// Third party classes
use Lcobucci\JWT\Builder;

class AudClaimTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that an incoming JWT's correct aud claim will validate.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationAudclaimtest
     * @group BlogbackendAPIJWTValidationAudclaimtestIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\AudClaimTest**";

        // Arrange
        $time  = time();

        config::set('lasallesoftware-library.lasalle_app_domain_name', 'hackintosh.lsv2-adminbackend-app.com');

        $token = (new Builder())
            ->issuedBy('https://lasallesoftware.ca')         // Configures the issuer (iss claim) (frontend.com)
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')  // *** Configures the audience (aud claim) (backend.com) ***
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;


        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isAudClaimValid($token);


        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Test that an incoming JWT's wrong aud claim will not validate.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationAudclaimtest
     * @group BlogbackendAPIJWTValidationAudclaimtestNotvalid
     *
     * @return void
     */
    public function testNotValid()
    {
        // Arrange
        $time  = time();

        config::set('lasallesoftware-library.lasalle_app_domain_name', 'hackintosh.lsv2-adminbackend-app.com');

        $token = (new Builder())
            ->issuedBy('https://LaSalleSoftware.ca')        // Configures the issuer (iss claim) (frontend.com)
            ->permittedFor('http://wrong_domain.ca')      // *** Configures the audience (aud claim) (backend.com) ***
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();

        $result = $jwtValidation->isAudClaimValid($token);

        // Assert false
        $this->assertFalse($result);
    }
}
