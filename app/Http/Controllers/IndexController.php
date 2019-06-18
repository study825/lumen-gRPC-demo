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

    public function snow()
    {
        $data =  $this->snow->getSnow();

        return $this->success($data);
    }
}