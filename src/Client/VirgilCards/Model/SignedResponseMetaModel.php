<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use DateTime;


/**
 * Class represents card meta model response.
 */
class SignedResponseMetaModel
{
    /** @var array $signs */
    private $signs;

    /** @var DateTime $createdAt */
    private $createdAt;

    /** @var string $cardVersion */
    private $cardVersion;


    /**
     * Class constructor.
     *
     * @param array    $signs
     * @param DateTime $createdAt
     * @param string   $cardVersion
     */
    public function __construct(array $signs, DateTime $createdAt, $cardVersion)
    {
        $this->signs = $signs;
        $this->createdAt = $createdAt;
        $this->cardVersion = $cardVersion;
    }


    /**
     * Returns card version.
     *
     * @return string
     */
    public function getCardVersion()
    {
        return $this->cardVersion;
    }


    /**
     * Returns create at date.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * Returns signatures.
     *
     * @return array
     */
    public function getSigns()
    {
        return $this->signs;
    }
}
