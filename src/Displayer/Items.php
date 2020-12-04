<?php

namespace Ichynul\Labuilder\Displayer;

use Illuminate\Support\Collection;
use Ichynul\Labuilder\Form\ItemsContent;

class Items extends Field
{
    protected $view = 'items';

    protected $data = [];

    /**
     * Undocumented variable
     *
     * @var Form|Search
     */
    protected $form;

    /**
     * Undocumented variable
     *
     * @var ItemsContent
     */
    protected $__items_content__;

    public function created($fieldType = '')
    {
        parent::created($fieldType);

        $this->form = $this->getWrapper()->getForm();
        $this->__items_content__ = $this->form->createItems();

        if (empty($this->name)) {
            $this->name = 'items' . mt_rand(100, 999);
        }

        $this->__items_content__->name($this->name);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed ...$fields
     * @return $this
     */
    public function with(...$fields)
    {
        $this->form->itemsEnd();
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|Collection $data
     * @return $this
     */
    public function value($val)
    {
        return $this->fill($val);
    }

    /**
     * Undocumented function
     *
     * @return ItemsContent
     */
    public function getContent()
    {
        return $this->__items_content__;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function actionRowText($val)
    {
        $this->__items_content__->actionRowText($val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function cnaDelete($val)
    {
        $this->__items_content__->cnaDelete($val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function canAdd($val)
    {
        $this->__items_content__->canAdd($val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function canNotAddOrDelete()
    {
        $this->__items_content__->cnaDelete(false);
        $this->__items_content__->canAdd(false);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|Collection $data
     * @param boolean $overWrite
     * @return $this
     */
    public function fill($data = [], $overWrite = false)
    {
        if (!$overWrite && !empty($this->data)) {
            return $this;
        }

        if (!empty($this->name) && isset($data[$this->name]) &&
            (is_array($data[$this->name]) || $data[$this->name] instanceof Collection)) {
            $this->data = $data[$this->name];
        } else {
            $this->data = $data;
        }

        $this->__items_content__->fill($this->data);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function readonly($val = true)
    {
        $this->cnaDelete(false);
        $this->canAdd(false);
        $this->__items_content__->readonly($val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Collection $data
     * @param string $idField
     * @param boolean $overWrite
     * @return $this
     */
    public function dataWithId($data, $idField = 'id', $overWrite = false)
    {
        if (!$overWrite && !empty($this->data)) {
            return $this;
        }

        $list = [];
        foreach ($data as $d) {
            if (empty($idField)) {
                $idField = $d->getPk();
            }

            $list[$d[$idField]] = $d;
        }
        $this->data = $list;

        $this->__items_content__->fill($this->data);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array|Collection
     */
    public function getData()
    {
        return $this->data;
    }

    protected function actionScript()
    {
        $id = 'items-' . $this->name;

        $script = <<<EOT
        $(document).on('click', "#{$id} .row-__action__ span.action-delete", function () {
           var del = $(this).prev('input').val();
           if(del === '0') {
                $(this).prev('input').val(1);
                $(this).removeClass('btn-danger').addClass('btn-success').attr('title', '恢复');
                $(this).children('i').removeClass('mdi-delete').addClass('mdi-restart');
                $(this).parents('td').prevAll('td').find('.item-field-required').addClass('ignore').removeClass('has-error');
           }
           else if(del === '1') {
                $(this).prev('input').val(0);
                $(this).removeClass('btn-success').addClass('btn-danger').attr('title', '删除');
                $(this).children('i').removeClass('mdi-restart').addClass('mdi-delete');
                $(this).parents('td').prevAll('td').find('.item-field-required').removeClass('ignore');
           }
           else {
                $(this).parents('tr').remove();
           }
        });
        $("#{$id}-temple .item-field").each(function(i, obj){
            if($(obj).hasClass('lyear-checkbox') || $(obj).hasClass('lyear-radio'))
            {
                var boxes = $(obj).find('input');
                boxes.each(function(){
                    $(this).attr('data-name', $(this).attr('name'));
                    $(this).removeAttr('name');
                });
            }
            else if($(obj).hasClass('lyear-switch'))
            {
                var input = $(obj).prev('input');
                $(input).attr('data-name', $(input).attr('name'));
                $(input).removeAttr('name');
            }
            else
            {
                $(obj).attr('data-name', $(obj).attr('name'));
                $(obj).removeAttr('name');
            }
        });

        var i = 1;
        var script ='';

        function reset(obj)
        {
            if($(obj).hasClass('lyear-checkbox') || $(obj).hasClass('lyear-radio'))
            {
                var boxes = $(obj).find('input');
                boxes.each(function(){
                    $(this).attr('data-name', $(this).attr('name'));
                    $(this).removeAttr('name');
                    reset(this);
                });
                return;
            }
            else if($(obj).hasClass('lyear-switch'))
            {
                var input = $(obj).prev('input');
                $(input).attr('data-name', $(input).attr('name'));
                $(input).removeAttr('name');
                reset(input);
                return;
            }
            var name = $(obj).data('name');
            var id = $(obj).attr('id');
            if(!id)
            {
                return;
            }
            id = id.replace('-no-init-script', '');
            var newid = id + '__new__' + i;
            script = script.replace(new RegExp('#' + id,"gm"), '#' + newid);
            $(obj).attr('id', newid);
            name = name.replace(/(.+?)\[__new__\](.+?)/, '$1' + '[__new__' + i + ']$2');
            $(obj).attr('name', name);
            $(obj).removeAttr('data-name');
            if($(obj).hasClass('item-field-required'))
            {
                $(obj).attr('required', true);
            }
        }

        $(document).on('click', "#{$id}-add", function () {
            var node = $("#{$id}-temple").find('tr').clone();
            var fields = node.find('.item-field');
            script = $("#{$id}-script").val();

            i += 1;
            fields.each(function(){
                reset(this);
            });
            $(this).parents('tr').before(node);
            $('.items-empty-text').hide();
            script = script.replace(/-no-init-script/g, '');
            console.log(script);
            if(script)
            {
                if ($('#script-div').size()) {
                    $('#script-div').html('\<script\>' + script + '\</script\>');
                } else {
                    $('body').append('<div class="hidden" id="script-div">' + '\<script\>' + script + '\</script\>' + '</div>');
                }
            }
            //console.log(script);
            //复制出来的，需要对应的初始化脚本
        });

EOT;
        $this->script[] = $script;

        return $this;
    }

    public function beforRender()
    {
        if ($this->__items_content__->hasAction()) {
            $this->actionScript();
        }

        $this->__items_content__->beforRender();

        parent::beforRender();

        return $this;
    }

    public function customVars()
    {
        return [
            'items_content' => $this->__items_content__,
        ];
    }
}
