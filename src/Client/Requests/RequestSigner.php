<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;

/**
 * Class provides methods for signing card requests. There a two ways how card can be signed:
 * just sign by card owner signature and by any authority signatures like card service signature.
 */
class RequestSigner implements RequestSignerInterface
{
    /** @var CryptoInterface $crypto */
    private $crypto;


    /**
     * RequestSigner constructor.
     *
     * @param CryptoInterface $crypto
     */
    public function __construct(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;
    }


    /**
     * @inheritdoc
     */
    public function selfSign(AbstractSignableCardRequest $request, PrivateKeyInterface $signerPrivateKey)
    {
        $fingerprint = $this->crypto->calculateFingerprint(base64_decode($request->getSnapshot()));
        $signature = $this->crypto->sign($fingerprint->getData(), $signerPrivateKey);
        $request->appendSignature($fingerprint->toHex(), $signature);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function authoritySign(AbstractSignableCardRequest $request, $appId, PrivateKeyInterface $signerPrivateKey)
    {
        $fingerprint = $this->crypto->calculateFingerprint(base64_decode($request->getSnapshot()));
        $signature = $this->crypto->sign($fingerprint->getData(), $signerPrivateKey);
        $request->appendSignature($appId, $signature);

        return $this;
    }
}
