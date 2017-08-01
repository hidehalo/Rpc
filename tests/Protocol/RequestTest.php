<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @group testing
     * @dataProvider reqProvider
     * @param Request $req
     */
    public function testWithMethodAndGetMethod(Request $req)
    {
        $exceptedMethod = 'test';
        $method = $req->withMethod('test')->getMethod();
        $this->assertSame($exceptedMethod, $method);
    }

    /**
     * @group testing
     * @dataProvider reqProvider
     * @param Request $req
     */
    public function testWithParamsAndGetParams(Request $req)
    {
        $exceptedParams = [1, 2, 3];
        $params = $req->withParams($exceptedParams)->getParams();
        $this->assertSame($exceptedParams, $params);
    }

    /**
     * @group testing
     * @dataProvider reqProvider
     * @param Request $req
     */
    public function testWithExtrasAndGetExtras(Request $req)
    {
        $exceptedExtras = [1, 2, 3];
        $extras = $req->withParams($exceptedExtras)->getParams();
        $this->assertSame($exceptedExtras, $extras);
    }

    /**
     * @group testing
     * @dataProvider reqProvider
     * @param Request $req
     */
    public function testToString(Request $req)
    {
        $method = 'test_method';
        $params = [1,2,3];
        $extras = [1,2,3];
        $payload = json_encode([
            'jsonrpc' => $req->getVersion(),
            'id' => $req->getId(),
            'method' => $method,
            'params' => $params,
            'extras' => $extras,
        ]);
        $this->assertSame($payload,
            (string) $req->withMethod($method)
            ->withParams($params)
            ->withExtras($extras)
        );
    }

    /**
     * @group testing
     * @dataProvider reqProvider
     * @param Request $req
     */
    public function testToArray(Request $req)
    {
        $method = 'test_method';
        $params = [1,2,3];
        $extras = [1,2,3];
        $ret = [
            'jsonrpc' => $req->getVersion(),
            'id' => $req->getId(),
            'method' => $method,
            'params' => $params,
            'extras' => $extras,
        ];
        $payload = $req->withMethod($method)
            ->withParams($params)
            ->withExtras($extras)
            ->toArray();
        $this->assertSame($payload, $ret);
    }

    /**
     * @return array
     */
    public function reqProvider()
    {
        return [
            [new Request(null, null)]
        ];
    }
}