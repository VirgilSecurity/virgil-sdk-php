<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Client\Requests\Constants\RevocationReasons;
use Virgil\Sdk\Client\Requests\CreateCardRequest;

use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Tests\Unit\Client\Requests\CardRequest;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\RequestModel;

class VirgilClientRevokeGlobalCardTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider getRevokeGlobalCardDataProvider
     *
     * @param SignedRequestModel $revokeCardRequestModelArgs
     * @param CreateCardRequest  $revokeGlobalCardRequestArgs
     *
     * @test
     */
    public function revokeGlobalCard__withRevokeGlobalCardRequest__returnsSelfOnSuccess(
        $revokeGlobalCardRequestArgs,
        $revokeCardRequestModelArgs
    ) {
        $revokeGlobalCardRequest = CardRequest::createRevokeGlobalCardRequest(...$revokeGlobalCardRequestArgs);

        $this->configureVirgilServiceResponse($revokeCardRequestModelArgs, []);


        $virgilClient = $this->virgilClient->revokeGlobalCard($revokeGlobalCardRequest);


        $this->assertEquals($this->virgilClient, $virgilClient);
    }


    public function getRevokeGlobalCardDataProvider()
    {
        return [
            [
                [
                    [
                        'model-id-1',
                        RevocationReasons::TYPE_UNSPECIFIED,
                        new ValidationModel(
                            'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A'
                        ),
                    ],
                    ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                ],
                [
                    [
                        'model-id-1',
                        RevocationReasons::TYPE_UNSPECIFIED,
                    ],
                    [
                        ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                        'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                    ],
                ],
            ],
        ];
    }


    protected function configureVirgilServiceResponse($createCardRequestModelRequestArgs, $response)
    {
        $revokeRequestModel = RequestModel::createRevokeCardRequestModel(...$createCardRequestModelRequestArgs);

        $this->registrationAuthorityServiceMock->expects($this->once())
                                               ->method('delete')
                                               ->with($revokeRequestModel)
                                               ->willReturn($response)
        ;
    }
}
