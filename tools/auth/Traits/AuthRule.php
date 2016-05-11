<?php

namespace Tp5Tool\Auth\Traits;

use \think\Config;

trait AuthRule
{
    /**
     * 关联菜单&权限表
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-04-21T16:27:30+0800
     * @return   [type]                   [description]
     */
    public function rules()
    {
        return $this->belongsToMany(Config::get('Tp5Tool.role'), Config::get('Tp5Tool.role_rule'));
    }
}
