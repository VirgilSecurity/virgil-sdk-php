<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Requests\PublishGlobalCardRequest;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;
use Virgil\Sdk\Client\Requests\RevokeGlobalCardRequest;
use Virgil\Sdk\Client\Requests\SearchCardRequest;

use Virgil\Sdk\Client\Validator\CardValidatorInterface;

use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

/**
 * Interface represents one point access to Virgil Services such as card creation, user identity verification etc.
 */
interface VirgilClientInterface
{
    /**
     * Performs the Virgil Cards service searching by search request.
     *
     * @param SearchCardRequest $searchCardRequest
     *
     * @return Card[]
     */
    public function searchCards(SearchCardRequest $searchCardRequest);


    /**
     * Performs the Virgil Cards service card creation by request.
     *
     * @param CreateCardRequest $createCardRequest
     *
     * @return Card
     */
    public function createCard(CreateCardRequest $createCardRequest);


    /**
     * Performs the Virgil RA service global card creation by request.
     *
     * @param PublishGlobalCardRequest $publishGlobalCardRequest
     *
     * @return Card
     */
    public function publishGlobalCard(PublishGlobalCardRequest $publishGlobalCardRequest);


    /**
     * Performs the Virgil Cards service card revoking by request.
     *
     * @param RevokeCardRequest $revokeCardRequest
     *
     * @return $this
     */
    public function revokeCard(RevokeCardRequest $revokeCardRequest);


    /**
     * Performs the Virgil RA global card revoking by request.
     *
     * @param RevokeGlobalCardRequest $revokeGlobalCardRequest
     *
     * @return $this
     */
    public function revokeGlobalCard(RevokeGlobalCardRequest $revokeGlobalCardRequest);


    /**
     * Performs the Virgil Cards service card searching by ID.
     *
     * @param $id
     *
     * @return Card
     */
    public function getCard($id);


    /**
     * Sends the request for identity verification, that's will be processed depending of specified type.
     *
     * @param string $identity
     * @param string $identityType
     * @param array  $extraFields
     *
     * @return string Returns action id.
     */
    public function verifyIdentity($identity, $identityType, array $extraFields);


    /**
     * Confirms the identity using confirmation code, that has been generated to confirm an identity.
     *
     * @param string $actionId
     * @param string $confirmationCode
     * @param int    $timeToLive
     * @param int    $countToLive
     *
     * @return string Returns validation token.
     */
    public function confirmIdentity($actionId, $confirmationCode, $timeToLive, $countToLive);


    /**
     * Checks if validation token is valid
     *
     * @param string $identityType
     * @param string $identity
     * @param string $validationToken
     *
     * @throws UnsuccessfulResponseException
     *
     * @return bool
     */
    public function isIdentityValid($identityType, $identity, $validationToken);


    /**
     * Sets the card validator.
     *
     * @param CardValidatorInterface $validator
     *
     * @return $this
     */
    public function setCardValidator(CardValidatorInterface $validator);
}
