<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\ErrorResponse;
use PHPUnit\Framework\TestCase;

class ErrorResponseTest extends TestCase
{
    /**
     * @group passed
     * @dataProvider errorRespProvider
     * @param ErrorResponse $errorResponse
     * @param $msg
     * @param $code
     * @param $data
     */
    public function testToString(ErrorResponse $errorResponse, $msg, $code, $data)
    {
        $excepted = json_encode([
            'jsonrpc' => '2.0',
            'id' => null,
            'error' => [
                'code' => $code,
                'message' => $msg,
                'data' => $data,
            ],
        ]);
        $this->assertSame($excepted, (string) $errorResponse);
    }

    /**
     * @group passed
     * @dataProvider errorRespProvider
     * @param ErrorResponse $errorResponse
     * @param $msg
     * @param $code
     * @param $data
     */
    public function testToArray(ErrorResponse $errorResponse, $msg, $code, $data)
    {
        $excepted = [
            'jsonrpc' => '2.0',
            'id' => null,
            'error' => [
                'code' => $code,
                'message' => $msg,
                'data' => $data,
            ],
        ];
        $this->assertSame($excepted, $errorResponse->toArray());
    }

    /**
     * @group passed
     * @dataProvider errorRespProvider
     * @param ErrorResponse $errorResponse
     * @param $msg
     * @param $code
     * @param $data
     */
    public function testGetData(ErrorResponse $errorResponse, $msg, $code, $data)
    {
        $this->assertSame($data, $errorResponse->getData());
    }

    /**
     * @return array
     */
    public function errorRespProvider()
    {
        $msg = 'test_error_msg';
        $code = 1000;
        $data = false;
        return [
            [ new ErrorResponse($msg, $code, null, $data), $msg, $code, $data],
        ];
    }
}