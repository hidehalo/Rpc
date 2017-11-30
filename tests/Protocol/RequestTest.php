<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @group passed
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
     * @group passed
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
     * @group passed
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
     * @group passed
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
     * @group passed
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
    * @group testing
    * @dataProvider reqAttrProvider
    */
    public function testCreate(array $attributes)
    {
        $req = Request::create($attributes);
        $this->assertInstanceOf(Request::class, $req);
        $id = $req->getId();
        $this->assertSame($attributes['id'], $id);
        $method = $req->getMethod();
        $this->assertEquals($attributes['method'], $method);
        $params = $req->getParams();
        $this->assertEquals($attributes['params'], $params);
        $extras = $req->getExtras();
        $this->assertEquals($attributes['extras'], $extras);
    }

    public function reqAttrProvider()
    {
        return [
            [
                [
                    'id' => uniqid(),
                    'method' => 'test',
                    'params' => [
                        'a', 'b', 'c',
                    ],
                    'extras' => [
                        'd', 'e', 'f',
                    ],
                ]
            ]
        ];
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