<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @group testing
     * @dataProvider repProvider
     * @param Response $rep
     * @param $ret
     */
    public function testGetResult(Response $rep, $ret)
    {
        $this->assertSame($ret, $rep->getResult());
    }

    /**
     * @group testing
     * @dataProvider repProvider
     * @param Response $rep
     * @param $ret
     */
    public function testToString(Response $rep, $ret)
    {
        $payload = json_encode([
            'jsonrpc' => $rep->getVersion(),
            'id' => $rep->getId(),
            'result' => $ret,
        ]);

        $this->assertSame($payload, (string) $rep);
    }

    /**
     * @group testing
     * @dataProvider repProvider
     * @param Response $rep
     * @param $ret
     */
    public function testToArray(Response $rep, $ret)
    {
        $this->assertNotEmpty($rep);
        $repAsArray = $rep->toArray();
        $this->assertSame($repAsArray['result'], $ret);
    }

    public function repProvider()
    {
        $id = uniqid();
        $ret = 'result';

        return [
            [new Response($id, $ret), $ret]
        ];
    }
}