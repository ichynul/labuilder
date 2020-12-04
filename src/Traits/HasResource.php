<?php

namespace Ichynul\Labuilder\Traits;

use Ichynul\Labuilder\Traits\Actions;

trait HasResource
{
    //基础
    use actions\HasBase;
    //按需加载，避免暴露不必要的action

    //列表
    use actions\HasIndex;
    //添加/修改
    use actions\HasCreate;
    use actions\HasEdit;

    //查看
    use actions\HasShow;

    //字段编辑
    use actions\HasAutopost;
    //禁用/启用
    use actions\HasEnable;
    //删除
    use actions\HasDestroy;
}
