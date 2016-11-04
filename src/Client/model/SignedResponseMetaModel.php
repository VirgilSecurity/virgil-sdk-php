<?php

namespace Virgil\SDK\Client\Model;


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
     * @param $fingerprint
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

    public function getFingerprint()
    {
        return $this->fingerprint;
    }
}