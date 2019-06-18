<?php

namespace component\grpc;

class GrpcException extends \Exception
{
    public function __construct($status)
    {
        parent::__construct($status["details"], $status["code"]);
    }
}