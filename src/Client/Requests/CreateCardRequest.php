<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Buffer;
use Virgil\Sdk\BufferInterface;

use Virgil\Sdk\Client\Requests\Mapper\CreateRequestModelMapper;

use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedResponseModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

/**
 * Class represents request for card creation.
 */
class CreateCardRequest extends AbstractCardRequest
{
    /** @var string $identity */
    private $identity;

    /** @var string $identityType */
    private $identityType;

    /** @var BufferInterface $publicKey */
    private $publicKey;

    /** @var array $data */
    private $data;

    /** @var DeviceInfoModel $info */
    private $info;

    /** @var string $scope */
    private $scope;


    /**
     * Class constructor.
     *
     * @param string          $identity
     * @param string          $identityType
     * @param BufferInterface $publicKey
     * @param string          $scope
     * @param array           $data
     * @param DeviceInfoModel $info
     */
    public function __construct(
        $identity,
        $identityType,
        BufferInterface $publicKey,
        $scope = CardScopes::TYPE_APPLICATION,
        $data = [],
        DeviceInfoModel $info = null
    ) {
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKey = $publicKey;
        $this->data = $data;
        $this->info = $info;
        $this->scope = $scope;
    }


    /**
     * Imports card request from base64 json string.
     *
     * @param string $exportedRequest base64 encoded request.
     *
     * @return CreateCardRequest
     */
    public static function import($exportedRequest)
    {
        $requestModelJsonMapper = self::getRequestModelJsonMapper();
        $modelJson = base64_decode($exportedRequest);
        $model = $requestModelJsonMapper->toModel($modelJson);

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
     * Returns create request model mapper.
     *
     * @return CreateRequestModelMapper
     */
    public static function getRequestModelJsonMapper()
    {
        return new CreateRequestModelMapper(new SignedRequestModelMapper(), new SignedResponseModelMapper());
    }


    /**
     * Returns card identity.
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * Returns card public key.
     *
     * @return BufferInterface
     * TODO correct naming of method. Somewhere exists not really correct getPublicKeyData method
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * Returns card identity type.
     *
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * Returns card info.
     *
     * @return DeviceInfoModel
     */
    public function getInfo()
    {
        return $this->info;
    }


    /**
     * Returns card additional data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Returns card scope.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * Exports card to base64 json string.
     *
     * @return string
     */
    public function export()
    {
        $requestModelJsonMapper = self::getRequestModelJsonMapper();

        return base64_encode($requestModelJsonMapper->toJson($this->getRequestModel()));
    }


    /**
     * @inheritdoc
     */
    protected function getCardContent()
    {
        return new CardContentModel(
            $this->identity, $this->identityType, $this->publicKey->toBase64(), $this->scope, $this->data, $this->info
        );
    }
}
