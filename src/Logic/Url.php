<?php

namespace Ichynul\Labuilder\Logic;

class Url
{
    public static $admin_prefix = '';
    /**
     * Build a url with an action in same controller
     *
     * Fro example
     *
     * Current uri : '/admni/users/index'
     *
     * Url::action('edit') => '/admni/users/edit'
     *
     * @param string $action
     * @param array $parameters
     * @param string $currentUri
     * @return string
     */
    public static function action($action = '', $parameters = [], $currentUri = '')
    {
        if (empty($currentUri)) {
            $currentUri = request()->getPathInfo();
        }

        if ($action) {
            $action = '/' . $action;
        }

        if (preg_match('/^\/\w+\/\w+\/?$/', $currentUri)) {
            $url = url(rtrim($currentUri, '/') . $action, $parameters);
        } else if (preg_match('/^.+?\/\w+\/edit$/i', $currentUri)) {
            $url = url(preg_replace('/^(.+?)\/\w+\/edit$/i', '$1' . $action, $currentUri), $parameters);
        } else {
            $url = url(preg_replace('/^(\/\w+\/\w+\/)\w*$/', '$1' . $action, $currentUri), $parameters);
        }

        return $url;
    }

    public static function adminUrl($path, $parameters = [])
    {
        if (empty(static::$admin_prefix)) {
            static::$admin_prefix = config('labuilder.admin_prefix');
        }

        return url('/' . static::$admin_prefix . '/' . ltrim($path, '/'), $parameters);
    }
}
