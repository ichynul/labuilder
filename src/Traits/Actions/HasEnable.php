<?php

namespace Ichynul\Labuilder\Traits\Actions;

/**
 * 禁用/启用
 */

trait HasEnable
{
    public function enable($state)
    {
        $ids = request('ids', '');
        $ids = array_filter(explode(',', $ids), 'strlen');
        if (empty($ids)) {
            return response()->json(
                ['code' => 0, 'msg' => '参数有误']
            );
        }
        $res = 0;
        foreach ($ids as $id) {

            //单独修改一个字段，好多字段是未设置的，处理模型事件容易出错。不触发模型事件，不触发[updated_at]修改
            if ($this->dataModel->where([$this->getPk() => $id])->update([$this->enableField => $state])) {
                $res += 1;
            }
        }
        if ($res) {
            return response()->json(
                ['code' => 1, 'msg' => '成功操作' . $res . '条数据']
            );
        } else {
            return response()->json(
                ['code' => 0, 'msg' => '操作失败']
            );
        }
    }
}
