<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedResponseModel;

/**
 * Interface provides methods for interaction with Virgil Cards Service.
 */
interface CardsServiceInterface
{
    /**
     * Create card by given request model.
     *
     * @param SignedRequestModel $model
     *
     * @return SignedResponseModel
     */
    public function create(SignedRequestModel $model);


    /**
     * Delete card by given request model.
     *
     * @param SignedRequestModel $model
     *
     * @return $this
     */
    public function delete(SignedRequestModel $model);


    /**
     * Search cards by given search request model.
     *
     * @param SearchRequestModel $model
     *
     * @return SignedResponseModel[]
     */
    public function search(SearchRequestModel $model);


    /**
     * Retrieve card by given id.
     *
     * @param string $id
     *
     * @return SignedResponseModel
     */
    public function get($id);
}
