<?php

namespace Tests\Feature\Middleware\Whitelist;

// LaSalle Software
use Lasallesoftware\Library\Firewall\Http\Middleware\Whitelist;

// TestCase
use Tests\TestCase;

// Laravel Framework
use Illuminate\Http\Request;

// Laravel Facades
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class LoginRouteBelongsToWebMiddlewareGroupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * The login route is supposed to belong to the "web" middleware group. Does it?
     *
     * @group middleware
     * @group middlewareWhitelist
     * @group middlewareWhitelistLoginroutebelongstowebmiddlewaregroup
     *
     * @return void
     */
    public function test_LoginRouteBelongsToWebMiddlewareGroupTest()
    {
        echo "\n**Now testing Tests\Feature\Middleware\Whitelist\LoginRouteBelongsToWebMiddlewareGroupTest**";

        $middlewareGroups = Route::getRoutes()->getByName('login')->gatherMiddleware();

        $result = (in_array('web', $middlewareGroups)) ? true : false;

        $this->assertEquals($result, true, 'The "login" route does not belong in the "web" middleware group --> this is bad!');
    }
}
