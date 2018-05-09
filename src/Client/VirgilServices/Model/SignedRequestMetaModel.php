<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents json serializable request card meta model.
 */
class SignedRequestMetaModel implements JsonSerializable
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
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [
            JsonProperties::SIGNS_ATTRIBUTE_NAME => $this->signs,
        ];

        if ($this->validation != null && count($this->validation->jsonSerialize())) {
            $data[JsonProperties::VALIDATION_ATTRIBUTE_NAME] = $this->validation;
        }

        return $data;
    }
}
