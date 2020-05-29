<?php

namespace Tests\Unit\Library\Authentication\JWT;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\Models\Json_web_token;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

// Third party classes
use Carbon\CarbonImmutable;


class DeleteExpiredJWTTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
    }

    /**
     * Test that expired JWT's are deleted from the json_web_tokens database table
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationJWT
     * @group LibraryAuthenticationJWTDeleteexpiredjwt
     * @group LibraryAuthenticationJWTDeleteexpiredjwtIsdeletedsuccessfully
     *
     * @return void
     */
    public function testIsDeletedSuccessfully()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\LoginsTable\DeleteExpiredJWTTest**";


        // Arrange
        $now = CarbonImmutable::now('America/New_York');

        DB::table('json_web_tokens')->insert([
            'jwt'        => 'jwt1',
            'created_at' => $now,
        ]);

        DB::table('json_web_tokens')->insert([
            'jwt'        => 'jwt2',
            'created_at' => $now,
        ]);

        // 24 hours x 60 minutes = 1,440 minutes
        DB::table('json_web_tokens')->insert([
            'jwt'        => 'jwt3',
            'created_at' => $now->subMinutes(1450),
        ]);

        // double-check that the records exist
        $this->assertDatabaseHas('json_web_tokens', ['id' => '1']);
        $this->assertDatabaseHas('json_web_tokens', ['id' => '2']);
        $this->assertDatabaseHas('json_web_tokens', ['id' => '3']);

        $json_web_token = $this->getMockBuilder(Json_web_token::class)
            ->setMethods(null)
            //->disableOriginalConstructor()
            ->getMock()
        ;


        // Act
        $json_web_token->deleteExpired();


        // Assert
        $this->assertDatabaseHas('json_web_tokens',     ['id' => '1']);
        $this->assertDatabaseHas('json_web_tokens',     ['id' => '2']);
        $this->assertDatabaseMissing('json_web_tokens', ['id' => '3']);
    }

    /**
     * Test that expired JWT's are deleted from the json_web_tokens database table via the artisan command
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationJWT
     * @group LibraryAuthenticationJWTDeleteexpiredjwt
     * @group LibraryAuthenticationJWTDeleteexpiredjwtIsdeletedsuccessfullyusingartisancommand
     *
     * @return void
     */
    public function testIsDeletedSuccessfullyUsingArtisanCommand()
    {
        // Arrange
        $now = CarbonImmutable::now('America/New_York');

        DB::table('json_web_tokens')->delete();

        DB::table('json_web_tokens')->insert([
            'jwt'        => 'jwt1',
            'created_at' => $now,
        ]);

        DB::table('json_web_tokens')->insert([
            'jwt'        => 'jwt2',
            'created_at' => $now,
        ]);

        // 24 hours x 60 minutes = 1,440 minutes
        DB::table('json_web_tokens')->insert([
            'jwt'        => 'jwt3',
            'created_at' => $now->subMinutes(1450),
        ]);

        // double-check that the records exist
        $this->assertDatabaseHas('json_web_tokens', ['id' => '1']);
        $this->assertDatabaseHas('json_web_tokens', ['id' => '2']);
        $this->assertDatabaseHas('json_web_tokens', ['id' => '3']);

        // Act
        $this->artisan('lslibrary:deleteexpiredjwt');


        // Assert
        $this->assertDatabaseHas('json_web_tokens',     ['id' => '1']);
        $this->assertDatabaseHas('json_web_tokens',     ['id' => '2']);
        $this->assertDatabaseMissing('json_web_tokens', ['id' => '3']);


    }
}
