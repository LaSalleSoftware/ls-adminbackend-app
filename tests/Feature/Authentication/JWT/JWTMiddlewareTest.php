<?php

namespace Tests\Feature\Authentication\JWT;

// LaSalle Software
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator;

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

        $uuidGenerator = new UuidGenerator();
        $uuid          = $uuidGenerator->createUuid(1);

        $jwtKey        = DB::table('installed_domains_jwt_keys')->where('installed_domain_id', 5)->pluck('key')->first();
        $clientDomain  = DB::table('installed_domains')->where('id', 5)->pluck('title')->first();
        $backendDomain = DB::table('installed_domains')->where('id', 1)->pluck('title')->first();

        // This is the token that is simulated to come from the client domain. Using the same key as the API domain.
        $token = (new Builder())
            ->issuedBy($clientDomain)
            ->permittedFor($backendDomain)
            ->identifiedBy($uuid, true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key($jwtKey))  // *** GENERATES AND SIGNS THE TOKEN
        ;

        // Act
        $response = $this
            ->withHeaders([
                'Authorization'    => 'Bearer ' . $token,
                'RequestingDomain' => $backendDomain,
                'Accept'           => 'application/json',
                ])
            ->get('/api/v1/allblogposts')
        ;

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test a get endpoint with the JWTMiddleware invoked.
     *
     * Fails due to bad JWT key.
     *
     * @group authentication
     * @group authenticationJwt
     * @group authenticationJwtJWTMiddleware
     * @group authenticationJwtJWTMiddlewareNotvalidatesduetobadjwtkey
     */
    public function testNotValidatesDueToBadJWTKey()
    {
        // Arrange
        $time   = time();
        $signer = new Sha256();

        $uuidGenerator = new UuidGenerator();
        $uuid          = $uuidGenerator->createUuid(1);

        $jwtKey        = 'wrong-key';
        $clientDomain  = DB::table('installed_domains')->where('id', 5)->pluck('title')->first();
        $backendDomain = DB::table('installed_domains')->where('id', 1)->pluck('title')->first();

        // This is the token that is simulated to come from the client domain. Using a different key as the API domain.
        $token = (new Builder())
            ->issuedBy($clientDomain)
            ->permittedFor($backendDomain)
            ->identifiedBy($uuid, true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key($jwtKey))                       // not validate because the key is wrong
        ;

        // Act
        $response = $this
            ->withHeaders([
                'Authorization'    => 'Bearer ' . $token,
                'RequestingDomain' => $backendDomain,
                'Accept'           => 'application/json',
            ])
            ->get('/api/v1/allblogposts')
        ;

        // Assert
        $response->assertStatus(403);
    }

    /**
     * Test a get endpoint with the JWTMiddleware invoked.
     *
     * Fails due to bad UUID in the JTI claim.
     *
     * @group authentication
     * @group authenticationJwt
     * @group authenticationJwtJWTMiddleware
     * @group authenticationJwtJWTMiddlewareNotvalidatesduetobaduuid
     */
    public function testNotValidatesDueToBadUuid()
    {
        // Arrange
        $time   = time();
        $signer = new Sha256();

        $uuid          = 'wrong-uuid';

        $jwtKey        = DB::table('installed_domains_jwt_keys')->where('installed_domain_id', 5)->pluck('key')->first();
        $clientDomain  = DB::table('installed_domains')->where('id', 5)->pluck('title')->first();
        $backendDomain = DB::table('installed_domains')->where('id', 1)->pluck('title')->first();

        // This is the token that is simulated to come from the client domain. Using a different key as the API domain.
        $token = (new Builder())
            ->issuedBy($clientDomain)
            ->permittedFor($backendDomain)
            ->identifiedBy($uuid, true)         // not validate due to wrong JTI claim (ie, no uuid)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($signer, new Key($jwtKey))
        ;

        // Act
        $response = $this
            ->withHeaders([
                'Authorization'    => 'Bearer ' . $token,
                'RequestingDomain' => $backendDomain,
                'Accept'           => 'application/json',
            ])
            ->get('/api/v1/allblogposts')
        ;

        // Assert
        $response->assertStatus(403);
    }
}
