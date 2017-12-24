<?php
namespace Hidehalo\JsonRpc\Protocol;

use Hidehalo\JsonRpc\Protocol\Reply\Response;
use Hidehalo\JsonRpc\Protocol\Reply\ErrorResponse;
use Hidehalo\JsonRpc\Protocol\Reply\BatchResponse;
use Closure;
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
        if (!$payload) {
            return false;
        }

        return new Response($payload->id, $payload->result);
    }

    public static function encode($payload)
    {
        return json_encode($payload);
    }

    public static function decode($payload, $assoc = false)
    {
        return json_decode($payload, $assoc);
    }

    public static function buildBatchRequests(Closure $procedure)
    {
        $batchReq = new BatchRequest();
        $batchReq = $procedure($batchReq);
        
        return $batchReq->execute();
    }

    public static function buildBatchResponses(array $replies)
    {
        $batchRes = new BatchResponse($replies);

        return $batchRes;
    }

    public static function parseBatchRequests($data)
    {
        $ret = [];
        $payloads = self::decode($data, true);
        foreach ($payloads as $payload) {
            $code = self::getPayloadTypeCode($payload);
            switch ($code) {
                case self::REQUEST:
                    $ret[] = self::createRequest($payload);
                    break;
                case self::NOTIFICATION:
                    $ret[] = self::createNotify();
                    break;
            }
        }

        return new BatchRequest($ret);
    }

    public static function parseBatchResponses($data)
    {
        $ret = [];
        $payloads = self::decode($data, true);
        foreach ($payloads as $payload) {
            $code = self::getPayloadTypeCode($payload);
            switch ($code) {
                case self::SUCCESS:
                    $ret[] = self::createResponse($payload);
                    break;
                case self::ERROR:
                    $ret[] = self::createErrResponse($payload);
                    break;
                default:
                    break;
            }
        }

        return new BatchResponse($ret);
    }

    private static function getPayloadTypeCode(array $payload)
    {
        if (isset($payload['error'])) {

            return self::ERROR;
        } elseif (isset($payload['result'])) {

            return self::SUCCESS;
        } elseif ($payload['id'] === null) {

            return self::NOTIFICATION;
        }
            
        return self::REQUEST;
    }

    private static function createRequest(array $attributes = [])
    {
        return Request::create($attributes);
    }

    private static function createResponse(array $attributes = [])
    {
        return Response::create($attributes);
    }

    private static function createErrResponse(array $attributes)
    {
        return ErrorResponse::create($attributes);
    }

    private static function createNotify()
    {
        return Request::notify();
    }
}