<?php

namespace Ichynul\Labuilder\tree;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;

class ZTree implements Renderable
{
    use HasDom;

    protected $data;

    protected $beforeClick = 'alert("未绑定`beforeClick`事件。点击了"+treeNode.id);';

    protected $trigger = '';

    protected $id = 'the-ztree';

    protected $partial = false;

    public function __construct()
    {
        $this->addStyle('float:left;');
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
     * @param string $val
     * @return $this
     */
    public function setId($val)
    {
        $this->id = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    public function data($val)
    {
        $this->data = $val;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Collection $optionsData
     * @param string $textField
     * @param string $idField
     * @param string $pidField
     * @return $this
     */
    public function fill($treeData, $textField = 'name', $idField = 'id', $pidField = 'parent_id')
    {
        $tree = [
            [
                'id' => 0,
                'pId' => '',
                'name' => '全部',
            ],
        ];

        foreach ($treeData as $dep) {
            $tree[] = [
                'id' => $dep[$idField],
                'pId' => $dep[$pidField],
                'name' => $dep[$textField],
            ];
        }

        $this->data = $tree;

        return $this;
    }

    /**
     * Undocumented function
     * $('input[name="category_id"]').val(treeNode.id);$('.row-submit').trigger('click');
     *
     * @param string $script
     * @return $this
     */
    public function beforeClick($script)
    {
        $this->beforeClick = $script;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $element
     * @return $this;
     */
    public function trigger($element)
    {
        $this->trigger = $element;
        $this->beforeClick = <<<EOT

                    if(!$('{$element}').length)
                    {
                        var __field__ = document.createElement("input");
                        __field__.type = "hidden";
                        __field__.name = '{$element}'.replace(/^\.row\-/, '');
                        __field__.className = '{$element}'.replace(/^\./, '');

                        $('form.search-form').append(__field__);
                    }

                    if($('{$element}').hasClass('select2-use-ajax'))
                    {
                        $('{$element}').empty().append('<option value="' + treeNode.id + '">' + treeNode.name + '</option>');
                    }
                    else
                    {
                        $('{$element}').val(treeNode.id);
                    }
                    $('{$element}').trigger('change');
                    $('.row-refresh').trigger('click');

EOT;
        return $this;
    }

    public function beforRender()
    {
        $data = json_encode($this->data);
        $script = <<<EOT

        var setting = {
            view: {
              addHoverDom: false,
              removeHoverDom: false,
              selectedMulti: false
            },
            check: {
              enable: false
            },
            data: {
              simpleData: {
                enable: true
              }
            },
            edit: {
              enable: false
            },
            callback: {
                beforeClick: function(treeId, treeNode, clickFlag){
                    {$this->beforeClick}
                }
            }
        };
        var zNodes = {$data};

        $(document).ready(function () {
            var treeObj = $.fn.zTree.init($("#{$this->id}"), setting, zNodes);
            treeObj.expandAll(true);
        });

        var leftw = $('.tree-div').parent('div').outerWidth();
        var rightw = $('.tree-div').parent('div').next('div').outerWidth();

        $('.tree-div .hide-left').click(function(){
            var parent = $('.tree-div').parent('div');
            if($(this).children('i').hasClass('mdi-format-horizontal-align-left'))
            {
                parent.next('div').css('width' ,(rightw + leftw - 15) + 'px');
                parent.css({'width':'15px' ,'padding':0 ,'margin':0});
                $(this).next('.ztree').addClass('hidden');
                $(this).children('i').removeClass('mdi-format-horizontal-align-left').addClass('mdi mdi-format-horizontal-align-right');
            }
            else
            {
                parent.next('div').removeAttr('style');
                parent.removeAttr('style');
                $(this).next('.ztree').removeClass('hidden');
                $(this).children('i').removeClass('mdi-format-horizontal-align-right').addClass('mdi mdi-format-horizontal-align-left');
            }
        });

EOT;

        $builder = Builder::getInstance();

        $builder->customCss('/vendor/ichynul/labuilder/builder/js/zTree_v3/css/materialDesignStyle/materialdesign.css');
        $builder->customJs('/vendor/ichynul/labuilder/builder/js/zTree_v3/js/jquery.ztree.all.min.js');

        $builder->addScript($script);

        $builder->addStyleSheet('
        .ztree li a.curSelectedNode
        {
            color : green;
        }
');
        return $this;
    }

    public function render()
    {
        $template = 'labuilder::tree.ztree';

        $vars = [
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
            'id' => $this->id,
        ];

        $viewshow = view($template, $vars);

        if ($this->partial) {
            return $viewshow;
        }

        return $viewshow->render();
    }
}
