<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Cards\CardsManagerInterface;

use Virgil\Sdk\Api\Keys\KeysManagerInterface;

/**
 * The a virgil api interface defines a high-level API that provides easy access to Virgil Security services and allows
 * to perform cryptographic operations by using two domain entities.
 */
interface VirgilApiInterface
{
    /**
     * Creates a virgil api from access token only.
     *
     * @param string $accessToken
     *
     * @return $this
     */
    public function create($accessToken = null);


    /**
     * @return KeysManagerInterface
     */
    public function getKeys();


    /**
     * @return CardsManagerInterface
     */
    public function getCards();
}
