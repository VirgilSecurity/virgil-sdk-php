<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use InvalidArgumentException;
use Virgil\Sdk\Client\AbstractJsonModelMapper;
use Virgil\Sdk\Client\VirgilCards\SearchCriteria;

/**
 * Class transforms search criteria request to json.
 */
class SearchCriteriaRequestMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        /** @var SearchCriteria $model */
        if (!$model instanceof SearchCriteria) {
            throw new InvalidArgumentException('Invalid model passed. Instance of SearchCriteria accept only.');
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
