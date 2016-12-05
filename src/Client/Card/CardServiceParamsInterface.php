<?php
namespace Virgil\Sdk\Client\Card;


interface CardServiceParamsInterface
{
    /**
     * @return string
     */
    public function getSearchEndpoint();

    /**
     * @return string
     */
    public function getCreateEndpoint();

    /**
     * @param mixed $id
     * @return string
     */
    public function getDeleteEndpoint($id = null);

    /**
     * @param mixed $id
     * @return string
     */
    public function getGetEndpoint($id = null);
}
