<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\MessageInterface;
use PHPUnit\Framework\TestCase;

class MessageAwareTraitTest extends TestCase
{
    /**
     * @group passed
     * @dataProvider msgProvider
     * @param MessageInterface $message
     */
    public function testWithIdAndGetId(MessageInterface $message)
    {
        new MessageStub();
        $id = uniqid();
        $message->withId($id);
        $this->assertSame($id, $message->getId());
    }

    /**
     * @group passed
     * @dataProvider msgProvider
     * @param MessageInterface $message
     */
    public function testGetVersion(MessageInterface $message)
    {
       $this->assertSame('2.0', $message->getVersion());
    }

    /**
     * @dataProvider msgProvider
     * @return array
     */
    public function msgProvider()
    {
        return [
            [new MessageStub()],
        ];
    }
}