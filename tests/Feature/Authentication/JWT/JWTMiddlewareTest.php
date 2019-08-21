<?php

namespace Tests\Feature\Authentication\JWT;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

// Third party classes
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;


class JWTMiddlewareTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

        // Insert the key the JWT uses. This insert represents the API domain.
        DB::table('installed_domains_jwt_keys')->insert([
            'installed_domain_id' => 5,
            'key'                 => 'correct-key',
            'enabled'             => 1,
            'created_at'          => now(),
            'created_by'          => 1
        ]);

    }

    /**
     * Test a get endpoint with the JWTMiddleware invoked.
     *
     *          *** THIS TEST USES A TEMPORARY ENDPOINT. EXPECT TO NEED IT CHANGED LATER!! ***
     *
     * @group authentication
     * @group authenticationJwt
     * @group authenticationJwtJWTMiddleware
     * @group authenticationJwtJWTMiddlewareValidates
     */
    public function testValidates()
    {
        echo "\n**Now testing Tests\Feature\Authentication\JWT\JWTMiddlewareTest**";

        // Arrange
        $time   = time();
        $signer = new Sha256();

        // This is the token that is simulated to come from the client domain. Using the same key as the API domain.
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
        $response = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json',])
            ->get('/api/v1/testapi')
        ;

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test a get endpoint with the JWTMiddleware invoked.
     *
     *          *** THIS TEST USES A TEMPORARY ENDPOINT. EXPECT TO NEED IT CHANGED LATER!! ***
     *
     * @group authentication
     * @group authenticationJwt
     * @group authenticationJwtJWTMiddleware
     * @group authenticationJwtJWTMiddlewareNotvalidates
     */
    public function testNotValidates()
    {
        echo "\n**Now testing Tests\Feature\Authentication\JWT\JWTMiddlewareTest**";

        // Arrange
        $time   = time();
        $signer = new Sha256();

        // This is the token that is simulated to come from the client domain. Using a different key as the API domain.
        $token = (new Builder())
            ->issuedBy('hackintosh.lsv2-basicfrontend-app.com')
            ->permittedFor('hackintosh.lsv2-adminbackend-app.com')
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key('wrong-key'))  // *** GENERATES AND SIGNS THE TOKEN
        ;

        // Act
        $response = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json',])
            ->get('/api/v1/testapi')
        ;

        // Assert
        $response->assertStatus(403);
    }
}
