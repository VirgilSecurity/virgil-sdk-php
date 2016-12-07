<?php
namespace Virgil\Sdk\Client;


class VirgilClientParams
{
    private $accessToken;
    private $cardsServiceAddress;
    private $readOnlyCardsServiceAddress;
    private $identityServiceAddress;


    /**
     * VirgilClientParams constructor.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->setCardsServiceAddress('https://cards.virgilsecurity.com');
        $this->setReadCardsServiceAddress('https://cards-ro.virgilsecurity.com');
        $this->setIdentityServiceAddress('https://identity.virgilsecurity.com');
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
