<?php

namespace component\grpc;

use Grpc\BaseStub;
use Grpc\BidiStreamingCall;
use component\utils\utils;
use component\grpc\GrpcException;
use Google\Protobuf\Internal\Message;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Class BaseGrpc
 * @package component\grpc
 */
class BaseGrpc
{
    const RST = -1;
    const ERR = 1;
    const OK = 0;
    // grpc请求正确状态码
    const STATUS_OK = 0;

    //请求读写
    protected $calls = [];

    // 服务端地址
    protected $hostnames = [];
    // pb文件命名空间前缀
    protected $namespacePrefix = "";
    // 调用的服务名
    protected $serviceName = "";
    // 调用的方法名
    protected $actionName;
    // 发起请求的客户端
    protected $clients = [];
    // 请求
    protected $request;
    //grpc 参数
    protected $options;
    //status 值
    protected $resStatus;

    /**
     * SimpleGrpc constructor.
     * @param $hostnames
     * @param $namespacePrefix
     */
    public function __construct($hostnames, $namespacePrefix)
    {
        $this->hostnames = $hostnames;
        $this->namespacePrefix = $namespacePrefix;
    }

    /**
     * @param null $channel
     *
     * @return mixed
     */
    public function getClient($channel = null)
    {
        $service = '\\' . $this->namespacePrefix . '\\' . $this->serviceName;

        if (empty($this->clients[$service])) {
            $this->clients[$service] = new $service($this->getHostnames(),
                [
                    'credentials' => \Grpc\ChannelCredentials::createInsecure(),
                ],
                $channel);
        }

        return $this->clients[$service];
    }

    /**
     * @return array
     */
    private function getHostnames()
    {
        return $this->hostnames;
    }

    /**
     * @param       $serviceName
     * @param       $action
     * @param       $request
     * @param array $options
     *
     */
    protected function setAttr($serviceName, $action, $request = null, $options = [])
    {
        $this->clear();

        $this->serviceName = $serviceName;
        $this->actionName = $action;
        $this->request = $request;
        $this->options = $options;
    }

    private function clear()
    {
        $this->request = null;

        $this->options = null;

        $this->serviceName = "";

        $this->actionName = "";
    }

    /**
     * @param $data
     *
     * @return array
     * @throws ErrorException
     */
    protected function parseToArray($data)
    {
        $reflects = new \ReflectionClass($data);
        $methods = $reflects->getMethods(\ReflectionMethod::IS_PUBLIC);

        $arr = [];
        foreach ($methods as $methods) {
            $funcName = $methods->getName();

            if (substr($funcName, 0, 3) == 'get') {
                //取key
                $propKey = substr($funcName, 3);
                //取值
                $ref = $data->$funcName();

                //不是对象,直接返回
                if (!is_object($ref)) {
                    $arr[$propKey] = $ref;
                } else if ($ref instanceof RepeatedField) {
                    $arr[$propKey] = [];
                    foreach ($ref as $key => $items) {
                        if (is_object($items)) {
                            $arr[$propKey][] = $this->parseToArray($items);
                        } else {
                            $arr[$propKey][] = $items;
                        }
                    }
                } else if ($ref instanceof Message) {
                    $arr[$propKey] = $this->parseToArray($ref);
                } else {
                    $throwMsg = [
                        'code' => ErrorException::PARAMS_ERR_CODE,
                        'msg' => ErrorException::PARAMS_ERR_MSG
                    ];
                    throw new ErrorException($throwMsg);
                }
            }
        }

        return $this->toSnakeCase($arr);
    }

    /**
     * @param $dataArr
     *
     * @return array
     */
    private function toSnakeCase($dataArr)
    {
        $snakeCaseArr = [];
        foreach ($dataArr AS $key => $item) {
            $snakeCaseKey = utils::toSnakeCase($key);
            if (is_array($item)) {
                $snakeCaseArr[$snakeCaseKey] = $this->toSnakeCase($item);
            } else {
                $snakeCaseArr[$snakeCaseKey] = $item;
            }
        }

        return $snakeCaseArr;
    }

    /**
     * 检测返回值
     * @param \stdClass
     *
     * @return bool
     */
    public function checkStatus($status)
    {
        $this->resStatus = $status;
        if ($this->resStatus->code != self::STATUS_OK) {
            $this->removeClient();
            return self::RST;
        }

        return self::OK;
    }

    /**
     * 关闭连接
     */
    public function removeClient()
    {
        $service = '\\' . $this->namespacePrefix . '\\' . $this->serviceName;
        /**
         * @var BaseStub $client
         */
        $client = $this->clients[$service];
        $client->close();
        $this->clients[$service] = null;
    }



    /**
     * 双向流
     * @return mixed
     */
    protected function getCall()
    {
        $client = $this->getClient();

        if (empty($this->calls[$this->serviceName . $this->actionName])) {
            $action = $this->actionName;

            $this->calls[$this->serviceName . $this->actionName] = $client->$action();
        }

        return $this->calls[$this->serviceName . $this->actionName];
    }

    /**
     * 移除calls值
     */
    protected function removeCall()
    {
        /**
         * @var AbstractCall $call
         */
        if ($this->calls[$this->serviceName . $this->actionName]) {
            $call = $this->calls[$this->serviceName . $this->actionName];
            $call->cancel();

            $this->calls[$this->serviceName . $this->actionName] = null;
        }
    }

    /**
     * @param BidiStreamingCall $call
     *
     * @return     mixed
     */
    protected function read($call)
    {
        try {
            $this->resReply = $call->read();
            if (empty($this->resReply)) {
                return $this->checkStatus($call->getStatus());
            }

            return $this->resReply;
        } catch (\Exception $e) {
            return self::RST;
        }
    }
}