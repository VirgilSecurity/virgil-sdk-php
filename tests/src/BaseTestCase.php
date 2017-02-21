<?php
namespace Virgil\Sdk\Tests;


use Exception;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Expects exception type raised in callback.
     * If provided exception has not raised then a test will fail.
     *
     * @param string   $exceptionType
     * @param callable $callback
     *
     * @return Exception
     */
    public function catchException($exceptionType, $callback)
    {
        try {
            call_user_func($callback);
        } catch (Exception $exception) {

            if ($exception instanceof $exceptionType) {
                return $exception;
            }
        }

        self::fail('Failed asserting that exception of type "' . $exceptionType . '" is thrown.');
    }
}
