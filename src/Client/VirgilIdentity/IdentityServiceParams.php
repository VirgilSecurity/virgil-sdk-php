<?php
namespace Virgil\Sdk\Client\VirgilIdentity;


use Virgil\Sdk\Client\VirgilServices\AbstractServiceParams;

/**
 * Class provides Identity Service params.
 */
class IdentityServiceParams extends AbstractServiceParams implements IdentityServiceParamsInterface
{
    const VERIFY_ENDPOINT = '/v1/verify';
    const CONFIRM_ENDPOINT = '/v1/confirm';
    const VALIDATE_ENDPOINT = '/v1/validate';

    /** @var string */
    private $identityServiceHost;

    /** @var string */
    private $confirmEndpoint;

    /** @var string */
    private $verifyEndpoint;

    /** @var string */
    private $validateEndpoint;


    /**
     * Class constructor.
     *
     * @param string $identityServiceHost
     * @param string $confirmEndpoint
     * @param string $verifyEndpoint
     * @param string $validateEndpoint
     */
    public function __construct(
        $identityServiceHost,
        $confirmEndpoint = self::CONFIRM_ENDPOINT,
        $verifyEndpoint = self::VERIFY_ENDPOINT,
        $validateEndpoint = self::VALIDATE_ENDPOINT
    ) {
        $this->identityServiceHost = $identityServiceHost;
        $this->confirmEndpoint = $confirmEndpoint;
        $this->verifyEndpoint = $verifyEndpoint;
        $this->validateEndpoint = $validateEndpoint;
    }


    /**
     * @inheritdoc
     */
    public function getVerifyUrl()
    {
        return $this->buildUrl($this->identityServiceHost, $this->verifyEndpoint);
    }


    /**
     * @inheritdoc
     */
    public function getConfirmUrl()
    {
        return $this->buildUrl($this->identityServiceHost, $this->confirmEndpoint);
    }


    /**
     * @inheritdoc
     */
    public function getValidateUrl()
    {
        return $this->buildUrl($this->identityServiceHost, $this->validateEndpoint);
    }
}
