<?php

namespace App\Http\Controllers;

use App\Services\SnowService;

class IndexController extends Controller
{
    protected $snow;

    public function __construct(
        SnowService $snow
    )
    {
        $this->snow = $snow;
    }

    /**
     * 简单grpc 获取雪花算法
     *
     * @return mixed
     */
    public function snow()
    {
        $data = $this->snow->getSnow();

        return $this->success($data);
    }

    /**
     * 双向流获取
     *
     * @return mixed
     */
    public function sayHello()
    {
        $data = $this->snow->getHello();

        return $this->success($data);
    }

}