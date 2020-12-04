<?php

namespace Ichynul\Labuilder\Traits;

use Illuminate\Support\Collection;

trait HasOptions
{
    protected $options = [];

    /**
     * Undocumented function
     *
     * @param array|Collection $options
     * @return $this
     */
    public function options($options)
    {
        if ($options instanceof Collection) {
            return $this->optionsData($options);
        }
        $this->options = $options;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $texts
     * @return $this
     */
    public function texts($texts)
    {
        $options = [];
        foreach ($texts as $text) {
            $options[$text] = $text;
        }

        $this->options = $options;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Undocumented function
     *
     * @param Collection $optionsData
     * @param string $textField
     * @param string $idField
     * @return $this
     */
    public function optionsData($optionsData, $textField = '', $idField = 'id')
    {
        $options = [];
        foreach ($optionsData as $data) {
            if (empty($idField)) {
                $idField = $data->getPk();
            }
            if (empty($textField)) {
                $textField = isset($data['opt_text']) ? 'opt_text' : 'name'; //模型需要实现[getOptTextAttr]，否则看是否刚好有name这个字段;
            }

            $options[$data[$idField]] = $data[$textField];
        }
        $this->options = $options;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function beforOptions($options)
    {
        $this->options = $options + $this->options;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function afterOptions($options)
    {
        $this->options = $this->options + $options;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function mergeOptions($options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
}
