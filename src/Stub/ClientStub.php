<?php

class ClientStub implements ClientStubInterface
{
    public function __contruct()
    {
        $this->protocol = new JsonRpc();
        $this->invoker = new Invoker();
    }

    public function import()
    {

    }

    public function procedure($method, $params, $extras = [])
    {
        return new Request($method, $params, $extras);
    }

    public function encode(RequestInterface $req)
    {
        return (string) $req;
    }

    public function send(RequestInterface $req)
    {
        //$data = $this->encode($req)
        //stream->write($data);
    }
}