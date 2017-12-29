<?php
namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Connection;
use PHPUnit\Framework\TestCase;
use Hidehalo\JsonRpc\Protocol\BatchRequest;

class BatchRequestTest extends TestCase
{
    /**
     * @group passed
     * @dataProvider batchReqProvider
     * @param BatchRequest $batchReq
     */
    public function testMagicCallAndExec(BatchRequest $batchReq)
    {
        $msg = $batchReq->test1(1,2,3)->test2(4,5,6)->test3(7,8,9)->execute();
        $this->assertNotNull($msg);
    }

    /**
     * @group passed
     * @dataProvider batchReqProvider
     * @param BatchRequest $batchReq
     */
    public function testGetIds(BatchRequest $batchReq)
    {
        $batchReq->test1(1,2,3)->test2(4,5,6)->test3(7,8,9);
        $ids = $batchReq->getIds();
        foreach ($ids as $id) {
            $this->assertNotNull($id);
        }
    }

    public function testDefaultService()
    {
        $batch = new BatchRequest([], 'TEST_SERV');
        $payload = $batch->hello('world')->execute();
        $ret = json_decode($payload, true);
        $this->assertSame('TEST_SERV', $ret[0]['extras']['service']);
    }

    /**
     * @group passed
     * @dataProvider batchReqProvider
     * @param BatchRequest $batchReq
     */
    public function testIsEmpty(BatchRequest $batchReq)
    {
        $ret1 = $batchReq->isEmpty();
        $this->assertTrue($ret1);

        $ret2 = $batchReq->hello('world')->isEmpty();
        $this->assertFalse($ret2);
    }

    /**
     * @return array
     */
    public function batchReqProvider()
    {
        return [
            [ $this->createBatchReq()]
        ];
    }

    /**
     * @param $endpoint
     * @return BatchRequest
     */
    private function createBatchReq()
    {
        return new BatchRequest();
    }
}