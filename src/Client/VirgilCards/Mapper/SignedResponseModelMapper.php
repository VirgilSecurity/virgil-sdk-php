<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use DateTime;

use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

/**
 * Class transforms signed response json to model.
 */
class SignedResponseModelMapper extends AbstractJsonModelMapper
{
    const ID_ATTRIBUTE_NAME = 'id';
    const CARD_VERSION_ATTRIBUTE_NAME = 'card_version';
    const CREATED_AT_ATTRIBUTE_NAME = 'created_at';

    /** @var CardContentModelMapper */
    private $cardContentModelMapper;


    /**
     * Class constructor.
     *
     * @param CardContentModelMapper $cardContentModelMapper
     */
    public function __construct(CardContentModelMapper $cardContentModelMapper)
    {
        $this->cardContentModelMapper = $cardContentModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentJson = base64_decode($data[self::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]);
        $cardMetaData = $data[self::META_ATTRIBUTE_NAME];

        $cardContentModel = $this->cardContentModelMapper->toModel($cardContentJson);

        $cardMetaModel = new SignedResponseMetaModel(
            $cardMetaData[self::SIGNS_ATTRIBUTE_NAME],
            new DateTime($cardMetaData[self::CREATED_AT_ATTRIBUTE_NAME]),
            $cardMetaData[self::CARD_VERSION_ATTRIBUTE_NAME]
        );

        return new SignedResponseModel(
            $data[self::ID_ATTRIBUTE_NAME],
            $data[self::CONTENT_SNAPSHOT_ATTRIBUTE_NAME],
            $cardContentModel,
            $cardMetaModel
        );
    }
}
