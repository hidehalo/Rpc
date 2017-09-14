<?php
namespace Hidehalo\JsonRpc\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;
//TODO: implements ProtocolInterface
class Json
{
    public static function parseRequest($data)
    {
        $payload = json_decode($data);

        return new Request($payload->method, $payload->params, isset($payload->extras) ? $payload->extras : []);
    }

    public static function parseResponse($data)
    {
        $payload = json_decode($data);

        return new Response($payload->id, $payload->result);
    }

    public static function parseBatchRequests($data)
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

    public static function parseBatchResponses($data)
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