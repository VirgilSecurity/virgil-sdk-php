<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Contracts\PrivateKeyInterface;

/**
 * Interface responsible for signing card requests.
 */
interface RequestSignerInterface
{
    /**
     * Signs the request with owner's Private key.
     *
     * @param AbstractCardRequest $request
     * @param PrivateKeyInterface $signerPrivateKey
     *
     */
    public function selfSign(AbstractCardRequest $request, PrivateKeyInterface $signerPrivateKey);


    /**
     * Sign the request with authority sign.
     *
     * @param AbstractCardRequest $request
     * @param string              $appId
     * @param PrivateKeyInterface $signerPrivateKey
     *
     */
    public function authoritySign(AbstractCardRequest $request, $appId, PrivateKeyInterface $signerPrivateKey);
}
