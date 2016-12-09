<?php
namespace Virgil\Sdk\Client\Card\Mapper;


use Virgil\Sdk\Client\Card\Model\SearchCriteria;

class SearchCriteriaRequestMapper extends AbstractJsonModelMapper
{
    public function toJson($model)
    {
        /** @var SearchCriteria $model */
        if (!$model instanceof SearchCriteria) {
            throw new \InvalidArgumentException('Invalid model passed. Instance of SearchCriteria accept only.');
        }

        return json_encode(
            array_filter(
                [
                    'identities'    => $model->getIdentities(),
                    'identity_type' => $model->getIdentityType(),
                    'scope'         => $model->getScope(),
                ],
                function ($value) {
                    return count($value) !== 0;
                }
            )
        );
    }
}
