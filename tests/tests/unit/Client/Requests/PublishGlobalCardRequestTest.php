<?php
namespace Virgil\Sdk\Tests\Unit\Client\Requests;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Requests\Constants\IdentityTypes;
use Virgil\Sdk\Client\Requests\PublishGlobalCardRequest;

use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Tests\BaseTestCase;

class PublishGlobalCardRequestTest extends BaseTestCase
{
    /**
     * @test
     */
    public function export__fromPublishGlobalCardRequest__importsOriginalCardRequest()
    {
        $signs = [
            "af6799a2f26376731abb9abf32b5f2ac0933013f42628498adb6b12702df1a87" => Buffer::fromBase64(
                "MIGaMA0GCWCGSAFlAwQCAgUABIGIMIGFAkAUkHTx9vEXcUAq9O5bRsfJ0K5s8Bwm55gEXfzbdtAfr6ihJOXA9MAdXOEocqKtH6DuU7zJAdWgqfTrweih7jAkEAgN7CeUXwZwS0lRslWulaIGvpK65czWphRwyuwN++hI6dlHOdPABmhMSqimwoRsLN8xsivhPqQdLow5rDFic7A=="
            ),
        ];

        $publishGlobalCardRequest = new PublishGlobalCardRequest(
            'alice@gmail.com',
            IdentityTypes::TYPE_EMAIL,
            new Buffer('public-key'),
            new ValidationModel('MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A'),
            [],
            new DeviceInfoModel('iPhone6s')
        );

        foreach ($signs as $signKey => $sign) {
            $publishGlobalCardRequest->appendSignature($signKey, $sign);
        }


        $exportedRequest = $publishGlobalCardRequest->export();
        /** @var PublishGlobalCardRequest $importedPublishGlobalCardRequest */
        $importedPublishGlobalCardRequest = $publishGlobalCardRequest::import($exportedRequest);


        $this->assertEquals($publishGlobalCardRequest, $importedPublishGlobalCardRequest);
    }


    /**
     * @test
     */
    public function import__exportedPublishGlobalCardRequest__keepsExportedContentSnapshot()
    {
        $exportedRequest = 'eyJjb250ZW50X3NuYXBzaG90IjoiZXlKelkyOXdaU0k2SW1kc2IySmhiQ0lzSW1sa1pXNTBhWFI1SWpvaVlXeHBZMlZBWjIxaGFXd3VZMjl0SWl3aWFXUmxiblJwZEhsZmRIbHdaU0k2SW1WdFlXbHNJaXdpY0hWaWJHbGpYMnRsZVNJNkltTklWbWxpUjJ4cVRGZDBiR1ZSUFQwaUxDSnBibVp2SWpwN0ltUmxkbWxqWlNJNkltbFFhRzl1WlRaekluMTkiLCJtZXRhIjp7InNpZ25zIjp7ImFmNjc5OWEyZjI2Mzc2NzMxYWJiOWFiZjMyYjVmMmFjMDkzMzAxM2Y0MjYyODQ5OGFkYjZiMTI3MDJkZjFhODciOiJNSUdhTUEwR0NXQ0dTQUZsQXdRQ0FnVUFCSUdJTUlHRkFrQVVrSFR4OXZFWGNVQXE5TzViUnNmSjBLNXM4QndtNTVnRVhmemJkdEFmcjZpaEpPWEE5TUFkWE9Fb2NxS3RINkR1VTd6SkFkV2dxZlRyd2VpaDdqQWtFQWdON0NlVVh3WndTMGxSc2xXdWxhSUd2cEs2NWN6V3BoUnd5dXdOKytoSTZkbEhPZFBBQm1oTVNxaW13b1JzTE44eHNpdmhQcVFkTG93NXJERmljN0E9In0sInZhbGlkYXRpb24iOnsidG9rZW4iOiJNSUdaTUEwR0NXQ0dTQUZsQXdRQ0FnVUFCSUdITUlHRUFrQjBSVmtxSjg5VWx2c2JCRGdBMm5QTlZFaFJwdGJGOFpWRlhyWkdielNtTFU5T0x3MkEifX19';
        $expectedContentSnapshot = 'eyJzY29wZSI6Imdsb2JhbCIsImlkZW50aXR5IjoiYWxpY2VAZ21haWwuY29tIiwiaWRlbnRpdHlfdHlwZSI6ImVtYWlsIiwicHVibGljX2tleSI6ImNIVmliR2xqTFd0bGVRPT0iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIn19';

        /** @var PublishGlobalCardRequest $publishGlobalCardRequest */
        $publishGlobalCardRequest = PublishGlobalCardRequest::import($exportedRequest);


        $contentSnapshot = $publishGlobalCardRequest->getSnapshot();


        $this->assertEquals($expectedContentSnapshot, $contentSnapshot);
    }

}
