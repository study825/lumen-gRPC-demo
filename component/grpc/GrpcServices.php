<?php

namespace component\grpc;

use Google\Protobuf\Internal\Message;

class GrpcServices extends BaseGrpc
{
    /**
     * 简单grpc请求
     *
     * @param $serviceName
     * @param $action
     * @param $request
     * @param array $options
     */
    public function unaryCall($serviceName, $action, $request, $options = [])
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

    /**
     * 双向流grpc请求
     *
     * @param $serviceName
     * @param $action
     * @param $request
     * @param array $options
     * @return array
     * @throws GrpcException
     */
    public function bidirectionalGrpc($serviceName, $action, $request, $options = [])
    {

        $this->setAttr($serviceName, $action, $request, $options);
        $call = $this->getCall();
        try {
            $call->write($this->request);
        } catch (\Exception $e) {
           $this->removeCall();
           $call = $this->getCall();
           $call->write($this->request);
        }

        /**
         * @var $res Message 连接异常时重连一次并且重新发起请求
         */
        $res = $this->read($call);

        if ($res === self::RST) {
            usleep(10000);
            $call = $this->getCall();
            $call->write($this->request);
            $res = $this->read($call);
        }

        if ($res === self::ERR || $res === self::RST) {
            throw new GrpcException(get_object_vars($this->resStatus));
        }

        if ($res === self::OK) {
            return [];
        }

        $resArr = $this->parseToArray($res);

        return $resArr;
    }
}