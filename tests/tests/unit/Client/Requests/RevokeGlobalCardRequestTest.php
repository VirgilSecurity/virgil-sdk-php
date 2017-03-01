<?php
namespace Virgil\Sdk\Tests\Unit\Client\Requests;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Requests\Constants\RevocationReasons;
use Virgil\Sdk\Client\Requests\RevokeGlobalCardRequest;

use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Tests\BaseTestCase;

class RevokeGlobalCardRequestTest extends BaseTestCase
{
    /**
     * @test
     */
    public function export__fromRevokeGlobalCardRequest__importsOriginalCardRequest()
    {
        $revokeGlobalCardRequest = new RevokeGlobalCardRequest(
            'card-id',
            RevocationReasons::TYPE_COMPROMISED,
            new ValidationModel('MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A')
        );

        $revokeGlobalCardRequest->appendSignature('sign-id', new Buffer('sign-value'));


        $exportedRequest = $revokeGlobalCardRequest->export();
        /** @var RevokeGlobalCardRequest $importedRevokeGlobalCardRequest */
        $importedRevokeGlobalCardRequest = $revokeGlobalCardRequest::import($exportedRequest);


        $this->assertEquals($revokeGlobalCardRequest, $importedRevokeGlobalCardRequest);
    }


    /**
     * @test
     */
    public function import__exportedRevokeGlobalCardRequest__keepsExportedContentSnapshot()
    {
        $exportedRequest = 'eyJjb250ZW50X3NuYXBzaG90IjoiZXlKeVpYWnZZMkYwYVc5dVgzSmxZWE52YmlJNkltTnZiWEJ5YjIxcGMyVmtJaXdpWTJGeVpGOXBaQ0k2SW1OaGNtUXRhV1FpZlE9PSIsIm1ldGEiOnsic2lnbnMiOnsic2lnbi1pZCI6ImMybG5iaTEyWVd4MVpRPT0ifSwidmFsaWRhdGlvbiI6eyJ0b2tlbiI6Ik1JR1pNQTBHQ1dDR1NBRmxBd1FDQWdVQUJJR0hNSUdFQWtCMFJWa3FKODlVbHZzYkJEZ0EyblBOVkVoUnB0YkY4WlZGWHJaR2J6U21MVTlPTHcyQSJ9fX0=';
        $expectedContentSnapshot = 'eyJyZXZvY2F0aW9uX3JlYXNvbiI6ImNvbXByb21pc2VkIiwiY2FyZF9pZCI6ImNhcmQtaWQifQ==';

        /** @var RevokeGlobalCardRequest $revokeGlobalCardRequest */
        $revokeGlobalCardRequest = RevokeGlobalCardRequest::import($exportedRequest);


        $contentSnapshot = $revokeGlobalCardRequest->getSnapshot();


        $this->assertEquals($expectedContentSnapshot, $contentSnapshot);
    }

}
