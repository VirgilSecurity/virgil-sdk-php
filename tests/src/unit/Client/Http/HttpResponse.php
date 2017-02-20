<?php
namespace Virgil\Sdk\Tests\Unit\Client\Http;


use Virgil\Sdk\Client\Http\Responses\HttpResponse as HttpClientResponse;
use Virgil\Sdk\Client\Http\Responses\HttpStatusCode;

class HttpResponse
{
    public static function createHttpClientResponse($httpStatusCode, $headers, $body)
    {
        return new HttpClientResponse(
            new HttpStatusCode($httpStatusCode), $headers, $body
        );
    }

}
