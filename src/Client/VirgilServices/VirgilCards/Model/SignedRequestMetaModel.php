<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

/**
 * Class represents json serializable request card meta model.
 */
class SignedRequestMetaModel extends AbstractModel
{
    /** @var array $signs */
    private $signs;


    /**
     * Class constructor.
     *
     * @param array $signs
     */
    public function __construct(array $signs)
    {
        $this->signs = $signs;
    }


    /**
     * Returns request card signs.
     *
     * @return array
     */
    public function getSigns()
    {
        return $this->signs;
    }


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [JsonProperties::SIGNS_ATTRIBUTE_NAME => $this->signs];
    }
}
