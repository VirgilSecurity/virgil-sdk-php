<?php
namespace Virgil\Sdk\Client\Card;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\VirgilServices\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

use Virgil\Sdk\Contracts\BufferInterface;

/**
 * Class transforms signed response model to card and vise versa.
 */
class SignedResponseCardMapper implements CardMapperInterface
{
    /**
     * Creates Card from SignedResponseModel.
     *
     * @param SignedResponseModel $signedResponseModel
     *
     * @return Card
     */
    public function toCard($signedResponseModel)
    {
        $responseCardModelContent = $signedResponseModel->getCardContent();
        $responseCardModelContentInfo = $responseCardModelContent->getInfo();
        $responseCardModelMeta = $signedResponseModel->getMeta();

        $responseModelSignsToCardSigns = function ($sign) {
            return Buffer::fromBase64($sign);
        };

        $cardSigns = array_map($responseModelSignsToCardSigns, $responseCardModelMeta->getSigns());

        return new Card(
            $signedResponseModel->getId(),
            Buffer::fromBase64($signedResponseModel->getSnapshot()),
            $responseCardModelContent->getIdentity(),
            $responseCardModelContent->getIdentityType(),
            Buffer::fromBase64($responseCardModelContent->getPublicKey()),
            $responseCardModelContent->getScope(),
            $responseCardModelContent->getData(),
            $responseCardModelContentInfo->getDevice(),
            $responseCardModelContentInfo->getDeviceName(),
            $responseCardModelMeta->getCardVersion(),
            $cardSigns,
            $responseCardModelMeta->getCreatedAt()
        );
    }


    /**
     * Creates SignedResponseModel from Card.
     *
     * @param Card $card
     *
     * @return SignedResponseModel
     */
    public function toModel(Card $card)
    {
        $id = $card->getId();
        $contentSnapshot = $card->getSnapshot();

        $cardContent = new CardContentModel(
            $card->getIdentity(),
            $card->getIdentityType(),
            $card->getPublicKeyData()
                 ->toBase64(),
            $card->getScope(),
            $card->getData(),
            new DeviceInfoModel($card->getDevice(), $card->getDeviceName())
        );

        $signsToBase64 = function (BufferInterface $sign) {
            return $sign->toBase64();
        };

        $signs = array_map($signsToBase64, $card->getSignatures());

        $cardMeta = new SignedResponseMetaModel($signs, $card->getCreatedAt(), $card->getVersion());

        return new SignedResponseModel($id, $contentSnapshot->toBase64(), $cardContent, $cardMeta);
    }
}
