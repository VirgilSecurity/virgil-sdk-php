<?php
namespace Virgil\Tests\Unit\Client\Requests;


use PHPUnit\Framework\TestCase;
use Virgil\Sdk\Buffer;
use Virgil\Sdk\Client\Requests\Constants\RevocationReasons;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;

class RevokeCardRequestTest extends TestCase
{
    /**
     * @test
     */
    public function export__fromCardRequest__importsOriginalCardRequest()
    {
        $signs = [
            "af6799a2f26376731abb9abf32b5f2ac0933013f42628498adb6b12702df1a87" => "MIGaMA0GCWCGSAFlAwQCAgUABIGIMIGFAkAUkHTx9vEXcUAq9O5bRsfJ0K5s8Bwm55gEXfzbdtAfr6ihJOXA9MAdXOEocqKtH6DuU7zJAdWgqfTrweih7jAkEAgN7CeUXwZwS0lRslWulaIGvpK65czWphRwyuwN++hI6dlHOdPABmhMSqimwoRsLN8xsivhPqQdLow5rDFic7A==",
            "767b6b12702df1a873f42628498f32b5f31abb9ab12ac09af6799a2f263330ad" => "MIGaMA0GCWCGSAFlAwQCAgUABIGIMIGFAkBg9WJPxgq1ObqxPpXdomNIDxlOvyGdI9wrgZYXu+YAibJd+8Vf0uFce9QrB7yiG2U2zTNVqwsg4f7bd1SKVleAkEAplvCmFJ6v3sYQVBXerr8Yb25UllbTDuCw5alWSfBw2j3ueFiXTiyY885y0detX08YFEWYgbAoKtJgModQTEcQ==",
        ];

        $request = new RevokeCardRequest(
            'card-id', RevocationReasons::TYPE_COMPROMISED
        );

        foreach ($signs as $signKey => $sign) {
            $request->appendSignature($signKey, Buffer::fromBase64($sign));
        }


        $exportedRequest = $request->export();
        $importedRequest = $request::import($exportedRequest);


        $this->assertEquals($request, $importedRequest);
    }
}
