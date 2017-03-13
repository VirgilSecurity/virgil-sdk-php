<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

/**
 * Interface provides related methods such as content encryption for a list of virgil cards.
 */
interface VirgilCardsInterface
{
    /**
     * Returns a list of public keys.
     *
     * @return PublicKeyInterface[]
     */
    public function getPublicKeys();


    /**
     * Encrypts the specified data for a list of virgil cards recipient.
     *
     * @param mixed $content
     *
     * @return BufferInterface
     */
    public function encrypt($content);
}
