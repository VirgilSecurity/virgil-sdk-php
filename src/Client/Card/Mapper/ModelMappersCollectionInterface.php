<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\JsonModelMapperInterface;

interface ModelMappersCollectionInterface
{
    /**
     * @return JsonModelMapperInterface
     */
    public function getSignedRequestModelMapper();

    /**
     * @return JsonModelMapperInterface
     */
    public function getSignedResponseModelMapper();

    /**
     * @return JsonModelMapperInterface
     */
    public function getSearchCriteriaResponseMapper();

    /**
     * @return JsonModelMapperInterface
     */
    public function getSearchCriteriaRequestMapper();

    /**
     * @return JsonModelMapperInterface
     */
    public function getErrorResponseModelMapper();
}