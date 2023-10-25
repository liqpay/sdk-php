<?php


use PHPUnit\Framework\TestCase;

class LiqPayTest extends TestCase
{
    private $publicKey = 'test_public_key';
    private $privateKey = 'test_private_key';
    
    public function testConstructorWithValidArguments()
    {
        $publicKey = 'public_key';
        $privateKey = 'private_key';
        $apiUrl = 'https://www.liqpay.ua';
        
        $instance = new LiqPay($publicKey, $privateKey, $apiUrl);
        
        $this->assertInstanceOf(LiqPay::class, $instance);
    }
    
    public function testConstructorWithMissingPublicKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('public_key is empty');
        
        new LiqPay('', 'private_key');
    }
    
    public function testConstructorWithMissingPrivateKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('private_key is empty');
        
        new LiqPay('public_key', '');
    }
    
    
    public function testApiThrowsExceptionWhenVersionIsMissing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('version is null');
        
        $liqPay = new LiqPay($this->publicKey, $this->privateKey);
        $liqPay->api('/testpath');
    }
    
    public function testApiHandlesCurlRequesterResponse()
    {
        $mockCurlRequester = $this->createMock(CurlRequester::class);
        $mockCurlRequester->expects($this->once())
            ->method('make_curl_request')
            ->willReturn('{"success":true}');
        
        $liqPay = $this->getMockBuilder(LiqPay::class)
            ->setConstructorArgs([$this->publicKey, $this->privateKey])
            ->onlyMethods(['encode_params', 'str_to_sign'])
            ->getMock();
        
        $liqPay->expects($this->once())
            ->method('encode_params')
            ->willReturn('encoded_params');
        $liqPay->expects($this->once())
            ->method('str_to_sign')
            ->willReturn('signature_string');
        
        $liqPay->curlRequester = $mockCurlRequester;
        
        $response = $liqPay->api('/testpath', ['version' => '1.0', 'action' => 'pay']);
        $this->assertEquals((object) ['success' => true], $response);
    }
    
    public function testCnbSignature()
    {
        $liqPay = $this->getMockBuilder(LiqPay::class)
            ->setConstructorArgs([$this->publicKey, $this->privateKey])
            ->onlyMethods(['encode_params', 'str_to_sign'])
            ->getMock();
        
        $params = [
            'version' => '1.0',
            'amount' => '10',
            'currency' => 'USD',
            'action' => 'pay',
            'order_id' => '123456',
            'description' => 'Test Order'
        ];
        
        $liqPay->expects($this->once())
            ->method('encode_params')
            ->willReturn('encoded_params');
        $liqPay->expects($this->once())
            ->method('str_to_sign')
            ->willReturn('signature_string');
        
        $result = $liqPay->cnb_signature($params);
        
        $this->assertEquals('signature_string', $result);
    }
    
    public function testGetResponseCode()
    {
        $publicKey = 'your_public_key';
        $privateKey = 'your_private_key';
        $apiUrl = 'https://api.example.com';
        $liqPay = new LiqPay($publicKey, $privateKey, $apiUrl);
        
        // Set a value to _server_response_code for testing
        $reflection = new ReflectionClass($liqPay);
        $property = $reflection->getProperty('_server_response_code');
        $property->setAccessible(true);
        $property->setValue($liqPay, 200); // Example response code
        
        $responseCode = $liqPay->get_response_code();
        
        $this->assertEquals(200, $responseCode);
    }
    
}

