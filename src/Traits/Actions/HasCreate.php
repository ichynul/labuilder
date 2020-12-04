<?php

namespace Ichynul\Labuilder\Traits\Actions;

use Ichynul\Labuilder\Logic\Url;

/**
 * 添加
 */

trait HasCreate
{
    public function create()
    {
        $builder = $this->builder($this->pageTitle, $this->addText, 'create');
        $form = $builder->form();
        $data = [];
        $this->form = $form;
        $this->buildForm(0, $data);
        $form->fill($data);
        $form->method('post');
        $form->action(Url::action(''));

        return $builder->render();
    }

    public function store()
    {
        return $this->save();
    }
}
