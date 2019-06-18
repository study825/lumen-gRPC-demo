<?php

namespace component\grpc;

class ErrorException extends \Exception {
    const PARAMS_ERR_CODE = 3;
    const PARAMS_ERR_MSG = '序列化参数错误';

    public function __construct ($msg) {
        parent::__construct($msg["msg"], $msg["code"]);
    }
}
