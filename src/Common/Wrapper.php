<?php

namespace Ichynul\Labuilder\Common;

class Wrapper
{
    protected $dfaultDisplayerSize = null;

    protected static $displayers = [];

    protected static $displayerMap = [
        'field' => \Ichynul\Labuilder\Displayer\Field::class,
        'text' => \Ichynul\Labuilder\Displayer\Text::class,
        'textarea' => \Ichynul\Labuilder\Displayer\Textarea::class,
        'html' => \Ichynul\Labuilder\Displayer\Html::class,
        'divider' => \Ichynul\Labuilder\Displayer\Divider::class,
        'raw' => \Ichynul\Labuilder\Displayer\Raw::class,
        'checkbox' => \Ichynul\Labuilder\Displayer\Checkbox::class,
        'radio' => \Ichynul\Labuilder\Displayer\Radio::class,
        'button' => \Ichynul\Labuilder\Displayer\Button::class,
        'select' => \Ichynul\Labuilder\Displayer\Select::class,
        'multipleSelect' => \Ichynul\Labuilder\Displayer\MultipleSelect::class,
        'hidden' => \Ichynul\Labuilder\Displayer\Hidden::class,
        'switchBtn' => \Ichynul\Labuilder\Displayer\SwitchBtn::class,
        'tags' => \Ichynul\Labuilder\Displayer\Tags::class,
        'datetime' => \Ichynul\Labuilder\Displayer\DateTime::class,
        'date' => \Ichynul\Labuilder\Displayer\Date::class,
        'time' => \Ichynul\Labuilder\Displayer\Time::class,
        'datetimeRange' => \Ichynul\Labuilder\Displayer\DateTimeRange::class,
        'dateRange' => \Ichynul\Labuilder\Displayer\DateRange::class,
        'timeRange' => \Ichynul\Labuilder\Displayer\TimeRange::class,
        'color' => \Ichynul\Labuilder\Displayer\Color::class,
        'number' => \Ichynul\Labuilder\Displayer\Number::class,
        'icon' => \Ichynul\Labuilder\Displayer\Icon::class,
        'wangEditor' => \Ichynul\Labuilder\Displayer\WangEditor::class,
        'tinymce' => \Ichynul\Labuilder\Displayer\Tinymce::class,
        'ueditor' => \Ichynul\Labuilder\Displayer\UEditor::class,
        'ckeditor' => \Ichynul\Labuilder\Displayer\CKEditor::class,
        'mdeditor' => \Ichynul\Labuilder\Displayer\MDEditor::class,
        'mdreader' => \Ichynul\Labuilder\Displayer\MDReader::class,
        'editor' => \Ichynul\Labuilder\Displayer\WangEditor::class,
        'rate' => \Ichynul\Labuilder\Displayer\Rate::class,
        'month' => \Ichynul\Labuilder\Displayer\Month::class,
        'year' => \Ichynul\Labuilder\Displayer\Year::class,
        'multipleFile' => \Ichynul\Labuilder\Displayer\MultipleFile::class,
        'file' => \Ichynul\Labuilder\Displayer\File::class,
        'files' => \Ichynul\Labuilder\Displayer\MultipleFile::class,
        'multipleImage' => \Ichynul\Labuilder\Displayer\MultipleImage::class,
        'image' => \Ichynul\Labuilder\Displayer\Image::class,
        'images' => \Ichynul\Labuilder\Displayer\MultipleImage::class,
        'rangeSlider' => \Ichynul\Labuilder\Displayer\RangeSlider::class,
        'match' => \Ichynul\Labuilder\Displayer\Match::class,
        'matches' => \Ichynul\Labuilder\Displayer\Matches::class,
        'show' => \Ichynul\Labuilder\Displayer\Show::class,
        'password' => \Ichynul\Labuilder\Displayer\Password::class,
        'fields' => \Ichynul\Labuilder\Displayer\Fields::class,
        'map' => \Ichynul\Labuilder\Displayer\Map::class,
        'items' => \Ichynul\Labuilder\Displayer\Items::class,
    ];

    protected static $defaultFieldClass = [];

    protected static $using = [];

    /**
     * Undocumented function
     *
     * @param string $class
     * @return void
     */
    public static function addUsing($class)
    {
        static::$using[] = $class;
    }

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

    public static function getDisplayerMap()
    {
        return static::$displayerMap;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public static function getUsing()
    {
        return static::$using;
    }

    /**
     * Undocumented function
     *
     * @param array $pair
     * @return void
     */
    public static function setDefaultFieldClass($pair)
    {
        static::$defaultFieldClass = array_merge(static::$defaultFieldClass, $pair);
    }
}
