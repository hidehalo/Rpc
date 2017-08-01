<?php

namespace Hidehalo\JsonRpc;

class Connection
{
    /**]
     * @param $endpoint
     */
    public function __construct($endpoint)
    {
        $stream = stream_socket_client($endpoint, $err_no, $err_msg);
        stream_set_blocking($stream, true);
        stream_set_timeout($stream, 10);
        $this->stream = $stream;
    }

    /**
     * @return string
     */
    public function read()
    {
        return fgets($this->stream);
    }

    /**
     * @param string $message
     * @return int
     */
    public function write($message = '')
    {
        return fwrite($this->stream, $message);
    }
}