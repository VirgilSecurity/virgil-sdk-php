<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedResponseModel;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

/**
 * Class transforms many signed responses to models and vise versa.
 */
class SignedResponseModelsMapper extends AbstractJsonModelMapper
{
    /** @var SignedResponseModelMapper $signedResponseModelMapper */
    private $signedResponseModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedResponseModelMapper $signedResponseModelMapper
     */
    public function __construct(SignedResponseModelMapper $signedResponseModelMapper)
    {
        $this->signedResponseModelMapper = $signedResponseModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedResponseModel[]
     */
    public function toModel($json)
    {
        $models = [];
        $data = json_decode($json, true);
        foreach ($data as $item) {
            $models[] = $this->signedResponseModelMapper->toModel(json_encode($item));
        }

        return $models;
    }
}
