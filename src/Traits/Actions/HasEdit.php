<?php

namespace Ichynul\Labuilder\Traits\Actions;

use Ichynul\Labuilder\Logic\Url;

/**
 * 编辑
 */

trait HasEdit
{
    public function edit($id)
    {
        $builder = $this->builder($this->pageTitle, $this->editText, 'edit');
        $data = $this->dataModel->find($id);
        if (!$data) {
            return $builder->layer()->close(0, '数据不存在');
        }
        $form = $builder->form();
        $this->form = $form;
        $this->buildForm(1, $data);
        $form->fill($data);
        $form->method('put');
        $form->action(Url::action($id));

        return $builder->render();
    }

    public function update($id)
    {
        return $this->save($id);
    }
}
