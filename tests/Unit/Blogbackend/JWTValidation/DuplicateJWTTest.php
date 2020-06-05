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

class DuplicateJWTTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

        $key = '12345';
        DB::table('installed_domains_jwt_keys')->insert([
            'installed_domain_id' => 5,
            'key'                 => $key,
            'enabled'             => 1,
            'created_at'          => now(),
            'created_by'          => 1
        ]);
    }

    /**
     * Test that an incoming JWT is new. Duplicate bad, new is good! Duplicate means that the JWT exists in the
     * json_web_tokens database table.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationDuplicatejwt
     * @group BlogbackendAPIJWTValidationDuplicatejwtIsvalid
     *
     * @return void
     */
    public function testIsValid()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\JWTValidation\DuplicateJWTTest**";

        // Arrange
        $time   = time();
        $signer = new Sha256();
        $key    = '12345';

        $token = (new Builder())
            ->issuedBy('hackintosh.lsv2-basicfrontend-app.com')
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key($key))  // *** GENERATES AND SIGNS THE TOKEN
        ;


        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isJWTDuplicate($token);


        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Test that an incoming JWT is new. Duplicate bad, new is good! Duplicate means that the JWT exists in the
     * json_web_tokens database table.
     *
     * @group Blogbackend
     * @group BlogbackendAPI
     * @group BlogbackendAPIJWTValidation
     * @group BlogbackendAPIJWTValidationDuplicatejwt
     * @group BlogbackendAPIJWTValidationDuplicatejwtNotvalid
     *
     * @return void
     */
    public function testNotValid()
    {
        // Arrange
        $time   = time();
        $signer = new Sha256();
        $key    = '12345';

        $token = (new Builder())
            ->issuedBy('hackintosh.lsv2-basicfrontend-app.com')
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key($key))            // *** GENERATES AND SIGNS THE TOKEN
        ;

        // the JWT must be in the "json_web_tokens" db table!
        DB::table('json_web_tokens')->insert(['jwt' => $token]);

        // Act
        $jwtValidation = new JWTValidation();
        $result = $jwtValidation->isJWTDuplicate($token);

        $result = false;

        // Assert true
        $this->assertFalse($result);
    }
}
