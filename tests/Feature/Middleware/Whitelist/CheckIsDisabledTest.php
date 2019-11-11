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

class CheckIsDisabledTest extends TestCase
{
    /**
     * HTTP test that I have this middleware check enabled, and the IP Address I am coming from is white listed.
     *
     * @group middleware
     * @group middlewareWhitelist
     * @group middlewareWhitelistCheckisdisabled
     *
     * @return void
     */
    public function test_CheckIsDisabled()
    {
        echo "\n**Now testing Tests\Feature\Middleware\Whitelist\CheckIsDisabledTest**";

        // Set the config parameter to "no" (FYI: every value equals "no", except for one single value: "yes"!)
        Config::set('lasallesoftware-library.web_middleware_do_whitelist_check', 'no');

        // Create request
        $url = env('APP_URL') . '/login';
        $request = Request::create($url, 'GET');

        // Pass the request to the middleware
        $middleware = new \Lasallesoftware\Library\Firewall\Http\Middleware\Whitelist();
        try {
            $response = $middleware->handle($request, function () {});
        } catch (\Throwable $e)  {

        }

        // When the check is not performed, the middleware passes the request to the next operation. This means that
        // the middleware does not return a response! So, the response this test gets back is "no response", ergo NULL.
        $this->assertNull($response);
    }
}
