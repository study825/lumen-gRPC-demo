<?php

namespace App\Services;

use Snowflake\NextRequest;
use component\grpc\SimpleGrpc;

/**
 *  雪花算法service
 */
class SnowService
{
    public function getSnow()
    {
        $simpleGrpc = new SimpleGrpc(config('grpc.host_server'), 'Snowflake');

        $request = new NextRequest();
        $request->setServiceId(mt_rand(0, 31));

        $result = $simpleGrpc->send('SnowflakeClient', 'Next', $request);

        $id = $result->getId();

        return $id;
    }
}