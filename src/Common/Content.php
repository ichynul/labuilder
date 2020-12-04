<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Inface\Renderable;

class Content implements Renderable
{
    /**
     * Undocumented variable
     *
     * @var mixed
     */
    protected $content;

    protected $displayContent = '';

    protected $partial = false;

    /**
     * Undocumented function
     *
     * @return string|mixed
     */
    public function render()
    {
        if ($this->displayContent) {

            if ($this->partial) {

                $this->partial = false;

                return $this;
            }

            return $this->displayContent;
        }

        if ($this->partial) {
            return $this->content;
        }

        return $this->content->render();
    }

    public function __toString()
    {
        $this->partial = false;
        return $this->render();
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function partial($val = true)
    {
        $this->partial = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @param array $vars
     * @return $this
     */
    public function fetch($template = '', $vars = [])
    {
        $this->content = view($template, $vars);

        return $this;
    }

    public function display($content)
    {
        $this->displayContent = $content;

        return $this;
    }

    public function beforRender()
    {
        return $this;
    }
}
