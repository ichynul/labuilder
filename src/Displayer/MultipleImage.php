<?php

namespace Ichynul\Labuilder\Displayer;

class MultipleImage extends MultipleFile
{
    public function render()
    {
        $this->image();

        $this->canUpload = !$this->readonly && $this->canUpload && empty($this->extKey);

        if (!$this->canUpload) {
            if (empty($this->default)) {
                $this->default = '/vendor/ichynul/labuilder/builder/images/default.png';
            }
        }

        return parent::render();
    }
}
