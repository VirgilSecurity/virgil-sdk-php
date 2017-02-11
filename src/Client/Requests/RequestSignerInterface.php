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
     * @param AbstractSignableCardRequest $request
     * @param PrivateKeyInterface         $signerPrivateKey
     *
     * @return RequestSignerInterface
     */
    public function selfSign(AbstractSignableCardRequest $request, PrivateKeyInterface $signerPrivateKey);


    /**
     * Sign the request with authority sign.
     *
     * @param AbstractSignableCardRequest $request
     * @param string                      $appId
     * @param PrivateKeyInterface         $signerPrivateKey
     *
     * @return RequestSignerInterface
     */
    public function authoritySign(AbstractSignableCardRequest $request, $appId, PrivateKeyInterface $signerPrivateKey);
}
