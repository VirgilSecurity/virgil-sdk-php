<?php
namespace Virgil\Sdk\Tests\Unit\Client\Card;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\SignedResponseCardMapper;

use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

use Virgil\Sdk\Tests\BaseTestCase;

class Base64CardSerializerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function serialize__card__returnsBase64EncodedSerializedCard()
    {
        $signedResponseCardMapperMock = $this->createSignedResponseCardMapper();
        $signedResponseModelMapperMock = $this->createSignedResponseModelMapper();

        $card = $this->createCard();

        $this->configuredSerializeMocks($card, $signedResponseCardMapperMock, $signedResponseModelMapperMock);


        $base64CardSerializer = $this->createBase64CardSerializer(
            $signedResponseCardMapperMock,
            $signedResponseModelMapperMock
        );


        $base64CardSerializer->serialize($card);
    }


    /**
     * @test
     */
    public function unserialize__base64EncodedSerializedCard__returnsCard()
    {
        $signedResponseCardMapperMock = $this->createSignedResponseCardMapper();
        $signedResponseModelMapperMock = $this->createSignedResponseModelMapper();

        $serializedCard = 'base64 Encoded Serialized Card';

        $this->configuredUnserializeMocks(
            $serializedCard,
            $signedResponseCardMapperMock,
            $signedResponseModelMapperMock
        );


        $base64CardSerializer = $this->createBase64CardSerializer(
            $signedResponseCardMapperMock,
            $signedResponseModelMapperMock
        );
        $base64EncodedSerializedCard = base64_encode($serializedCard);


        $base64CardSerializer->unserialize($base64EncodedSerializedCard);
    }


    /**
     *
     * @return Card
     */
    protected function createCard()
    {
        return $this->createMock(Card::class);
    }


    /**
     * @return SignedResponseModel
     */
    protected function createSignedResponseModel()
    {
        return $this->createMock(SignedResponseModel::class);
    }


    /**
     * @param $signedResponseCardMapper
     * @param $signedResponseModelMapper
     *
     * @return Base64CardSerializer
     */
    protected function createBase64CardSerializer(
        SignedResponseCardMapper $signedResponseCardMapper,
        SignedResponseModelMapper $signedResponseModelMapper
    ) {
        return new Base64CardSerializer($signedResponseCardMapper, $signedResponseModelMapper);
    }


    /**
     * @return SignedResponseCardMapper
     */
    protected function createSignedResponseCardMapper()
    {
        return $this->createMock(SignedResponseCardMapper::class);
    }


    /**
     * @return SignedResponseModelMapper
     */
    protected function createSignedResponseModelMapper()
    {
        return $this->createMock(SignedResponseModelMapper::class);
    }


    /**
     * @param Card                                    $card
     * @param PHPUnit_Framework_MockObject_MockObject $signedResponseCardMapperMock
     * @param PHPUnit_Framework_MockObject_MockObject $signedResponseModelMapperMock
     */
    protected function configuredSerializeMocks(
        Card $card,
        PHPUnit_Framework_MockObject_MockObject $signedResponseCardMapperMock,
        PHPUnit_Framework_MockObject_MockObject $signedResponseModelMapperMock
    ) {

        $signedResponseModel = $this->createSignedResponseModel();

        $signedResponseCardMapperMock->expects($this->once())
                                     ->method('toModel')
                                     ->with($card)
                                     ->willReturn($signedResponseModel)
        ;

        $signedResponseModelMapperMock->expects($this->once())
                                      ->method('toJson')
                                      ->with($signedResponseModel)
        ;
    }


    /**
     * @param string                                  $serializedCard
     * @param PHPUnit_Framework_MockObject_MockObject $signedResponseCardMapperMock
     * @param PHPUnit_Framework_MockObject_MockObject $signedResponseModelMapperMock
     */
    protected function configuredUnserializeMocks(
        $serializedCard,
        PHPUnit_Framework_MockObject_MockObject $signedResponseCardMapperMock,
        PHPUnit_Framework_MockObject_MockObject $signedResponseModelMapperMock
    ) {

        $signedResponseModel = $this->createSignedResponseModel();

        $signedResponseCardMapperMock->expects($this->once())
                                     ->method('toCard')
                                     ->with($signedResponseModel)
        ;

        $signedResponseModelMapperMock->expects($this->once())
                                      ->method('toModel')
                                      ->with($serializedCard)
                                      ->willReturn($signedResponseModel)
        ;
    }
}
