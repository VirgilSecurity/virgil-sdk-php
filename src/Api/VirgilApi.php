<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Cards\CardsManager;
use Virgil\Sdk\Api\Cards\CardsManagerInterface;

use Virgil\Sdk\Api\Keys\KeysManager;
use Virgil\Sdk\Api\Keys\KeysManagerInterface;

/**
 * Virgil api is a one point to work with Virgil entities that provides high-level API such as cards and keys.
 */
class VirgilApi implements VirgilApiInterface
{
    /** @var KeysManagerInterface */
    public $Keys;

    /** @var CardsManagerInterface */
    public $Cards;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->Keys = new KeysManager($virgilApiContext);
        $this->Cards = new CardsManager($virgilApiContext);
    }


    /**
     * @inheritdoc
     */
    public function create($accessToken = null)
    {
        $virgilApiContext = new VirgilApiContext($accessToken);

        return new self($virgilApiContext);
    }


    /**
     * @inheritdoc
     */
    public function getKeys()
    {
        return $this->Keys;
    }


    /**
     * @inheritdoc
     */
    public function getCards()
    {
        return $this->Cards;
    }
}
