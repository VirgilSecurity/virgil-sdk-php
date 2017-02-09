<?php
namespace Virgil\Sdk\Client\VirgilServices;


use Virgil\Sdk\Client\Http\ResponseInterface;

use Virgil\Sdk\Client\VirgilCards\Model\ErrorResponseModel;

/**
 * Class is a base class for any Virgil Services.
 */
abstract class AbstractService
{
    const DEFAULT_ERROR_MESSAGES = [
        400 => 'Request error',
        401 => 'Authentication error',
        403 => 'Forbidden',
        404 => 'Entity not found',
        405 => 'Method not allowed',
        500 => 'Server error',
    ];


    /**
     * Makes request to http client and gets response object.
     *
     * @param callable $request
     *
     * @throws UnsuccessfulResponseException
     *
     * @return ResponseInterface
     */
    protected function makeRequest($request)
    {
        /** @var ResponseInterface $response */
        $response = call_user_func($request);
        $responseHttpStatusCode = $response->getHttpStatusCode();

        if (!$responseHttpStatusCode->isSuccess()) {
            $errorResponseModelMapper = $this->mappers->getErrorResponseModelMapper();

            /** @var ErrorResponseModel $errorResponse */
            $errorResponse = $errorResponseModelMapper->toModel($response->getBody());

            $httpStatusCode = $responseHttpStatusCode->getCode();

            $serviceErrorMessage = $errorResponse->getMessageOrDefault(
                self::DEFAULT_ERROR_MESSAGES[(int)$httpStatusCode]
            );

            $serviceErrorCode = $errorResponse->getCode();

            throw new UnsuccessfulResponseException($serviceErrorMessage, $httpStatusCode, $serviceErrorCode);
        }

        return $response;
    }
}
