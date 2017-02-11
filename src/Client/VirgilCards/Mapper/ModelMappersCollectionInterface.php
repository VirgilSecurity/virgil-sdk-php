<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\JsonModelMapperInterface;

/**
 * Interface provides methods for getting mappers.
 */
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
    public function getSignedResponseModelsMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getSearchRequestModelMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getErrorResponseModelMapper();
}
