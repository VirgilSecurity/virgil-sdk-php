<?php
namespace Virgil\Sdk\Client\VirgilServices;


/**
 * Base class for Virgil services params.
 */
abstract class AbstractServiceParams
{
    /**
     * Build url from given host, uri and $id if specified.
     *
     * @param string      $host
     * @param string      $uri
     * @param string|null $id
     *
     * @return string
     */
    protected function buildUrl($host, $uri, $id = null)
    {
        $params = null;
        if ($id !== null) {
            $params = '/' . $id;
        }

        return rtrim($host, '/') . '/' . trim($uri, '/') . $params;
    }
}
