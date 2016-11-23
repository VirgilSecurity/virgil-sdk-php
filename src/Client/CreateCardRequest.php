<?php

namespace Virgil\SDK\Client;


use Virgil\SDK\Buffer;
use Virgil\SDK\BufferInterface;
use Virgil\SDK\Client\Card\Mapper\CreateRequestModelMapper;
use Virgil\SDK\Client\Card\Mapper\SignedRequestModelMapper;
use Virgil\SDK\Client\Card\Model\CardContentModel;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\Card\Model\SignedRequestMetaModel;

class CreateCardRequest extends AbstractCardRequest
{
    private $identity;
    private $identityType;
    private $publicKey;
    private $data;
    private $info;
    private $scope;

    /**
     * CreateCardRequest constructor.
     *
     * @param string $identity
     * @param string $identityType
     * @param BufferInterface $publicKey
     * @param string $scope
     * @param array $data
     * @param DeviceInfoModel $info
     */
    public function __construct($identity, $identityType, BufferInterface $publicKey, $scope = CardScope::TYPE_APPLICATION, $data = [], DeviceInfoModel $info = null)
    {
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKey = $publicKey;
        $this->data = $data;
        $this->info = $info;
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return BufferInterface
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }

    /**
     * @return DeviceInfoModel
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @inheritdoc
     * @return CardContentModel
     */
    protected function getCardContent()
    {
        return new CardContentModel(
            $this->identity,
            $this->identityType,
            $this->publicKey->toBase64(),
            $this->scope,
            $this->data,
            $this->info
        );
    }

    /**
     * @return string
     */
    public function export()
    {
        return base64_encode(self::getRequestModelJsonMapper()->toJson($this->getRequestModel()));
    }

    /**
     * @param $exportedRequest
     * @return CreateCardRequest
     */
    public static function import($exportedRequest)
    {
        $modelJson = base64_decode($exportedRequest);
        $model = self::getRequestModelJsonMapper()->toModel($modelJson);

        /** @var CardContentModel $cardContent */
        $cardContent = $model->getCardContent();
        $request = new self(
            $cardContent->getIdentity(),
            $cardContent->getIdentityType(),
            Buffer::fromBase64($cardContent->getPublicKey()),
            $cardContent->getScope(),
            $cardContent->getData(),
            $cardContent->getInfo()
        );

        /** @var SignedRequestMetaModel $meta */
        $meta = $model->getMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $request->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        return $request;
    }

    /**
     * @return CreateRequestModelMapper
     */
    public static function getRequestModelJsonMapper()
    {
        return new CreateRequestModelMapper(new SignedRequestModelMapper());
    }
}