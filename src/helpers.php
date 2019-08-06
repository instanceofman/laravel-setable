<?php

if(! function_exists('setting')) {
    function setting($key, $value = null, $type = null, $forge = false)
    {
        if (is_null($value)) {
            return app('setable')->get($key);
        }

        return app('setable')->set($key, $value, $type, $forge);
    }
}