<?php
namespace Virgil\Sdk\Client\Card;


use Virgil\Sdk\Client\Card;

/**
 * Interface provides methods for card transformation to other models and vise versa.
 */
interface CardMapperInterface
{
    /**
     * Creates Card from model.
     *
     * @param $model
     *
     * @return Card
     */
    public function toCard($model);


    /**
     * Creates model from Card.
     *
     * @param Card $card
     *
     * @return mixed
     */
    public function toModel(Card $card);
}
