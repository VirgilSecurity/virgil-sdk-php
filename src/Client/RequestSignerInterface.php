<?php

namespace Virgil\SDK\Client;


use Virgil\SDK\Contracts\PrivateKeyInterface;

interface RequestSignerInterface
{
    /**
     * Signs the request with owner's Private key.
     * @param AbstractCardRequest $request
     * @param PrivateKeyInterface $privateKey
     * @return void
     */
    public function selfSign(AbstractCardRequest $request, PrivateKeyInterface $privateKey);

    /**
     * Sign the request with authority sign.
     * @param AbstractCardRequest $request
     * @param string $appId
     * @param PrivateKeyInterface $privateKey
     * @return void
     */
    public function authoritySign(AbstractCardRequest $request, $appId, PrivateKeyInterface $privateKey);
}