<?php
namespace Virgil\Sdk\Client;


/**
 * Interface provides services specific params.
 */
interface VirgilClientParamsInterface
{
    /**
     * Sets Identity Service url.
     *
     * @param string $identityServiceAddress
     *
     * @return VirgilClientParamsInterface
     */
    public function setIdentityServiceAddress($identityServiceAddress);


    /**
     * Returns Identity Service url.
     *
     * @return string
     */
    public function getIdentityServiceAddress();


    /**
     * Sets read only Cards Service url.
     *
     * @param string $readOnlyCardsServiceAddress
     *
     * @return VirgilClientParamsInterface
     */
    public function setReadCardsServiceAddress($readOnlyCardsServiceAddress);


    /**
     * Returns read only Cards Service url.
     *
     * @return string
     */
    public function getReadOnlyCardsServiceAddress();


    /**
     * Sets read\write Cards Service url.
     *
     * @param string $cardsServiceAddress
     *
     * @return VirgilClientParamsInterface
     */
    public function setCardsServiceAddress($cardsServiceAddress);


    /**
     * Returns read\write Cards Service url.
     *
     * @return string
     */
    public function getCardsServiceAddress();


    /**
     * Returns Cards Service application access token.
     *
     * @return string
     */
    public function getAccessToken();


    /**
     * Returns Registration Authority Service url.
     *
     * @return string
     */
    public function getRegistrationAuthorityServiceAddress();


    /**
     * Sets up Registration Authority Service url.
     *
     * @param string $registrationAuthorityServiceAddress
     *
     * @return VirgilClientParamsInterface
     */
    public function setRegistrationAuthorityService($registrationAuthorityServiceAddress);

}
