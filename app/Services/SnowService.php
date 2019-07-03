<?php

namespace App\Services;

use Grpc;
use Snowflake\StreamReq;
use Snowflake\NextRequest;
use component\grpc\GrpcServices;
//use Grpc\BidiStreamingCall; 原生调用需要
//use Snowflake\SnowflakeClient;

/**
 *  雪花算法service
 */
class SnowService
{
    protected $call;

    public function __construct(Grpc\DefaultCallInvoker $callInvoker)
    {
        $this->call = $callInvoker;
    }

    /**
     * 普通请求/
     * @return array
     * @throws \component\grpc\GrpcException
     */
    public function getSnow()
    {
        // $hostnames = [
        //     0 => config('grpc.host_server')
        // ];
        $hostnames = config('grpc.host_server');

        //1.已封装语法
        $grpc = new GrpcServices($hostnames, 'Snowflake');

        $request = new NextRequest();
        $request->setServiceId(mt_rand(0, 31));

        $result = $grpc->unaryCall('SnowflakeClient', 'Next', $request);

        return $result;

        //2.原生语法请求
        //    $request = (new NextRequest())->setServiceId(mt_rand(0, 31));

        //    $client = new SnowflakeClient($hostnames, [
        //        'credentials' => grpc\ChannelCredentials::createInsecure(),
        //        'timeout' => 1000000,
        //    ]);

        //    list($reply, $status) = $client->Next($request)->wait();
        //    $client->close();

    }

    /**
     * 双向流
     */
    public function getHello()
    {
        // $hostnames = [
        //     0 => config('grpc.host_server')
        // ];
        $hostnames = config('grpc.host_server');

        //1.已封装语法
        $grpc = new GrpcServices($hostnames, 'Snowflake');

        $request = new StreamReq();
        $request->setName('haha');

        $result = $grpc->bidirectionalGrpc('SnowflakeClient', 'Hello', $request);

        return $result;

        //2.原生语法
//        $client = new SnowflakeClient($hostnames, [
//            'credentials' => grpc\ChannelCredentials::createInsecure(),
//            'timeout' => 10,
//        ]);
//
//        $call = $client->Hello();
//        $request = (new StreamReq())->setName('hahh');
//        $call->write($request);
//
//        $call->writesDone();
//        $result = $call->read();
//        dd($result);
    }
}