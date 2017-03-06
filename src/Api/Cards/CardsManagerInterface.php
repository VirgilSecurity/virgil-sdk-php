<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Api\Cards\Identity\IdentityValidationToken;

use Virgil\Sdk\Api\Keys\VirgilKey;

use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

/**
 * Interface provides methods to work with virigl card.
 */
interface CardsManagerInterface
{
    /**
     * Imports a virgil card from string representation.
     *
     * @param string $exportedVirgilCard
     *
     * @return VirgilCardInterface
     */
    public function import($exportedVirgilCard);


    /**
     * Publishes a virgil card into global Virgil Services scope.
     *
     * @param VirgilCard              $virgilCard
     * @param IdentityValidationToken $identityValidationToken
     *
     * @return $this
     */
    public function publishGlobal(VirgilCard $virgilCard, IdentityValidationToken $identityValidationToken);


    /**
     * Revokes a virgil card from application Virgil Services scope.
     *
     * @param VirgilCard $virgilCard
     *
     * @return $this
     */
    public function revoke(VirgilCard $virgilCard);


    /**
     * Revokes a global virgil card from Virgil Security services.
     *
     * @param VirgilCard              $virgilCard
     * @param VirgilKey               $virgilKey
     * @param IdentityValidationToken $identityValidationToken
     *
     * @return $this
     */
    public function revokeGlobal(
        VirgilCard $virgilCard,
        VirgilKey $virgilKey,
        IdentityValidationToken $identityValidationToken
    );


    /**
     * Publishes a virgil card into application Virgil Services scope.
     *
     * @param VirgilCard $virgilCard
     *
     * @return $this
     */
    public function publish(VirgilCard $virgilCard);


    /**
     * Sets custom card serializer.
     *
     * @param CardSerializerInterface $cardSerializer
     *
     * @return $this
     */
    public function setCardSerializer(CardSerializerInterface $cardSerializer);


    /**
     * Sets card mapper for response transformation to card.
     *
     * @param CardMapperInterface $cardMapper
     *
     * @return $this
     */
    public function setCardMapper(CardMapperInterface $cardMapper);
}
