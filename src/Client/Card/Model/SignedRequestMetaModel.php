<?php
namespace Virgil\Sdk\Client\Card\Model;


class SignedRequestMetaModel extends AbstractModel
{
    private $signs;


    /**
     * SignedRequestMetaModel constructor.
     *
     * @param array $signs
     */
    public function __construct(array $signs)
    {
        $this->signs = $signs;
    }


    /**
     * @return array
     */
    public function getSigns()
    {
        return $this->signs;
    }


    function jsonSerialize()
    {
        return ['signs' => $this->signs];
    }
}
