<?php
namespace Hidehalo\JsonRpc\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;

class Json
{
    public function __construct()
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

        return new Request($payload->method, $payload->params, isset($payload->extras) ? $payload->extras : []);
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