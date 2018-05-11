<?php

namespace Kuainiu\Facades;

use Illuminate\Support\Facades\Facade;
use Kuainiu\Wrappers\KuainiuApi;

/**
 * (Facade) Class Mollie.
 *
 * @method static KuainiuApi api()
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
