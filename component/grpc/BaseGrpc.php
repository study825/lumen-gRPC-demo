<?php

namespace component\grpc;

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

    // 服务端地址
    protected $hostnames = [];
    // pb文件命名空间前缀
    protected $namespacePrefix = "";
    // 调用的服务名
    protected $serviceName = "";
    // 调用的方法名
    private $actionName;
    // 发起请求的客户端
    private $clients = [];
    // 请求
    protected $request;
    //grpc 参数
    private $options;

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
     * @CreateTime 2018/11/1 11:53:04
     * @Author     : xingxiaohe@styd.cn
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


}