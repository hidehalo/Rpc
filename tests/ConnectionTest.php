<?php
namespace Hidehalo\JsonRpc\Test;

use PHPUnit\Framework\TestCase;
use Hidehalo\JsonRpc\Connection;

class ConnectionTest extends TestCase
{
    /**
     * @group passed
     * @dataProvider connProvider
     * @param Connection $conn
     * @param resource $server
     */
    public function testRead(Connection $conn, $server)
    {
        $socket = stream_socket_accept($server, 1);
        fwrite($socket, 'hello');
        $msg = $conn->read();
        $conn->close();
        $this->assertSame($msg, 'hello');
    }

    /**
     * @group passed
     * @dataProvider connProvider
     * @param Connection $conn
     * @param resource $server
     */
    public function testWrite(Connection $conn, $server)
    {
        $socket = stream_socket_accept($server);
        $conn->write('hello');
        $msg = fread($socket, 1024);
        $conn->close();
        $this->assertSame($msg, 'hello');;
    }

    /**
     * @group passed
     * @dataProvider connProvider
     * @param Connection $conn
     * @param $server
     */
    public function testClose(Connection $conn, $server)
    {
        stream_socket_accept($server);
        $this->assertTrue($conn->close());
    }

    public function connProvider()
    {
        $bitmask = STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;
        $endpoint = 'tcp://127.0.0.1:3'.mt_rand(1111, 9999);
        $context['socket'] = [
            'bindto' => $endpoint,
        ];
        $context = stream_context_create($context);
        $stream = stream_socket_server($endpoint, $errno, $errstr, $bitmask, $context);

        return [
            [new Connection($endpoint), $stream]
        ];
    }
}