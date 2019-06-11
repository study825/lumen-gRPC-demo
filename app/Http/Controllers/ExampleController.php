<?php

namespace App\Http\Controllers;

use App\Service\SnowService;

class ExampleController extends Controller
{
    protected $snow;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        SnowService $snow
    )
    {
        $this->snow = $snow;
    }


    public function snow()
    {
        return $this->snow->getSnow();
    }
}
