<?php

namespace Tp5Tool\Auth\Traits;

trait AuthUser
{
    /**
     * 关联用户组
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-04-21T16:27:30+0800
     * @return   [type]                   [description]
     */
    public function role()
    {
        return $this->belongsTo(Config::get('Tp5Tool.user'));
    }
}
