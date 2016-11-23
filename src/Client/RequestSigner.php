<?php

namespace Virgil\SDK\Client;


use Virgil\SDK\Buffer;
use Virgil\SDK\Contracts\CryptoInterface;
use Virgil\SDK\Contracts\PrivateKeyInterface;

class RequestSigner implements RequestSignerInterface
{
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

    public function selfSign(AbstractCardRequest $request, PrivateKeyInterface $privateKey)
    {
        $fingerprint = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()));
        $signature = $this->crypto->sign($fingerprint->getData(), $privateKey);
        $request->appendSignature($fingerprint->toHex(), $signature);
    }

    public function authoritySign(AbstractCardRequest $request, $appId, PrivateKeyInterface $privateKey)
    {
        $fingerprint = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()));
        $signature = $this->crypto->sign($fingerprint->getData(), $privateKey);
        $request->appendSignature($appId, $signature);
    }
}