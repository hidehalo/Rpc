<?php
namespace Hidehalo\JsonRpc\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;
//TODO: implements ProtocolInterface
class Json
{
    //TODO: remove it and add encode method
    public function buildResponse($id, $result)
    {
        $rep = new Response($id, $result);

        return (string) $rep;
    }

    //TODO: remove it and add decode method
    public function buildRequest($method, $params, $extras = [])
    {
        $req = new Request($method, $params, $extras);

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

    public function parseBatchRequests($data)
    {
        $payloads = json_decode($data);
        foreach ($payloads as $payload) {
            //TODO: switch(type) {
            //case :error
            //case :notify
            //case :response
            //default:
            //}
        }

        return $data;
    }

    public function parseBatchResponses($data)
    {
        $payloads = json_decode($data);
        foreach ($payloads as $payload) {
            //TODO: switch(type) {
            //case :error
            //case :notify
            //case :response
            //default:
            //}
        }

        return $data;
    }
}