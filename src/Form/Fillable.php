<?php

namespace Ichynul\Labuilder\Form;

use Ichynul\Labuilder\Inface\Renderable;

interface Fillable extends Renderable
{
    /**
     * Undocumented function
     *
     * @param array|Model $data
     * @return $this
     */
    public function fill($data = []);
}
