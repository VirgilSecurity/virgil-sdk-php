<?php
namespace Virgil\Sdk\Client;


/**
 * Class provides params for Virgil Client.
 * TODO:decide to move constants to config.
 */
class VirgilClientParams
{
    const CARDS_SERVICE_UR = 'https://cards.virgilsecurity.com';
    const CARDS_RO_SERVICE_UR = 'https://cards-ro.virgilsecurity.com';
    const IDENTITY_SERVICE_URL = 'https://identity.virgilsecurity.com';

    /** @var string string $accessToken */
    private $accessToken;

    /** @var string $cardsServiceAddress */
    private $cardsServiceAddress;

    /** @var string $readOnlyCardsServiceAddress */
    private $readOnlyCardsServiceAddress;

    /** @var string $identityServiceAddress */
    private $identityServiceAddress;


    /**
     * VirgilClientParams constructor.
     *
     * @param string $accessToken
     * @param string $cardsServiceAddress
     * @param string $readOnlyCardsServiceAddress
     * @param string $identityServiceAddress
     */
    public function __construct(
        $accessToken,
        $cardsServiceAddress = self::CARDS_SERVICE_UR,
        $readOnlyCardsServiceAddress = self::CARDS_RO_SERVICE_UR,
        $identityServiceAddress = self::IDENTITY_SERVICE_URL
    ) {
        $this->accessToken = $accessToken;
        $this->setCardsServiceAddress($cardsServiceAddress);
        $this->setReadCardsServiceAddress($readOnlyCardsServiceAddress);
        $this->setIdentityServiceAddress($identityServiceAddress);
    }


    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }


    /**
     * @return string
     */
    public function getCardsServiceAddress()
    {
        return $this->cardsServiceAddress;
    }


    /**
     * @param string $cardsServiceAddress
     */
    public function setCardsServiceAddress($cardsServiceAddress)
    {
        if (!$this->checkServiceUrl($cardsServiceAddress)) {
            throw new \InvalidArgumentException(__METHOD__);
        }

        $this->cardsServiceAddress = $cardsServiceAddress;
    }


    /**
     * @return string
     */
    public function getReadOnlyCardsServiceAddress()
    {
        return $this->readOnlyCardsServiceAddress;
    }


    /**
     * @param string $readOnlyCardsServiceAddress
     */
    public function setReadCardsServiceAddress($readOnlyCardsServiceAddress)
    {
        if (!$this->checkServiceUrl($readOnlyCardsServiceAddress)) {
            throw new \InvalidArgumentException(__METHOD__);
        }

        $this->readOnlyCardsServiceAddress = $readOnlyCardsServiceAddress;
    }


    /**
     * @return string
     */
    public function getIdentityServiceAddress()
    {
        return $this->identityServiceAddress;
    }


    /**
     * @param string $identityServiceAddress
     */
    public function setIdentityServiceAddress($identityServiceAddress)
    {
        if (!$this->checkServiceUrl($identityServiceAddress)) {
            throw new \InvalidArgumentException(__METHOD__);
        }

        $this->identityServiceAddress = $identityServiceAddress;
    }


    /**
     * Checks if given url is valid.
     *
     * @param string $serviceUrl
     *
     * @return bool
     */
    private function checkServiceUrl($serviceUrl)
    {
        return (bool)filter_var($serviceUrl, FILTER_VALIDATE_URL);
    }
}
