<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\Json;
use PHPUnit\Framework\TestCase;

class JsonRpcTest extends TestCase
{
    /**
     * @group testing
     * @dataProvider protoProvider
     * @param Json $json
     */
    public function testBuildRepAndParseRep(Json $json)
    {
        $payload = $json->buildResponse(uniqid(),'hello');
        $rep = $json->parseResponse($payload);
        $this->assertSame('2.0', $rep->getVersion());
        $this->assertSame('hello', $rep->getResult());
    }

    /**
     * @group testing
     * @dataProvider protoProvider
     * @param Json $json
     */
    public function testBuildReq(Json $json)
    {
        $payload = $json->buildRequest('test', [1,2,3]);
        $req = $json->parseRequest($payload);
        $this->assertSame('2.0', $req->getVersion());
        $this->assertSame('test', $req->getMethod());
        $this->assertSame([1,2,3], $req->getParams());
        $this->assertSame([], $req->getExtras());
    }

    /**
     *
     */
    public function protoProvider()
    {
        return [
            [new Json()],
        ];
    }
}