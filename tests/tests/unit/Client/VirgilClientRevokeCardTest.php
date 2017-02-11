<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Client\Requests\Constants\RevocationReasons;

use Virgil\Sdk\Client\Requests\RevokeCardRequest;

use Virgil\Sdk\Tests\Unit\Client\Requests\CardRequest;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\RequestModel;

class VirgilClientRevokeCardTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider getRevokeDataProvider
     *
     * @param RevokeCardRequest $revokeCardRequestArgs
     *
     * @test
     */
    public function revokeCard__withRevokeCardRequest__returnsSelfOnSuccess(
        $revokeCardRequestArgs
    ) {
        $this->configureCardsServiceResponse($revokeCardRequestArgs, []);

        $revokeCardRequest = CardRequest::createRevokeCardRequest(...$revokeCardRequestArgs);


        $virgilClient = $this->virgilClient->revokeCard($revokeCardRequest);


        $this->assertEquals($this->virgilClient, $virgilClient);
    }


    public function getRevokeDataProvider()
    {
        return [
            [
                [
                    ['model-id-1', RevocationReasons::TYPE_UNSPECIFIED],
                    ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                ],
            ],
            [
                [
                    ['model-id-2', RevocationReasons::TYPE_COMPROMISED],
                    ['sign-id-4' => 'X3NpZ240'],
                ],
            ],
        ];
    }


    protected function configureCardsServiceResponse($revokeCardRequestModelRequestArgs, $response)
    {
        $revokeCardRequestModel = RequestModel::createRevokeCardRequestModel(...$revokeCardRequestModelRequestArgs);

        $this->cardsServiceMock->expects($this->once())
                               ->method('delete')
                               ->with($revokeCardRequestModel)
                               ->willReturn($response)
        ;
    }
}
