<?php

namespace Virgil\Tests\Unit\Client\Card;


use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Virgil\SDK\Client\Card\CardServiceParams;
use Virgil\SDK\Client\Card\CardsServiceException;
use Virgil\SDK\Client\Card\CardsService;
use Virgil\SDK\Client\Card\Mapper\ModelMappersCollection;
use Virgil\SDK\Client\Card\Mapper\SearchCriteriaRequestMapper;
use Virgil\SDK\Client\Card\Mapper\SearchCriteriaResponseMapper;
use Virgil\SDK\Client\Card\Mapper\SignedRequestModelMapper;
use Virgil\SDK\Client\Card\Mapper\SignedResponseModelMapper;
use Virgil\SDK\Client\Card\Model\CardContentModel;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\Card\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\Card\Model\SignedRequestMetaModel;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\Card\Model\SignedResponseMetaModel;
use Virgil\SDK\Client\Card\Model\SignedResponseModel;
use Virgil\SDK\Client\CardScope;
use Virgil\SDK\Client\HashMapJsonMapper;
use Virgil\SDK\Client\Http\CurlClient;
use Virgil\SDK\Client\Http\CurlRequest;
use Virgil\SDK\Client\Http\CurlRequestFactory;
use Virgil\SDK\Client\Http\Response;
use Virgil\SDK\Client\Http\Status;

class CardsServiceTest extends TestCase
{
    /** @var  PHPUnit_Framework_MockObject_MockObject $httpClientMock */
    private $httpClientMock;
    /** @var  CardsService $cardService */
    private $cardService;

    public function setUp()
    {
        parent::setUp();
        $this->httpClientMock = $this->getMockBuilder(CurlClient::class)
            ->setConstructorArgs([new CurlRequestFactory()])
            ->setMethods(['doRequest'])
            ->getMock();

        $mappers = new ModelMappersCollection(
            new SignedResponseModelMapper(),
            new SignedRequestModelMapper(),
            new SearchCriteriaResponseMapper(new SignedResponseModelMapper()),
            new SearchCriteriaRequestMapper(),
            new HashMapJsonMapper()
        );

        $params = new CardServiceParams(
            [
                'mutable_host' => 'http://mutable.host/',
                'immutable_host' => 'http://immutable.host',
                'get_endpoint' => '/card/',
                'search_endpoint' => '/card/actions/search',
                'delete_endpoint' => '/card',
                'create_endpoint' => '/card/'
            ]
        );

        $this->cardService = new CardsService($params, $this->httpClientMock, $mappers);
    }

    public function testCardServiceAuthError()
    {
        $this->httpClientMock
            ->expects($this->any())
            ->method('doRequest')
            ->with($this->callback(function (CurlRequest $actualRequest) {
                return !in_array('Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }', $actualRequest->getOptions()[CURLOPT_HTTPHEADER]);
            }))
            ->willReturnCallback(function () {
                return new Response(new Status('401'), [], '{"code":"20300"}');
            });


        try {
            $this->cardService->get('card-id');
        } catch (CardsServiceException $exception) {
            $this->assertEquals('20300', $exception->getCode());
            $this->assertEquals('The Virgil access token was not specified or is invalid', $exception->getMessage());
        }
    }

