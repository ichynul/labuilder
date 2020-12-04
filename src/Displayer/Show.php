<?php

namespace Ichynul\Labuilder\Displayer;

class Show extends Field
{
    protected $view = 'show';

    protected $sublen = 0;

    protected $more = '...';

    protected $subed = false;

    protected $full = '';

    protected $inline = false;

    /**
     * Undocumented function
     *
     * @param integer $len
     * @param string $more
     * @return $this
     */
    public function cut($len = 0, $more = '...')
    {
        $this->sublen = $len;
        $this->more = $more;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function inline($val)
    {
        $this->inline = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function renderValue()
    {
        $value = parent::renderValue();

        $this->full = '';
        $this->subed = false;
        if ($this->sublen > 0 && $value) {
            if (mb_strlen($value) > $this->sublen) {
                $this->full = $value;
                $value = mb_substr($value, 0, $this->sublen);
                $this->subed = true;
            }
        }

        return $value;
    }

    public function customVars()
    {
        return [
            'subed' => $this->subed,
            'more' => $this->more,
            'full' => $this->full,
            'inline' => $this->inline,
        ];
    }
}
