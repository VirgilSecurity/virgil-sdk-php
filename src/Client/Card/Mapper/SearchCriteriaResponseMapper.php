<?php
namespace Virgil\Sdk\Client\Card\Mapper;


use Virgil\Sdk\Client\Card\Model\SignedResponseModel;

class SearchCriteriaResponseMapper extends AbstractJsonModelMapper
{
    private $mapper;


    /**
     * SearchCriteriaResponseMapper constructor.
     *
     * @param SignedResponseModelMapper $mapper
     */
    public function __construct(SignedResponseModelMapper $mapper)
    {
        $this->mapper = $mapper;
    }


    /**
     * @inheritdoc
     * @return SignedResponseModel[]
     */
    public function toModel($json)
    {
        $models = [];
        $data = json_decode($json, true);
        foreach ($data as $item) {
            $models[] = $this->mapper->toModel(json_encode($item));
        }

        return $models;
    }


    public function toJson($model)
    {
        $this->mapper->toJson($model);
    }
}
