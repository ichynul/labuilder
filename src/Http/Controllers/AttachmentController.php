<?php

namespace Ichynul\Labuilder\Http\Controllers;

use Ichynul\Labuilder\Models\Attachment as AttachmentModel;
use Ichynul\Labuilder\Traits\Actions\HasAutopost;
use Ichynul\Labuilder\Traits\Actions\HasBase;
use Ichynul\Labuilder\Traits\Actions\HasIndex;
use Illuminate\Routing\Controller;

/**
 * Undocumented class
 * @title 文件管理
 */
class AttachmentController extends Controller
{
    use HasBase;
    use HasIndex;
    use HasAutopost;

    protected $dataModel;

    public function __construct()
    {
        $this->dataModel = new AttachmentModel;

        $this->pageTitle = '文件管理';
        $this->postAllowFields = ['name'];
        $this->pagesize = 8;
    }

    protected function filterWhere()
    {
        $searchData = request()->post();

        $where = [];

        if (!empty($searchData['name'])) {
            $where[] = ['name', 'like', '%' . $searchData['name'] . '%'];
        }

        if (!empty($searchData['url'])) {
            $where[] = ['url', 'like', '%' . $searchData['url'] . '%'];
        }

        $ext = request('ext');

        if ($ext) {
            $where[] = ['suffix', 'in', explode(',', $ext)];
        }

        if (!empty($searchData['suffix'])) {
            $where[] = ['suffix', 'in', $searchData['suffix']];
        }

        return $where;
    }

    /**
     * 构建搜索
     *
     * @return void
     */
    protected function buildSearch()
    {
        $search = $this->search;

        $search->text('name', '文件名', '6 col-xs-6')->size('4 col-xs-4', '8 col-xs-8')->maxlength(55);
        $search->text('url', 'url链接', '6 col-xs-6')->size('4 col-xs-4', '8 col-xs-8')->maxlength(200);

        $exts = [];
        $arr = [];

        $ext = request()->get('ext');
        if ($ext) {
            $arr = explode(',', $ext);
        } else {
            $allow_suffix = config('labuilder.allow_suffix');
            $arr = explode(',', $allow_suffix);
        }

        foreach ($arr as $a) {
            $exts[$a] = $a;
        }

        $search->multipleSelect('suffix', '后缀', '6 col-xs-6')->size('4 col-xs-4', '8 col-xs-8')->options($exts);
    }
    /**
     * 构建表格
     *
     * @return void
     */
    protected function buildTable(&$data = [])
    {
        $table = $this->table;

        $choose = request()->get('choose');

        $table->show('id', 'ID');
        $table->text('name', '文件名')->autoPost();
        $table->file('file', '文件')->thumbSize(50, 50);
        if (!$choose) {
            $table->show('mime', 'mime类型');
            $table->show('size', '大小')->to('{val}MB');
            $table->show('suffix', '后缀')->getWrapper()->addStyle('width:80px');
            $table->show('storage', '位置');
        }

        $table->raw('url', '链接')->to('<a href="{val}" target="_blank">{val}</a>');
        $table->show('created_at', '添加时间')->getWrapper()->addStyle('width:160px');

        $table->getToolbar()
            ->btnRefresh()
            ->btnImport(url('/admin/attachments/afterSuccess'), '', ['250px', '245px'], 0, '上传')
            ->btnToggleSearch();

        foreach ($data as &$d) {
            $d['file'] = $d['url'];
        }

        unset($d);

        $table->useCheckbox(false);

        if ($choose) {
            $table->getActionbar()->btnPostRowid('choose', url('choose', ['id' => request('id'), 'limit' => request('limit')]), '选择', 'btn-success', 'mdi-note-plus-outline', '', false);
        } else {
            $table->useActionbar(false);
        }
    }

    /**
     * Undocumented function
     *
     * @title 选中文件
     * @return mixed
     */
    public function choose($id, $limit)
    {
        $ids = request('ids', '0');
        if (empty($ids)) {
            $this->error('参数有误');
        }

        $file = $this->dataModel->get($ids);

        if ($file) {
            $script = '';

            if ($limit < 2) {
                $script = "<script>parent.$('#{$id}').val('{$file['url']}');parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";
            } else {
                $script = "<script>parent.$('#{$id}').val(parent.$('#{$id}').val()+(parent.$('#{$id}').val()?',':'')+'{$file['url']}');parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";
            }

            return response()->json(['code' => 1, 'script' => $script]);
        } else {
            $this->error('文件不存在');
        }
    }

    /**
     * Undocumented function
     *
     * @title 上传成功
     * @return mixed
     */
    public function afterSuccess()
    {
        $builder = $this->builder('上传成功');

        $builder->addScript('parent.lightyear.notify("上传成功","success");parent.$(".search-refresh").trigger("click");parent.layer.close(parent.layer.getFrameIndex(window.name));'); //刷新列表页

        return $builder->render();
    }
}
