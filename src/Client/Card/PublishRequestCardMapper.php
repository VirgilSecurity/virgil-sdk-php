<?php
namespace Virgil\Sdk\Client\Card;


use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\VirgilServices\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Contracts\BufferInterface;

use Virgil\Sdk\Exceptions\MethodIsDisabledException;

/**
 * Class transforms card to signed request model.
 */
class PublishRequestCardMapper implements CardMapperInterface
{
    /**
     * @inheritdoc
     */
    public function toCard($model)
    {
        throw new MethodIsDisabledException(__METHOD__);
    }


    /**
     * Creates publish card request model from Card.
     *
     * @param Card $card
     * @param string $validationToken
     *
     * @return SignedRequestModel
     */
    public function toModel(Card $card, $validationToken = null)
    {
        $cardContentModel = new CardContentModel(
            $card->getIdentity(),
            $card->getIdentityType(),
            $card->getPublicKeyData()
                 ->toBase64(),
            $card->getScope(),
            $card->getData(),
            new DeviceInfoModel(
                $card->getDevice(), $card->getDeviceName()
            )
        );

        $signatureToBase64Encode = function (BufferInterface $signature) {
            return $signature->toBase64();
        };

        $signedRequestMetaModelArgs[] = array_map($signatureToBase64Encode, $card->getSignatures());

        if ($validationToken != null) {
            $signedRequestMetaModelArgs[] = new ValidationModel($validationToken);
        }

        $signedRequestMetaModel = new SignedRequestMetaModel(...$signedRequestMetaModelArgs);

        return new SignedRequestModel(
            $cardContentModel,
            $signedRequestMetaModel,
            $card->getSnapshot()
                 ->toBase64()
        );
    }
}
