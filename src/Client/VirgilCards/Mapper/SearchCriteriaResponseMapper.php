<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

/**
 * Class transforms search criteria response to model and vise versa.
 */
class SearchCriteriaResponseMapper extends AbstractJsonModelMapper
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
