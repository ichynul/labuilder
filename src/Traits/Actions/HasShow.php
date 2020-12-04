<?php

namespace Ichynul\Labuilder\Traits\Actions;

use Ichynul\Labuilder\Common\Tab;
use Ichynul\Labuilder\Displayer;
use Ichynul\Labuilder\Form\FieldsContent;
use Ichynul\Labuilder\Form\FRow;
use Ichynul\Labuilder\Form\Step;

/**
 * 查看
 */

trait HasShow
{
    public function show($id)
    {
        if (request()->ajax()) {
            exit('不允许的操作');
        } else {
            $builder = $this->builder($this->pageTitle, $this->viewText, 'show');
            $data = $this->dataModel->find($id);
            if (!$data) {
                return $builder->layer()->close(0, '数据不存在');
            }
            $form = $builder->form();
            $this->form = $form;

            $this->buildForm(2, $data);

            $rows = $this->form->getRows();

            $form->fill($data);

            $this->turn($rows);

            $form->readonly();

            return $builder->render();
        }
    }

    private function turn($rows)
    {
        $displayer = null;

        foreach ($rows as $row) {

            if ($row instanceof Tab || $row instanceof Step) {

                $rows_ = $row->getRows();
                foreach ($rows_ as $row_) {
                    if ($row_['content'] instanceof FieldsContent) {
                        $rows_ = $row_['content']->getRows();
                        $this->turn($rows_);
                    }
                }

                continue;
            }

            if (!$row instanceof FRow) {
                continue;
            }

            $displayer = $row->getDisplayer();

            if ($displayer instanceof displayer\Button || $displayer instanceof displayer\Show || $displayer instanceof displayer\Raw
                || $displayer instanceof displayer\Match || $displayer instanceof displayer\Matches) {
                continue;
            } else if ($displayer instanceof displayer\Items) {

                $content = $displayer->getContent();
                $this->turn($content->getCols());

            } else if ($displayer instanceof displayer\Fields) {

                $content = $displayer->getContent();
                $this->turn($content->getRows());

            } else if ($displayer instanceof displayer\Password) {

                $row->show($displayer->getName(), $row->getLabel())->default('*********');

            } else if ($displayer instanceof displayer\Text || $displayer instanceof displayer\Tags
                || $displayer instanceof displayer\Number || $displayer instanceof displayer\Textarea) {

                $row->show($displayer->getName(), $row->getLabel())->value($displayer->renderValue())->default('-空-');

            } else if ($displayer instanceof displayer\Checkbox || $displayer instanceof displayer\MultipleSelect) {

                $row->matches($displayer->getName(), $row->getLabel())->options($displayer->getOptions())->value($displayer->renderValue());

            } else if ($displayer instanceof displayer\Radio) {

                $row->match($displayer->getName(), $row->getLabel())->options($displayer->getOptions())->value($displayer->renderValue());

            } else if ($displayer instanceof displayer\SwitchBtn) {

                $pair = $displayer->getPair();
                $options = [$pair[0] => '是', $pair[1] => '否'];
                $row->match($displayer->getName(), $row->getLabel())->options($options)->value($displayer->renderValue());

            } else if (!($displayer instanceof displayer\MultipleFile
                || $displayer instanceof displayer\Divider || $displayer instanceof displayer\Html)) {

                $row->raw($displayer->getName(), $row->getLabel())->value($displayer->renderValue())->default('-空-');
            }
            $size = $displayer->getSize();
            $row->getDisplayer()->showLabel($displayer->isShowLabel())->size($size[0], $size[1]);
        }
    }
}
