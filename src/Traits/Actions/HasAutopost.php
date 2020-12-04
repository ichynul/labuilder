<?php

namespace Ichynul\Labuilder\Traits\Actions;

/**
 * 字段编辑
 */

trait HasAutopost
{
    public function autopost()
    {
        return $this->_autopost();
    }

    protected function _autopost()
    {
        $id = request('id', '');
        $name = request('name', '');
        $value = request('value', '');

        if (empty($id) || empty($name)) {
            return response()->json(
                ['code' => 0, 'msg' => '参数有误']
            );
        }

        if (!empty($this->postAllowFields) && !in_array($name, $this->postAllowFields)) {
            return response()->json(
                ['code' => 0, 'msg' => '不允许的操作,字段[' . $name . ']不在postAllowFields中' .json_encode($this->postAllowFields)]
            );
        }

        $res = $this->dataModel->where([$this->getPk() => $id])->update([$name => $value]);

        if ($res) {
            return response()->json(
                ['code' => 1, 'msg' => '修改成功']
            );
        } else {
            return response()->json(
                ['code' => 0, 'msg' => '修改失败，或无更改']
            );
        }
    }
}
