<?php
namespace Virgil\Sdk\Client;


use InvalidArgumentException;

/**
 * Class provides params for virgil services.
 */
class VirgilClientParams implements VirgilClientParamsInterface
{
    const CARDS_SERVICE_URL = 'https://cards.virgilsecurity.com';
    const CARDS_RO_SERVICE_URL = 'https://cards-ro.virgilsecurity.com';
    const IDENTITY_SERVICE_URL = 'https://identity.virgilsecurity.com';
    const REGISTRATION_AUTHORITY_SERVICE_URL = 'https://ra.virgilsecurity.com';

    /** @var string $accessToken */
    private $accessToken;

    /** @var string $cardsServiceAddress */
    private $cardsServiceAddress;

    /** @var string $readOnlyCardsServiceAddress */
    private $readOnlyCardsServiceAddress;

    /** @var string $identityServiceAddress */
    private $identityServiceAddress;

    /** @var string $registrationAuthorityServiceAddress */
    private $registrationAuthorityServiceAddress;


    /**
     * Class constructor.
     *
     * @param string $accessToken
     * @param string $cardsServiceAddress
     * @param string $readOnlyCardsServiceAddress
     * @param string $identityServiceAddress
     * @param string $registrationAuthorityServiceAddress
     */
    public function __construct(
        $accessToken = null,
        $cardsServiceAddress = self::CARDS_SERVICE_URL,
        $readOnlyCardsServiceAddress = self::CARDS_RO_SERVICE_URL,
        $identityServiceAddress = self::IDENTITY_SERVICE_URL,
        $registrationAuthorityServiceAddress = self::REGISTRATION_AUTHORITY_SERVICE_URL
    ) {
        $this->accessToken = $accessToken;

        $this->setCardsServiceAddress($cardsServiceAddress);
        $this->setReadCardsServiceAddress($readOnlyCardsServiceAddress);
        $this->setIdentityServiceAddress($identityServiceAddress);
        $this->setRegistrationAuthorityService($registrationAuthorityServiceAddress);
    }


    /**
     * @inheritdoc
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }


    /**
     * @inheritdoc
     */
    public function getCardsServiceAddress()
    {
        return $this->cardsServiceAddress;
    }


    /**
     * @inheritdoc
     */
    public function setCardsServiceAddress($cardsServiceAddress)
    {
        if (!$this->isServiceUrlValid($cardsServiceAddress)) {
            throw new InvalidArgumentException(__METHOD__);
        }

        $this->cardsServiceAddress = $cardsServiceAddress;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getReadOnlyCardsServiceAddress()
    {
        return $this->readOnlyCardsServiceAddress;
    }


    /**
     * @inheritdoc
     */
    public function setReadCardsServiceAddress($readOnlyCardsServiceAddress)
    {
        if (!$this->isServiceUrlValid($readOnlyCardsServiceAddress)) {
            throw new InvalidArgumentException(__METHOD__);
        }

        $this->readOnlyCardsServiceAddress = $readOnlyCardsServiceAddress;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getIdentityServiceAddress()
    {
        return $this->identityServiceAddress;
    }


    /**
     * @inheritdoc
     */
    public function setIdentityServiceAddress($identityServiceAddress)
    {
        if (!$this->isServiceUrlValid($identityServiceAddress)) {
            throw new InvalidArgumentException(__METHOD__);
        }

        $this->identityServiceAddress = $identityServiceAddress;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getRegistrationAuthorityServiceAddress()
    {
        return $this->registrationAuthorityServiceAddress;
    }


    /**
     * @inheritdoc
     */
    public function setRegistrationAuthorityService($registrationAuthorityServiceAddress)
    {
        if (!$this->isServiceUrlValid($registrationAuthorityServiceAddress)) {
            throw new InvalidArgumentException(__METHOD__);
        }

        $this->registrationAuthorityServiceAddress = $registrationAuthorityServiceAddress;

        return $this;
    }


    /**
     * Checks if given url is valid.
     *
     * @param string $serviceUrl
     *
     * @return bool
     */
    private function isServiceUrlValid($serviceUrl)
    {
        return (bool)filter_var($serviceUrl, FILTER_VALIDATE_URL);
    }
}
