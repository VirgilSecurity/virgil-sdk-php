<?php
namespace Virgil\Sdk\Api\Cards;


use ArrayObject;

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
     * @param CryptoInterface $crypto
     * @param VirgilCard[]    $virgilCards
     *
     */
    public function __construct(CryptoInterface $crypto, array $virgilCards = [])
    {
        $this->virgilCrypto = $crypto;

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
            function (VirgilCardInterface $virgilCard) {
                return $virgilCard->getPublicKey();
            },
            $this->getArrayCopy()
        );
    }
}
