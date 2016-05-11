<?php

namespace Tp5Tool\Auth\Traits;

use \think\Config;

trait AuthRole
{
    /**
     * 关联菜单&权限表
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-04-21T16:27:30+0800
     * @return   [type]                   [description]
     */
    public function rules()
    {
        return $this->belongsToMany(Config::get('tp5tool.rule'), Config::get('tp5tool.role_rule'));
    }
}
