<?php

namespace Hidehalo\JsonRpc;

class Connection
{
    /**
     * @var resource
     */
    private $stream;

    private $defaultBufferSize = 2<<15 -1;

    private $closed;

    /**
     * @codeCoverageIgnore
     * @param $endpoint
     */
    public function __construct($endpoint)
    {
        $stream = stream_socket_client($endpoint, $err_no, $err_msg);
        stream_set_read_buffer($stream, $this->defaultBufferSize);
        stream_set_blocking($stream, false);
        $this->stream = $stream;
        $this->closed = false;
    }

    /**
     * @return string
     */
    public function read()
    {
        $stringBuilder = '';
        if (!$this->closed) {
            while (($buffer = @fread($this->stream, $this->defaultBufferSize)) != false) {
                $stringBuilder .= $buffer;
            }
        }

        return $stringBuilder;
    }

    /**
     * @param string $message
     * @return int
     */
    public function write($message = '')
    {
        $wroteSize = @fwrite($this->stream, $message);
        //@codeCoverageIgnoreStart
        if ($wroteSize == false) {
            $this->closed = true;

            return false;
        }
        //@codeCoverageIgnoreEnd

        return $wroteSize;
    }

    /**
     * @return bool
     */
    public function close()
    {
        //@codeCoverageIgnoreStart
        if ($this->closed) {
            return false;
        }
        //@codeCoverageIgnoreEnd
        $this->closed = true;

        return fclose($this->stream);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        $this->close();
    }
}