<?php
namespace Virgil\Sdk\Client\Card;


/**
 * Interface provides urls for access to Cards Service.
 */
interface CardsServiceParamsInterface
{
    /**
     * @return string
     */
    public function getSearchUrl();


    /**
     * @return string
     */
    public function getCreateUrl();


    /**
     * @param string $id
     *
     * @return string
     */
    public function getDeleteUrl($id);


    /**
     * @param string $id
     *
     * @return string
     */
    public function getGetUrl($id);
}
