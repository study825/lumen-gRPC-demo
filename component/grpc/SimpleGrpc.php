<?php

namespace component\grpc;

class SimpleGrpc extends BaseGrpc {

    public function send($serviceName, $action, $request, $options = [])
    {
        $this->setAttr($serviceName, $action, $request, $options);
        $client = $this->getClient();

        $call   = $client->$action($this->request);
        list($reply, $status) = $call->wait();

        if ($status->code != 0) {
            echo "Call Next() failed, err code: {$status->code}." . PHP_EOL;
            return;
        }

        return $reply;
    }
}