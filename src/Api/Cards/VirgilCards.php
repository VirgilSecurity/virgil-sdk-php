<?php
namespace Virgil\Sdk\Api\Cards;


use ArrayObject;

use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Contracts\CryptoInterface;

/**
 * Class keeps a list of virgil cards and provides related methods such as content encryption.
 */
class VirgilCards extends ArrayObject implements VirgilCardsInterface
{
    /** @var CryptoInterface */
    private $virgilCrypto;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     * @param VirgilCard[]              $virgilCards
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext, array $virgilCards = [])
    {
        $this->virgilCrypto = $virgilApiContext->getCrypto();

        parent::__construct($virgilCards, ArrayObject::ARRAY_AS_PROPS | ArrayObject::STD_PROP_LIST);
    }


    /**
     * @inheritdoc
     */
    public function encrypt($content)
    {
        return $this->virgilCrypto->encrypt($content, $this->getPublicKeys());
    }


    /**
     * @inheritdoc
     */
    public function getPublicKeys()
    {
        return array_map(
            function (VirgilCard $virgilCard) {
                return $virgilCard->getPublicKey();
            },
            $this->getArrayCopy()
        );
    }
}
