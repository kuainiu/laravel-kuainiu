<?php

namespace Kuainiu\Facades;

use Illuminate\Support\Facades\Facade;
use Kuainiu\Wrappers\KuainiuApi;

/**
 * (Facade) Class Kuainiu.
 *
 * @method static Kuainiu
 */
class Kuainiu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'kuainiu';
    }
}
