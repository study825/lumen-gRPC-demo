<?php

namespace component\grpc;

class SimpleGrpc extends BaseGrpc
{
    /**
     * 简单grpc请求
     *
     * @param $serviceName
     * @param $action
     * @param $request
     * @param array $options
     */
    public function send($serviceName, $action, $request, $options = [])
    {
        $this->setAttr($serviceName, $action, $request, $options);
        $client = $this->getClient();

        $call = $client->$action($this->request);
        list($reply, $status) = $call->wait();

        $checkRes = $this->checkStatus($status);
        if ($checkRes === self::ERR || $checkRes === self::RST) {
            throw new GrpcException(get_object_vars($status));
        }

        $resArr = $this->parseToArray($reply);

        return $resArr;
    }
}