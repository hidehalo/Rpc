<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\Json;
use Hidehalo\JsonRpc\Protocol\Reply\Response;
use Hidehalo\JsonRpc\Protocol\Reply\ErrorResponse;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @group passed
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
     * @group passed
     * @dataProvider protoProvider
     * @param Json $json
     */
    public function testBuildReqAndParseReq(Json $json)
    {
        $payload = $json->buildRequest('test', [1,2,3]);
        $req = $json->parseRequest($payload);
        $this->assertSame('2.0', $req->getVersion());
        $this->assertSame('test', $req->getMethod());
        $this->assertSame([1,2,3], $req->getParams());
        $this->assertSame([], $req->getExtras());
    }

    /**
     * @group testing
     * @dataProvider protoProvider
     * @param Json $json
     */
    public function testBuildBatchReqAndParse(Json $json)
    {
        $payload = $json->buildBatchRequests(function ($req) {
            return $req->test(1)->test(2)->test(3);
        });
        $this->assertNotNull($payload);
        $batchReq = $json->parseBatchRequests($payload);
        $this->assertSame($payload, (string)$batchReq);
    }

    /**
     * @group testing
     * @dataProvider protoProvider
     * @param Json $json
     */
    public function testBuildBatchResAndParse(Json $json)
    {
        $replies = [];
        for ($i=0; $i<10; $i++) {
            if ($i == 5) {
                $replies[] = new ErrorResponse();
                continue;
            }
            $replies[] = new Response(uniqid(), mt_rand(1,10000));
        }
        $batchRes = $json->buildBatchResponses($replies);
        $payload = (string)$batchRes;
        $handled = $json->parseBatchResponses($payload);
        $this->assertEquals($batchRes, $handled);
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