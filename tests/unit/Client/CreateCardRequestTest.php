<?php

namespace Virgil\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Buffer;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\CardScope;
use Virgil\SDK\Client\CreateCardRequest;

class CreateCardRequestTest extends TestCase
{
    public function testExportThenImport()
    {
        $signs = [
            "af6799a2f26376731abb9abf32b5f2ac0933013f42628498adb6b12702df1a87" => "MIGaMA0GCWCGSAFlAwQCAgUABIGIMIGFAkAUkHTx9vEXcUAq9O5bRsfJ0K5s8Bwm55gEXfzbdtAfr6ihJOXA9MAdXOEocqKtH6DuU7zJAdWgqfTrweih7jAkEAgN7CeUXwZwS0lRslWulaIGvpK65czWphRwyuwN++hI6dlHOdPABmhMSqimwoRsLN8xsivhPqQdLow5rDFic7A==",
            "767b6b12702df1a873f42628498f32b5f31abb9ab12ac09af6799a2f263330ad" => "MIGaMA0GCWCGSAFlAwQCAgUABIGIMIGFAkBg9WJPxgq1ObqxPpXdomNIDxlOvyGdI9wrgZYXu+YAibJd+8Vf0uFce9QrB7yiG2U2zTNVqwsg4f7bd1SKVleAkEAplvCmFJ6v3sYQVBXerr8Yb25UllbTDuCw5alWSfBw2j3ueFiXTiyY885y0detX08YFEWYgbAoKtJgModQTEcQ==",
            "ab799a2f26333c09af6628496b12702df1a80ad767b73f42b9ab12a8f32b5f31" => "MIGaMA0GCWCGSAFlAwQCAgUABf7bd1SKVleAkEAplvCmFJ6v3sYQVBXerr8Yb25UllbTDuCw5alWSfBw2j3ueFiXTiyY88bAoKtJgModQTEc9WJPxgq1Obqx5y0dIGIMIGFAkBgwrgZYXu+YAibJd+8Vf0uFce9QrB7yiG2U2zTNVqwsg4etX08YFEWYgPpXdomNIDxlOvyGdI9Q=="
        ];
        $request = new CreateCardRequest(
            'alice', 'member', new Buffer('public-key'), CardScope::TYPE_APPLICATION, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')
        );

        foreach ($signs as $signKey => $sign) {
            $request->appendSignature($signKey, $sign);
        }

        $exportedRequest = $request->export();

        $this->assertEquals($request, $request::import($exportedRequest));
    }
}