    public function testCardGetById()
    {
        $this->httpClientMock->setHeaders(['Authorization' => 'VIRGIL { YOUR_APPLICATION_TOKEN }']);
        $this->httpClientMock
            ->expects($this->any())
            ->method('doRequest')
            ->with($this->callback(function (CurlRequest $actualRequest) {
                $options = $actualRequest->getOptions();

                return $options[CURLOPT_URL] == 'http://immutable.host/card/model-id-1' &&
                in_array('Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }', $options[CURLOPT_HTTPHEADER]) &&
                $options[CURLOPT_CUSTOMREQUEST] == 'GET' && $options[CURLOPT_HTTPGET] == true;
            }))
            ->willReturnCallback(function () {
                return new Response(
                    new Status('200'), [], '{"id":"model-id-1","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"fingerprint":"bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59","created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}'
                );
            });

        $response = $this->cardService->get('model-id-1');
        $expectedResponse = new SignedResponseModel(
            'model-id-1',
            new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
            new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
        );

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCardCreate()
    {
        $this->httpClientMock->setHeaders(['Authorization' => 'VIRGIL { YOUR_APPLICATION_TOKEN }']);
        $this->httpClientMock
            ->expects($this->any())
            ->method('doRequest')
            ->with($this->callback(function (CurlRequest $actualRequest) {
                $options = $actualRequest->getOptions();
                $requestJson = '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlIiwiaWRlbnRpdHlfdHlwZSI6Im1lbWJlciIsInB1YmxpY19rZXkiOiJwdWJsaWMta2V5Iiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}';

                return $options[CURLOPT_URL] == 'http://mutable.host/card/' &&
                in_array('Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }', $options[CURLOPT_HTTPHEADER]) &&
                $options[CURLOPT_CUSTOMREQUEST] == 'POST' && $options[CURLOPT_POST] == true &&
                $options[CURLOPT_POSTFIELDS] == $requestJson;
            }))
            ->willReturnCallback(function () {
                return new Response(
                    new Status('200'), [], '{"id":"model-id-2","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"fingerprint":"bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59","created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}'
                );
            });

        $expectedResponse = new SignedResponseModel(
            'model-id-2',
            new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL),
            new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
        );

        $request = $model = new SignedRequestModel(
            new CardContentModel('alice', 'member', 'public-key', CardScope::TYPE_APPLICATION),
            new SignedRequestMetaModel(['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'])
        );

        $response = $this->cardService->create($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCardSearch()
    {
        $this->httpClientMock->setHeaders(['Authorization' => 'VIRGIL { YOUR_APPLICATION_TOKEN }']);
        $this->httpClientMock
            ->expects($this->any())
            ->method('doRequest')
            ->with($this->callback(function (CurlRequest $actualRequest) {
                $options = $actualRequest->getOptions();
                $requestJson = '{"identities":["user@virgilsecurity.com","another.user@virgilsecurity.com"],"identity_type":"email","scope":"global"}';

                return $options[CURLOPT_URL] == 'http://immutable.host/card/actions/search/' &&
                in_array('Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }', $options[CURLOPT_HTTPHEADER]) &&
                $options[CURLOPT_CUSTOMREQUEST] == 'POST' && $options[CURLOPT_POST] == true &&
                $options[CURLOPT_POSTFIELDS] == $requestJson;
            }))
            ->willReturnCallback(function () {
                return new Response(
                    new Status('200'), [],
                    '[{"id":"model-id-1","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"fingerprint":"bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59","created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}},{"id":"model-id-2","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"fingerprint":"bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59","created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}]'
                );
            });

        $expectedResponseId2 = new SignedResponseModel(
            'model-id-2',
            new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL),
            new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
        );

        $expectedResponseId1 = new SignedResponseModel(
            'model-id-1',
            new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
            new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
        );

        $expectedResponse[] = $expectedResponseId1;
        $expectedResponse[] = $expectedResponseId2;

        $request = $model = new SearchCriteria(
            ['user@virgilsecurity.com', 'another.user@virgilsecurity.com'], 'email', CardScope::TYPE_GLOBAL
        );

        $response = $this->cardService->search($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCardDelete()
    {
        $this->httpClientMock->setHeaders(['Authorization' => 'VIRGIL { YOUR_APPLICATION_TOKEN }']);
        $this->httpClientMock
            ->expects($this->any())
            ->method('doRequest')
            ->with($this->callback(function (CurlRequest $actualRequest) {
                $options = $actualRequest->getOptions();
                $requestJson = '{"content_snapshot":"eyJpZCI6ImFsaWNlLWZpbmdlcnByaW50LWlkLTEiLCJyZXZvY2F0aW9uX3JlYXNvbiI6ImNvbXByb21pc2VkIn0=","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}';

                return $options[CURLOPT_URL] == 'http://mutable.host/card/alice-fingerprint-id-1' &&
                in_array('Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }', $options[CURLOPT_HTTPHEADER]) &&
                $options[CURLOPT_CUSTOMREQUEST] == 'DELETE' && $options[CURLOPT_POST] == true &&
                $options[CURLOPT_POSTFIELDS] == $requestJson;
            }))
            ->willReturnCallback(function () {
                return new Response(
                    new Status('200'), [], '{}'
                );
            });

        $request = new SignedRequestModel(
            new RevokeCardContentModel('alice-fingerprint-id-1', 'compromised'),
            new SignedRequestMetaModel(['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'])
        );

        $response = $this->cardService->delete($request);

        $this->assertEquals([], $response);
    }
}