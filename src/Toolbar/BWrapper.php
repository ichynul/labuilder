<?php

namespace Ichynul\Labuilder\Toolbar;

/**
 * Class Wapper.
 *
 * @method \Ichynul\Labuilder\Toolbar\LinkBtn          linkBtn($name, $label)
 * @method \Ichynul\Labuilder\Toolbar\ActionBtn        actionBtn($name, $label)
 * @method \Ichynul\Labuilder\Toolbar\DropdownBtns     dropdownBtns($items, $label)
 * @method \Ichynul\Labuilder\Toolbar\MultipleActions  multipleActions($items, $label)
 * @method \Ichynul\Labuilder\Toolbar\Actions          actions($items, $label)
 * @method \Ichynul\Labuilder\Toolbar\Html             html($html)
 */

class BWrapper
{
    protected static $displayers = [];

    protected static $displayerMap = [
        'linkBtn' => \Ichynul\Labuilder\Toolbar\LinkBtn::class,
        'actionBtn' => \Ichynul\Labuilder\Toolbar\ActionBtn::class,
        'dropdownBtns' => \Ichynul\Labuilder\Toolbar\DropdownBtns::class,
        'multipleActions' => \Ichynul\Labuilder\Toolbar\MultipleActions::class,
        'actions' => \Ichynul\Labuilder\Toolbar\Actions::class,
        'html' => \Ichynul\Labuilder\Toolbar\Html::class,
    ];

    protected static $defaultFieldClass = [
        \Ichynul\Labuilder\Toolbar\LinkBtn::class => 'btn-xs',
        \Ichynul\Labuilder\Toolbar\ActionBtn::class => 'btn-xs',
        \Ichynul\Labuilder\Toolbar\DropdownBtns::class => 'btn-xs',
        \Ichynul\Labuilder\Toolbar\MultipleActions::class => 'btn-xs',
        \Ichynul\Labuilder\Toolbar\Actions::class => 'btn-xs',
    ];

    /**
     * Undocumented function
     *
     * @param string $name
     * @return boolean
     */
    public static function isDisplayer($name)
    {
        if (empty(static::$displayers)) {
            static::$displayers = array_keys(static::$displayerMap);
        }

        return in_array($name, static::$displayers);
    }

    /**
     * Undocumented function
     *
     * @param string $type
     * @return string
     */
    public static function hasDefaultFieldClass($type)
    {
        if (isset(static::$defaultFieldClass[$type])) {
            return static::$defaultFieldClass[$type];
        }

        return '';
    }

    /**
     * Undocumented function
     *
     * @param array $pair
     * @return void
     */
    public static function extend($pair)
    {
        static::$displayerMap = array_merge(static::$displayerMap, $pair);
    }

    /**
     * Undocumented function
     *
     * @param array $pair
     * @return void
     */
    public static function setdefaultFieldClass($pair)
    {
        static::$defaultFieldClass = array_merge(static::$defaultFieldClass, $pair);
    }
}
