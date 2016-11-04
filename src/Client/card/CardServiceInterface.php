<?php

namespace Virgil\SDK\Client\Card;


use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\Card\Model\SignedResponseModel;

interface CardServiceInterface
{
    /**
     * @param SignedRequestModel $model
     * @return SignedResponseModel
     */
    public function create(SignedRequestModel $model);

    /**
     * @param SignedRequestModel $model
     * @return mixed
     */
    public function delete(SignedRequestModel $model);

    /**
     * @param SearchCriteria $model
     * @return SignedResponseModel[]
     */
    public function search(SearchCriteria $model);

    /**
     * @param string $id
     * @return SignedResponseModel
     */
    public function get($id);
}