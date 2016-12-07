<?php
namespace Virgil\Sdk\Client\Card\Mapper;


use Virgil\Sdk\Client\JsonModelMapperInterface;

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
