<?php
namespace Virgil\Sdk\Client\VirgilServices;


use Virgil\Sdk\Client\Http\Responses\HttpResponseInterface;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;

/**
 * Class is a base class for any Virgil Services.
 */
abstract class AbstractVirgilServices
{
    /**
     * Returns proper error response model mapper.
     *
     * @return AbstractErrorResponseModelMapper
     */
    abstract protected function getErrorResponseModelMapper();


    /**
     * Makes request to http client and gets response object.
     *
     * @param callable $request
     *
     * @throws UnsuccessfulResponseException
     *
     * @return HttpResponseInterface
     */
    protected function makeRequest($request)
    {
        /** @var HttpResponseInterface $response */
        $response = call_user_func($request);

        $responseHttpStatusCode = $response->getHttpStatusCode();

        if (!$responseHttpStatusCode->isSuccess()) {
            $errorResponseModelMapper = $this->getErrorResponseModelMapper();

            $errorResponseModel = $errorResponseModelMapper->toModel($response->getBody());

            $httpStatusCode = $responseHttpStatusCode->getCode();

            $serviceErrorMessage = $errorResponseModel->getMessage();

            $serviceErrorCode = $errorResponseModel->getCode();

            throw new UnsuccessfulResponseException($serviceErrorMessage, $httpStatusCode, $serviceErrorCode);
        }

        return $response;
    }
}
