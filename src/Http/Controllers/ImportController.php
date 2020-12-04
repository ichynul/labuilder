<?php

namespace Ichynul\Labuilder\Http\Controllers;

use Ichynul\Labuilder\Common\Builder;
use Illuminate\Routing\Controller;

/**
 * Undocumented class
 * @title 导入
 */
class ImportController extends Controller
{
    /**
     * Undocumented function
     *
     * @title 上传文件弹窗
     * @return mixed
     */
    public function import()
    {
        $acceptedExts = request('acceptedExts', '');
        $fileSize = request('fileSize');
        $pageToken = request('pageToken');
        $successUrl = request('successUrl');

        if ($fileSize == '' || empty($pageToken) || empty($successUrl)) {
            exit('参数有误');
        }

        $importpagetoken = session('importpagetoken');

        $_pageToken = md5($importpagetoken . $acceptedExts . $fileSize);

        if ($_pageToken != $pageToken) {
            exit('验证失败');
        }

        $config = config('labuilder');

        if ($fileSize == 0 || $fileSize == '' || $fileSize > $config['max_size']) {
            $fileSize = $config['max_size'];
        }

        if ($acceptedExts == '*' || $acceptedExts == '*/*' || empty($acceptedExts)) {

            $acceptedExts = $config['allow_suffix'];
        }

        $acceptedExts = explode(',', $acceptedExts);
        $acceptedExts = '.' . implode(',.', $acceptedExts);

        $successUrl = urldecode($successUrl);

        $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

        request()->session()->put('uploadtoken', $token);

        $uploadUrl = url('/admin/uploads/upfiles', ['type' => 'dropzone', 'token' => $token]);

        $vars = [
            'uploadUrl' => $uploadUrl,
            'acceptedExts' => $acceptedExts,
            'fileSize' => $fileSize,
            'successUrl' => $successUrl,
        ];

        $builder = Builder::getInstance();

        $builder->content()->fetch('labuilder::http.import', $vars);

        $builder->customJs('/vendor/ichynul/labuilder/builder/js/dropzone/min/dropzone.min.js');

        $builder->customCss(
            [
                '/vendor/ichynul/labuilder/builder/js/dropzone/min/basic.min.css',
                '/vendor/ichynul/labuilder/builder/js/dropzone/min/dropzone.min.css',
            ]
        );

        $script = <<<EOT
        initDropzone();
EOT;

        $builder->addScript($script);

        $builder->getCsrfToken();

        return $builder->render();
    }

    /**
     * Undocumented function
     *
     * @title 上传成功
     * @return mixed
     */
    public function afterSuccess()
    {
        $builder = Builder::getInstance('提示');

        $fileurl = request('fileurl');

        $script = <<<EOT
        <p>文件上传成功，但未做后续处理：{$fileurl}</p>
        <pre>
        //指定你的处理action，如 url('afterSuccess')
        \$table->getToolbar()->btnImport(url('afterSuccess'));

        //请在你的控制器实现导入逻辑
        public function afterSuccess()
        {
            \$fileurl = request('fileurl');
            if (is_file(app()->getRootPath() . 'public' . \$fileurl)) {
                // 导入逻辑...
                return Builder::getInstance()->layer()->closeRefresh(1, '导入成功：' . \$fileurl);
            }

            \$builder = Builder::getInstance('出错了');
            \$builder->content()->display('&lt;p&gt;' . '未能读取文件:' . \$fileurl . '&lt;/p&gt;');
            return \$builder->render();
        }
        </pre>

EOT;
        $builder->content()->display($script);
        return $builder->render();
    }
}
