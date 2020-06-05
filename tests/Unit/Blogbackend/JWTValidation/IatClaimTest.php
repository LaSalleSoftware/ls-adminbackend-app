<?php

namespace Tests\Unit\Blogbackend\JWTValidation;

// LaSalle Software
use Lasallesoftware\Librarybackend\JWT\Validation\JWTValidation;

// Laravel classes
use Tests\TestCase;

// Laravel facade
use Illuminate\Support\Facades\Config;

// Third party classes
use Lcobucci\JWT\Builder;

class IatClaimTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that an incoming JWT's IAT claim ("issued at time") plus a time span specified in the config is valid.
     *
     * The difference between an EXP claim and what I am doing here with the IAT claim, is where the "alive" time duration
     * is specified. The front-end app specifies the EXP claim (ie, when the JWT EXPires). The API (back-end) app
     * specifies how long after the JWT's IAT until that JWT's expiration. It may not make much of a difference here
     * where we control both the front and back ends; however, when you don't control both of 'em, it's good that "both
     * sides" have claims.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationIatclaimtest
     * @group BlogbackendAPIJWTValidationIatclaimtestIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\IatClaimTest**";

        // Arrange
        $time  = time();

        config::set('lasallesoftware-librarybackend.lasalle_jwt_iat_claim_valid_for_how_many_seconds', 120);

        $token = (new Builder())
            ->issuedBy('https://lasallesoftware.ca')         // Configures the issuer (iss claim) (frontend.com)
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')  // Configures the audience (aud claim) (backend.com)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // *** Configures the time that the token was issue (iat claim) ***
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;


        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isIatClaimValid($token);


        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Test that an incoming JWT's IAT claim ("issued at time") plus a time span specified in the config is not valid.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationIatclaimtest
     * @group BlogbackendAPIJWTValidationIatclaimtestNotvalid
     *
     * @return void
     */
    public function testNotValid()
    {
        // Arrange
        $time  = time() - 600;

        config::set('lasallesoftware-librarybackend.lasalle_jwt_iat_claim_valid_for_how_many_seconds', 120);

        $token = (new Builder())
            ->issuedBy('https://LaSalleSoftware.ca')        // Configures the issuer (iss claim) (frontend.com)
            ->permittedFor('http://wrong_domain.ca')      // Configures the audience (aud claim) (backend.com)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // *** Configures the time that the token was issue (iat claim) **
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;

        // Act
        $jwtValidation = new JWTValidation();

        $result = $jwtValidation->isIatClaimValid($token);


        // Assert false
        $this->assertFalse($result);
    }
}
