<?php

namespace Tests\Unit\Blogbackend\JWTValidation;

// LaSalle Software
use Lasallesoftware\Librarybackend\JWT\Validation\JWTValidation;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

// Third party classes
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class SignatureTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

        DB::table('installed_domains_jwt_keys')->insert([
            'installed_domain_id' => 5,
            'key'                 => 'correct-key',
            'enabled'             => 1,
            'created_at'          => now(),
            'created_by'          => 1
        ]);
    }

    /**
     * Test that an incoming JWT's signature is valid.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationSignature
     * @group BlogbackendAPIJWTValidationSignatureIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\SignatureTest**";

        // Arrange
        $time   = time();
        $signer = new Sha256();

        $token = (new Builder())
            ->issuedBy('hackintosh.lsv2-basicfrontend-app.com')
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key('correct-key'))  // *** GENERATES AND SIGNS THE TOKEN
        ;


        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isSignatureValid($token);


        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Test that an incoming JWT's signature is not valid.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationSignature
     * @group BlogbackendAPIJWTValidationSignatureNotvalid
     *
     * @return void
     */
    public function testNotValid()
    {
        // Arrange
        $time   = time();
        $signer = new Sha256();

        $token = (new Builder())
            ->issuedBy('hackintosh.lsv2-basicfrontend-app.com')
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key('wrong-key'))            // *** GENERATES AND SIGNS THE TOKEN
        ;


        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isSignatureValid($token);


        // Assert true
        $this->assertFalse($result);
    }
}
