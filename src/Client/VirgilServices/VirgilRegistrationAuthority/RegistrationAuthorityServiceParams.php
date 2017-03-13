<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority;


use Virgil\Sdk\Client\VirgilServices\AbstractServiceParams;

/**
 * Class provides Registration Authority Service params.
 */
class RegistrationAuthorityServiceParams extends AbstractServiceParams implements RegistrationAuthorityServiceParamsInterface
{
    const CREATE_ENDPOINT = '/v1/card';
    const DELETE_ENDPOINT = '/v1/card';

    /** @var string */
    private $registrationAuthorityServiceHost;

    /** @var string */
    private $createEndpoint;

    /** @var string */
    private $deleteEndpoint;


    /**
     * Class constructor.
     *
     * @param string $registrationAuthorityServiceHost
     * @param string $createEndpoint
     * @param string $deleteEndpoint
     */
    public function __construct(
        $registrationAuthorityServiceHost,
        $createEndpoint = self::CREATE_ENDPOINT,
        $deleteEndpoint = self::DELETE_ENDPOINT
    ) {
        $this->registrationAuthorityServiceHost = $registrationAuthorityServiceHost;
        $this->createEndpoint = $createEndpoint;
        $this->deleteEndpoint = $deleteEndpoint;
    }


    /**
     * @inheritdoc
     */
    public function getCreateUrl()
    {
        return $this->buildUrl($this->registrationAuthorityServiceHost, $this->createEndpoint);
    }


    /**
     * @inheritdoc
     */
    public function getDeleteUrl($id)
    {
        return $this->buildUrl($this->registrationAuthorityServiceHost, $this->deleteEndpoint, $id);
    }
}
