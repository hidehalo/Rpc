<?php
namespace Hidehalo\JsonRpc\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;
//TODO: implements ProtocolInterface
class Json
{
    public static function buildRequest($method = null, $params = [], $extras = [])
    {
        return new Request($method, $params, $extras);
    }

    public static function buildResponse($id = null, $result = null)
    {
        return new Response($id, $result);
    }

    public static function parseRequest($data)
    {
        $payload = self::decode($data);

        return new Request($payload->method, $payload->params, isset($payload->extras) ? $payload->extras : []);
    }

    public static function parseResponse($data)
    {
        $payload = self::decode($data);

        return new Response($payload->id, $payload->result);
    }

    public static function encode($payload)
    {
        return json_encode($payload);
    }

    public static function decode($payload)
    {
        return json_decode($payload);
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