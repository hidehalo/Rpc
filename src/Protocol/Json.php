<?php
namespace Hidehalo\JsonRpc\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;
//TODO: implements ProtocolInterface
class Json
{
    const SUCCESS = 0;
    const ERROR = 1;
    const NOTIFICATION = 2;
    const REQUEST = 3;
    const UNKNOWN= 4;

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
        $payloads = self::decode($data);
        foreach ($payloads as $payload) {
            $code = self::getPayloadTypeCode($payload);
            switch ($code) {
                case self::SUCCESS:
                    break;
                case self::ERROR:
                    break;
            }
        }

        return $data;
    }

    public static function parseBatchResponses($data)
    {
        $ret = [];
        $payloads = self::decode($data);
        foreach ($payloads as $payload) {
            $code = self::getPayloadTypeCode($payload);
            switch ($code) {
                case self::NOTIFACATION:
                    return self::createNotify($payload);
                case self::REQUEST:
                    return self::createRequest($payload);
                default:
                    break;
            }
        }

        return $data;
    }

    private static function getPayloadTypeCode(array $payload)
    {
        if (isset($payload['error'])) {

            return self::ERROR;
        } elseif (isset($payload['result'])) {

            return self::SUCCESS;
        } elseif (isset($payload['id']) && $payload['id'] === null) {

            return self::NOTIFICATION;
        } elseif (isset($payload['method'])) {
            
            return self::REQUEST;
        }

        return self::UNKNOWN;
    }

    private static function createRequest(array $attributes = [])
    {
        return Request::create($attributes);
    }

    private static function createResponse(array $attributes = [])
    {
        return Response::create($attributes);
    }

    private static function createErrResponse(Exception $e)
    {
        return ErrorResponse::create($e);
    }

    private static function createNotify(array $attributes = [])
    {
        return Request::create($attributes)->withId(null);
    }
}