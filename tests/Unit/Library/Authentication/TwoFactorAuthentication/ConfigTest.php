<?php

namespace Tests\Unit\Library\Authentication\TwoFactorAuthentication;

// Laravel classes
use Tests\TestCase;


class ConfigTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that the 2FA config is set to false
     *
     * @group Library
     * @group LibraryAuthentication
     * @group LibraryAuthenticationTwofactorauthentication
     * @group LibraryAuthenticationTwofactorauthenticationConfigtest
     * @group LibraryAuthenticationTwofactorauthenticationConfigtestIsfalse
     * 
     * @return void
     */
    public function testIsfalse()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\TwoFactorAuthentication\ConfigTest**";


        // Arrange
        // blank       


        // Act
        config(['lasallesoftware-librarybackend.enable_two_factor_authentication' => false]);
        

        // Assert
        $this->assertFalse(config('lasallesoftware-librarybackend.enable_two_factor_authentication'));        
    }

    /**
     * Test that the 2FA config is set to true
     *
     * @group Twofactorauthentication
     * @group TwofactorauthenticationConfigtest
     * @group TwofactorauthenticationConfigtestIstrue
     *
     * @return void
     */
    public function testIstrue()
    {
        // Arrange
        // blank       


        // Act
        config(['lasallesoftware-librarybackend.enable_two_factor_authentication' => true]);
        

        // Assert
        $this->assertTrue(config('lasallesoftware-librarybackend.enable_two_factor_authentication'));        
    }
}
