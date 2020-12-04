<?php

namespace Ichynul\Labuilder\tree;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;

class JSTree implements Renderable
{
    use HasDom;

    protected $data;

    protected $beforeClick = 'alert("未绑定`beforeClick`事件。点击了"+data.instance.get_node(data.selected[0]).text);';

    protected $trigger = '';

    protected $id = 'the-jstree';

    protected $partial = false;

    public function __construct()
    {
        $this->addStyle('float:left;padding-left:5px;');
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
        $tree = [];
        foreach ($treeData as $dep) {

            if ($dep[$pidField] != 0) {
                continue;
            }

            $tree[] = [
                'id' => $dep[$idField],
                'text' => $dep[$textField],
                'state' => [
                    'opened' => true,
                ],
                'children' => isset($dep['children']) ? $dep['children'] : $this->getChildren($treeData, $dep[$idField], $textField, $idField, $pidField),
            ];
        }

        $this->data = [
            [
                'id' => 0,
                'text' => '全部',
                'state' => [
                    'opened' => true,
                ],
                'children' => $tree,
            ],
        ];

        return $this;
    }

    protected function getChildren($treeData, $pid, $textField = 'name', $idField = 'id', $pidField = 'parent_id')
    {
        $children = [];

        foreach ($treeData as $key => $dep) {

            if ($dep[$pidField] == $pid) {

                $children[] = [
                    'id' => $dep[$idField],
                    'text' => $dep[$textField],
                    'state' => [
                        'opened' => true,
                    ],
                    'children' => isset($dep['children']) ? $dep['children'] : $this->getChildren($treeData, $dep[$idField], $pidField),
                ];
            }
        }

        return $children;
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

                    var treeNode = data.instance.get_selected(true)[0];

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
                        $('{$element}').empty().append('<option value="' + treeNode.id + '">' + treeNode.text + '</option>');
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
            'core' : {
                'themes' : {
                    'responsive': false
                },
                'data' : {$data}
            },
            "types" : {
                'default' : {
                    'icon' : 'mdi mdi-folder-outline'
                },
                'file' : {
                    'icon' : 'mdi mdi-file-outline'
                }
            },
            'plugins' : ['types']
        };

        $(document).ready(function () {
            $('#{$this->id}').jstree(setting);
            $('#{$this->id}').on('activate_node.jstree', function(e, data) {
                {$this->beforeClick}
            });
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

        $builder->customCss('/vendor/ichynul/labuilder/builder/js/jstree/style.min.css');
        $builder->customJs('/vendor/ichynul/labuilder/builder/js/jstree/jstree.min.js');

        $builder->addScript($script);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function render()
    {
        $template = 'labuilder::tree.jstree';

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
