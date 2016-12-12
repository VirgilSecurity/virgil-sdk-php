<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;

/**
 * Class provides methods for signing card requests. There a two ways how card can be signed:
 * just sing by card owner signature and by any authority signatures like card service signature.
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
    public function selfSign(AbstractCardRequest $request, PrivateKeyInterface $signerPrivateKey)
    {
        $fingerprint = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()));
        $signature = $this->crypto->sign($fingerprint->getData(), $signerPrivateKey);
        $request->appendSignature($fingerprint->toHex(), $signature);
    }


    /**
     * @inheritdoc
     */
    public function authoritySign(AbstractCardRequest $request, $appId, PrivateKeyInterface $signerPrivateKey)
    {
        $fingerprint = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()));
        $signature = $this->crypto->sign($fingerprint->getData(), $signerPrivateKey);
        $request->appendSignature($appId, $signature);
    }
}
