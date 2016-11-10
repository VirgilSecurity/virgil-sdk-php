<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\JsonModelMapper;

interface ModelMappersCollectionInterface
{
    /**
     * @return JsonModelMapper
     */
    public function getSignedRequestModelMapper();

    /**
     * @return JsonModelMapper
     */
    public function getSignedResponseModelMapper();

    /**
     * @return JsonModelMapper
     */
    public function getSearchCriteriaResponseMapper();

    /**
     * @return JsonModelMapper
     */
    public function getSearchCriteriaRequestMapper();

    /**
     * @return JsonModelMapper
     */
    public function getHashMapJsonMapper();
}