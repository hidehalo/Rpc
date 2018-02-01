<?php
namespace Hidehalo\JsonRpc\Test\Protocol;

use PHPUnit\Framework\TestCase;
use Hidehalo\JsonRpc\Protocol\Reply\Response;
use Hidehalo\JsonRpc\Protocol\Reply\BatchResponse;

class BatchResponseTest extends TestCase
{
    /**
     * @dataProvider batchRespProvider
     */
    public function testToString(BatchResponse $batch)
    {
        $payload = $batch->__toString();
        $this->assertGreaterThan(0, strlen($payload));
    }

    /**
     * @dataProvider batchRespProvider
     */
    public function testToArray(BatchResponse $batch)
    {
        $asArray = $batch->toArray();
        $this->assertNotEmpty($asArray);
        
        foreach ($asArray as $arr) {
            $this->assertArrayHasKey('jsonrpc', $arr);
            $this->assertArrayHasKey('id', $arr);
            $this->assertArrayHasKey('result', $arr);
        }
    }

    /**
     * @dataProvider batchRespProvider
     */
    public function testIsEmpty(BatchResponse $batch)
    {
        $ret1 = $batch->isEmpty();
        $this->assertFalse($ret1);

        $batch2 = new BatchResponse([]);
        $ret2 = $batch2->isEmpty();
        $this->assertTrue($ret2);
    }

    public function batchRespProvider()
    {
        for ($i=0; $i<5; $i++) {
            $id = uniqid($i * mt_rand(1, 5));
            $result = 'ok';
            $res[] = new Response($id, $result);
        }
        $batch = new BatchResponse($res);

        return [
            [ $batch ]
        ];
    }
}