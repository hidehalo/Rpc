<?php
namespace Hidehalo\JsonRpc;

use Exception;

class Connection
{
    const TIMEOUT = 10;
    /**
     * @var resource
     */
    private $stream = null;

    private $defaultBufferSize = 2<<15 -1;

    private $closed = true;

    /**
     * @codeCoverageIgnore
     * @param $endpointOrResource
     */
    public function __construct($endpointOrResource)
    {
        if (is_resource($endpointOrResource)) {
            $stream = $endpointOrResource;
        } else {
            try {
                $stream = @stream_socket_client($endpointOrResource, $err_no, $err_msg);
            } catch (Exception $e) {
                //TODO: handle error
            }
        }
        if ($stream != false) {
            stream_set_read_buffer($stream, $this->defaultBufferSize);
            stream_set_blocking($stream, true);
            stream_set_timeout($stream, self::TIMEOUT);
            $this->stream = $stream;
            $this->closed = false;
        }
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
        //TODO: depart it to connector
        //@codeCoverageIgnoreStart
        if ($this->closed || !is_resource($this->stream)) {
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