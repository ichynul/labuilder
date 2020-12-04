<?php
namespace Ichynul\Labuilder\Common;

class Layer
{
    /**
     * Undocumented variable
     *
     * @var mixed
     */
    private $viewShow;

    public function getViewShow()
    {
        return $this->viewShow;
    }

    public function close($success = true, $msg = '操作成功')
    {
        if (request()->ajax()) {
            return response()->json([
                'code' => $success ? 1 : 0,
                'msg' => $msg,
                'layer_close' => 1,
            ]);
        }

        $view = 'labuilder::layer.close';

        $vars = [
            'success' => $success ? 1 : 0,
            'msg' => $msg,
        ];

        $this->viewShow = view($view, $vars);

        return $this->viewShow->render();
    }

    public function closeGo($success = true, $msg = '操作成功', $url)
    {
        if (request()->ajax()) {
            return response()->json([
                'code' => $success ? 1 : 0,
                'msg' => $msg,
                'layer_close_go' => $url,
            ]);
        }

        $view = 'labuilder::layer.closego';

        $vars = [
            'success' => $success ? 1 : 0,
            'msg' => $msg,
            'url' => $url,
        ];

        $this->viewShow = view($view, $vars);

        return $this->viewShow->render();
    }

    public function closeRefresh($success = true, $msg = '操作成功')
    {
        if (request()->ajax()) {
            return response()->json([
                'code' => $success ? 1 : 0,
                'msg' => $msg,
                'layer_close_refresh' => 1,
            ]);
        }

        $view = 'labuilder::layer.closerefresh';

        $vars = [
            'success' => $success ? 1 : 0,
            'msg' => $msg,
        ];

        $this->viewShow = view($view, $vars);

        return $this->viewShow->render();
    }
}
