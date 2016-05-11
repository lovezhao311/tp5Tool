<?php
/**
 * 基于thinkphp 5 auth权限验证类
 *
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * Author: LufyyZhao <LufyyZhao@vip.126.com>
 */
namespace Tp5Tool\Auth;

use \Exception;
use \think\Config;
use \think\Db;

/**
 * 数据表
 *
CREATE TABLE `role` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(32) NOT NULL COMMENT '角色名称',
`status` tinyint(5) NOT NULL DEFAULT '0' COMMENT '是否启用',
`remark` varchar(255) DEFAULT '' COMMENT '简单说明',
`create_time` int(11) NOT NULL,
`update_time` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

CREATE TABLE `role_rule` (
`role_id` int(11) NOT NULL,
`rule_id` int(11) NOT NULL,
UNIQUE KEY `fu` (`role_id`,`rule_id`),
KEY `role_rule_rule_id` (`rule_id`),
CONSTRAINT `role_rule_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
CONSTRAINT `role_rule_rule_id` FOREIGN KEY (`rule_id`) REFERENCES `rule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限关联表';

CREATE TABLE `rule` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单',
`name` varchar(100) NOT NULL COMMENT 'url地址 c+a',
`title` varchar(100) NOT NULL COMMENT '菜单名称',
`icon` varchar(100) DEFAULT NULL COMMENT '图标',
`islink` tinyint(5) NOT NULL DEFAULT '0' COMMENT '是否菜单',
`sort` int(3) NOT NULL DEFAULT '255' COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='权限&菜单表';
---- 数据示例
---- INSERT INTO `rule` (`id`, `parent_id`, `name`, `title`, `icon`, `islink`, `sort`) VALUES ('2', '0', 'index/main', '后台首页', 'glyphicon glyphicon-home', '1', '255');

CREATE TABLE `user` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(32) NOT NULL COMMENT '用户姓名',
`email` varchar(100) NOT NULL COMMENT '用户邮件地址',
`password` varchar(64) NOT NULL COMMENT '用户密码',
`role_id` int(11) NOT NULL COMMENT '用户角色',
`status` tinyint(5) NOT NULL COMMENT '是否启用',
`sex` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0：保密 1：男 2：女',
`birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
`tel` varchar(20) DEFAULT '' COMMENT '电话号码',
`create_time` int(11) NOT NULL COMMENT '创建时间',
`update_time` int(11) NOT NULL COMMENT '更新时间',
PRIMARY KEY (`id`),
UNIQUE KEY `email` (`email`) USING BTREE,
KEY `user_role_id` (`role_id`),
CONSTRAINT `user_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';
 */

/**
 * 新建一个 Tp5Tool.php 的配置文件
 * return [
 *     'user'      => '\app\model\User', // 用户模型
 *     'role'      => '\app\model\Role', // 用户组模型
 *     'rule'      => '\app\model\Rule', // 权限模型
 *     'role_rule' => 'role_rule',                    // 用户组&权限关系表
 * ]
 */
class Auth
{
    /**
     * 当然登录用户的用户组
     * @var \think\Model;
     */
    protected $group;

    /**
     * 构造函数
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-05-11T11:28:35+0800
     * @param    [type]                   $user 当前登录用户模型
     */
    public function __construct($user = null)
    {
        if (!Config::has('tp5tool')) {
            throw new Exception("找不到配置文件 tp5tool ");
        }

        if ($user != null) {
            $this->group = $user->role;
        }

    }

    /**
     * 检查当前用户是否有操作
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-05-11T11:37:27+0800
     * @param    [type]                   $role       权限
     * @param    boolean                  $requireAll 是否验证全部数据
     * @return   boolean                              [description]
     */
    public function can($rule, $requireAll = false)
    {
        if ($this->group->status == 0) {
            return false;
        }

        if (is_array($rule)) {
            foreach ($rule as $item) {
                $isVoid = $this->ruleNeedsRole($item);
                // 验证全部数据时，有一个不通过就全部都不通过
                if (($requireAll == true) && (!$isVoid)) {
                    return false;
                }
                // 不验证全部数据时，有一个通过就全部都通过
                if (($requireAll == false) && ($isVoid)) {
                    return true;
                }
            }
            // 不验证全部数据时，没有一个通过的情况
            return false;
        } else {
            return $this->ruleNeedsRole($rule);
        }

    }

    /**
     * 获取用户组所有权限
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-05-11T14:30:39+0800
     * @param    string                   $role 用户组模型
     */
    public function ruleByRole($role)
    {
        $this->group = $role;

        return $this->group->rules;
    }

    /**
     * 用户组是否有该权限
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-05-11T11:46:52+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    protected function ruleNeedsRole($rule)
    {
        return $this->group->rules()->where('name', $rule)->find();
    }
}
