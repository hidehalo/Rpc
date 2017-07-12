<?php

class JsonRpc
{
    public function __consturct()
    {
        $this->parser = new Parser();
    }

    public function buildResponse()
    {

    }

    public function buildRequest()
    {

    }

    public function buildNotice()
    {

    }

    public function pipe()
    {
        return new BatchRequest;
    }
}