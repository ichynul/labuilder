<?php

namespace Ichynul\Labuilder\Traits\Actions;

/**
 * 删除
 */

trait HasDestroy
{
    public function destroy()
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
            if (!$this->canDestroy($id)) {
                continue;
            }
            if ($this->dataModel->where($this->getPk(), $id)->delete()) {
                $res += 1;
                $this->afterDestroy($id);
            }
        }

        if ($res) {
            return response()->json(
                ['code' => 1, 'msg' => '成功删除' . $res . '条数据']
            );
        } else {
            return response()->json(
                ['code' => 0, 'msg' => '删除失败']
            );
        }
    }

    /**
     * 判断是否可以删除 ，可使用模型的befroDelete事件替代
     *
     * @param mixed $id
     * @return boolean
     */
    protected function canDestroy($id)
    {
        if (!empty($this->delNotAllowed) && in_array($id, $this->delNotAllowed)) {
            return false;
        }
        // 其他逻辑
        return true;
    }

    /**
     * 删除以后，可使用模型的afterDelete事件替代
     *
     * @param mixed $id
     * @return boolean
     */
    protected function afterDestroy($id)
    {
        return true;
    }
}
