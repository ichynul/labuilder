<?php

namespace Ichynul\Labuilder\Search;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;
use Ichynul\Labuilder\Traits\HasOptions;

class TabLink implements Renderable
{
    use HasDom;
    use HasOptions;

    private $view = '';

    protected $active = '';
    protected $id = '';
    protected $key = '';

    public function getId()
    {
        if (empty($this->id)) {
            $this->id = 'tab-' . mt_rand(1000, 9999);
        }

        return $this->id;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function key($val)
    {
        $this->key = $val;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function active($val)
    {
        $this->active = $val;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        $id = $this->getId();
        $element = 'row-' . $this->key;
        $script = <<<EOT

    if(!$('.{$element}').length)
    {
        var __field__ = document.createElement("input");
        __field__.type = "hidden";
        __field__.name = '{$this->key}';
        __field__.className = '{$element}';

        $('form.search-form').append(__field__);
    }

    $('body').on('click', '#{$id} .nav-item a', function(){
        var val = $(this).data('val');
        if($('.{$element}').hasClass('select2-use-ajax'))
        {
            $('.{$element}').empty().append('<option value="' + val + '">' + $(this).text() + '</option>');
        }
        else
        {
            $('.{$element}').val(val);
        }
        $('.{$element}').trigger('change');
        $('.row-refresh').trigger('click');
        $('#{$id} .nav-item').removeClass('in active');
        $(this).parent('.nav-item').addClass('in active');
        return false;
    });

    if($('#{$id} .nav-item.tab-{$this->active}').length)
    {
        $('#{$id} .nav-item.tab-{$this->active}').addClass('in active');
    }
    else
    {
        $('#{$id} .nav-item').eq(0).addClass('in active');
    }

EOT;

        Builder::getInstance()->addScript($script);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string|mixed
     */
    public function render()
    {
        $this->view = 'labuilder::table.tab';

        $vars = [
            'options' => $this->options,
            'active' => $this->active,
            'id' => $this->getId(),
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
        ];

        $viewshow = view($this->view, $vars);

        return $viewshow->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
