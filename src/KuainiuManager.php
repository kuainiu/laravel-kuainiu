<?php

namespace Kuainiu;

use Illuminate\Contracts\Container\Container;

/**
 * Class KuainiuManager.
 */
class KuainiuManager
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * KuainiuManager constructor.
     *
     * @param Container $app
     *
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @return mixed
     */
    public function api()
    {
        return $this->app['kuainiu.api'];
    }
}
