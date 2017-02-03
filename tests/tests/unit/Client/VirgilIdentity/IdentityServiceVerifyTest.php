<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilIdentity;


use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyResponseModel;

class IdentityServiceVerifyTest extends AbstractIdentityServiceTest
{
    /**
     * @test
     */
    public function verify__withVerifyRequest__returnsValidResponse()
    {
        $expectedResponse = new VerifyResponseModel();
        $verifyIdentityRequest = new VerifyRequestModel();


        $verifyIdentityResponse = $this->virgilService->verify($verifyIdentityRequest);


        $this->assertEquals($expectedResponse, $verifyIdentityResponse);
    }
}
