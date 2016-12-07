<?php
namespace Virgil\Sdk\Cryptography\KeyEntryStorage;


/**
 * Class is the base class for public/private keys references.
 * Objects of this class act like an access key to a material representation of key entry by providing reference.
 */
class KeyReference
{
    /** @var  string */
    private $id;


    /**
     * Class constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
