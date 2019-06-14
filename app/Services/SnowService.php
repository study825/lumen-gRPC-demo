<?php

namespace App\Services;

use Snowflake\NextRequest;
use Snowflake\SnowflakeClient;

/**
 *  雪花算法service
 */
class SnowService
{
    public function getSnow()
    {
        $opts = [
            'credentials' => \Grpc\ChannelCredentials::createInsecure()
        ];


        $client = new SnowflakeClient(
            "127.0.0.1:6666", $opts
        );

        $request = new NextRequest();
        $request->setServiceId(mt_rand(0, 31));

        $get = $client->Next($request)->wait();
        list($reply, $status) = $get;

        if ($status->code != 0) {
            echo "Call Next() failed, err code: {$status->code}." . PHP_EOL;
            return;
        }

        $id = $reply->getId();

        return $id;
    }
}