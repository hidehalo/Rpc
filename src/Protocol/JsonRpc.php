<?php
namespace JsonRpc\Protocol;

use JsonRpc\Protocol\Reply\Response;

class JsonRpc
{
    public function __consturct()
    {
        
    }

    public function buildResponse($id, $result)
    {
        $rep = new Response($id, $result);

        return (string) $rep;
    }

    public function buildRequest($method, $params)
    {
        $req = new Request($method, $params);

        return (string) $req;
    }

    public function parseRequest($data)
    {
        $payload = json_decode($data);

        return new Request($payload->method, $payload->params, $payload->extras);
    }

    public function parseResponse($data)
    {
        $payload = json_decode($data);

        return new Response($payload->id, $payload->result);
    }

    public function pipe()
    {
        return new BatchRequest();
    }
}