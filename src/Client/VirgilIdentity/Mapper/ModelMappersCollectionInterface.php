<?php
namespace Virgil\Sdk\Client\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\JsonModelMapperInterface;

/**
 * Interface provides methods for getting mappers.
 */
interface ModelMappersCollectionInterface
{
    /**
     * @return JsonModelMapperInterface
     */
    public function getVerifyResponseModelMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getVerifyRequestModelMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getConfirmResponseModelMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getConfirmRequestModelMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getValidateRequestModelMapper();


    /**
     * @return JsonModelMapperInterface
     */
    public function getErrorResponseModelMapper();
}
