<?php

namespace Tests\Feature\Middleware\Whitelist;

// Laravel Framework
use Tests\TestCase;
use Illuminate\Http\Request;

// Laravel Facades
use Illuminate\Support\Facades\Config;

class CheckIsEnabledAndPassesTest extends TestCase
{
    /**
     * HTTP test that I have this middleware check enabled, and the IP Address I am coming from is NOT white listed.
     *
     * @group middleware
     * @group middlewareWhitelist
     * @group middlewareWhitelistCheckisenabledandpasses
     *
     * @return void
     */
    public function test_CheckIsEnabledAndPasses()
    {
        echo "\n**Now testing Tests\Feature\Middleware\Whitelist\CheckIsEnabledAndPassesTest**";

        // Set the config parameter to "yes" (FYI: every value equals "no", except for one single value: "yes"!)
        Config::set('lasallesoftware-library.web_middleware_do_whitelist_check', 'yes');

        // Create the request
        $url = env('APP_URL') . '/login';
        $request = Request::create($url, 'GET');

        // Mock the middleware so we can use custom values for some methods.
        // The methods listed in setMethods() will return NULL. The methods not listed will run original code.
        $middleware = $this->getMockBuilder(\Lasallesoftware\Library\Firewall\Http\Middleware\Whitelist::class)
            ->setMethods(['getWhitelistedIpAddresses', 'getRemoteIpAddress'])
            ->getMock()
        ;
        $middleware->method('getWhitelistedIpAddresses')
            ->willReturn([
                '151.101.193.153',
                '151.101.193.154',
                '151.101.193.155'
            ])
        ;
        $middleware->method('getRemoteIpAddress')
            ->willReturn('151.101.193.154')
        ;

        // Run the middleware with the request created above.
        // The middleware will either (a) continue the request to the next operation, meaning that there is no response
        // (generally a good thing) or (b) return an exception due to an abort(), meaning that the request was not allowed
        // to continue onward.
        try {
            $middlewareResponse = $middleware->handle($request, function (){});
        } catch (\Throwable $e)  {
            // blank on purpose
        }

        // Double check that the object stubs' returned values are correct
        // https://phpunit.de/manual/6.5/en/test-doubles.html#test-doubles.stubs.examples.StubTest5.php
        $this->assertEquals('151.101.193.154', $middleware->getRemoteIpAddress());
        $this->assertEquals([
            '151.101.193.153',
            '151.101.193.154',
            '151.101.193.155'
        ], $middleware->getWhitelistedIpAddresses());

        // The check should have passed, resulting in no response back from the middleware
        $this->assertNull($middlewareResponse);
    }
}
