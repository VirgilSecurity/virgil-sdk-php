<?php
namespace Virgil\Sdk\Tests\Unit\Client\Http;


use Virgil\Sdk\Client\Http\HttpStatusCode;
use Virgil\Sdk\Client\Http\Response;

class HttpResponse
{
    public static function createHttpClientResponse($httpStatusCode, $headers, $body)
    {
        return new Response(
            new HttpStatusCode($httpStatusCode), $headers, $body
        );
    }

}
