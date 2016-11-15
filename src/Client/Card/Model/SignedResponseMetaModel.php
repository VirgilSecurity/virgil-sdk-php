<?php

namespace Virgil\SDK\Client\Card\Model;


class SignedResponseMetaModel
{
    private $signs;
    private $createdAt;
    private $cardVersion;
    private $fingerprint;

    /**
     * SignedResponseMetaModel constructor.
     * @param array $signs
     * @param \DateTime $createdAt
     * @param string $cardVersion
     * @param string $fingerprint
     */
    public function __construct(array $signs, \DateTime $createdAt, $cardVersion, $fingerprint)
    {
        $this->signs = $signs;
        $this->createdAt = $createdAt;
        $this->cardVersion = $cardVersion;
        $this->fingerprint = $fingerprint;
    }

    /**
     * @return string
     */
    public function getCardVersion()
    {
        return $this->cardVersion;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function getSigns()
    {
        return $this->signs;
    }

    /**
     * @return string
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }
}