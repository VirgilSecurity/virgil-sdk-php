<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents json serializable request card meta model.
 */
class SignedRequestMetaModel extends AbstractModel
{
    /** @var array $signs */
    private $signs;

    /** @var ValidationModel */
    private $validation;


    /**
     * Class constructor.
     *
     * @param array           $signs
     * @param ValidationModel $validation
     */
    public function __construct(array $signs, ValidationModel $validation = null)
    {
        $this->signs = $signs;
        $this->validation = $validation;
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
     * @return ValidationModel
     */
    public function getValidation()
    {
        return $this->validation;
    }


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::SIGNS_ATTRIBUTE_NAME      => $this->signs,
            JsonProperties::VALIDATION_ATTRIBUTE_NAME => $this->validation,
        ];
    }
}
