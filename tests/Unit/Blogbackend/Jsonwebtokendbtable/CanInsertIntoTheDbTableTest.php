<?php

namespace Tests\Unit\Blogbackend\Jsonwebtokendbtable;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Json_web_token;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Third party classes
use Lcobucci\JWT\Builder;

class CanInsertIntoTheDbTableTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that we can insert a jwt into the json_web_tokens database table.
     *
     * @group Blogbackend
     * @group BlogbackendJsonwebtokendbtable
     * @group BlogbackendJsonwebtokendbtableCaninsertintoihecbTable
     * @group BlogbackendJsonwebtokendbtableCaninsertintothedbTableCaninsert
     *
     * @return void
     */
    public function testCanInsert()
    {
        echo "\n**Now testing Tests\Unit\Blogbackend\Jsonwebtokendbtable\CanInsertIntoTheDbTableTest**";

        // Arrange
        $time  = time();
        $token = (new Builder())
            ->issuedBy('lasallesoftware.ca')                 // *** Configures the issuer (iss claim) (frontend.com) ***
            ->permittedFor('http://backend.com')           // Configures the audience (aud claim) (backend.com)
            ->identifiedBy(123456, true)             // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)                                       // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60)              // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600)                     // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', 1)                      // Configures a new claim, called "uid"
            ->getToken()                                            // Retrieves the generated token
        ;


        // Act
        $json_web_token = new Json_web_token();
        $json_web_token->saveWithJWT($token);


        // Assert
        $this->assertDatabaseHas('json_web_tokens', ['jwt' => $token]);

        $row = Json_web_token::orderBy('id', 'desc')->first();
        $this->assertEquals($token, $row->jwt);
    }
}
