<?php
namespace Virgil\Sdk\Client\VirgilCards;


use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

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
     */
    public function delete(SignedRequestModel $model);


    /**
     * Search cards by given search criteria.
     *
     * @param SearchCriteria $model
     *
     * @return SignedResponseModel[]
     */
    public function search(SearchCriteria $model);


    /**
     * Retrieve card by given id.
     *
     * @param string $id
     *
     * @return SignedResponseModel
     */
    public function get($id);
}
