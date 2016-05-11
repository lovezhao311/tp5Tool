# thinkphp v5 auth

基于 `thinkphp 5.x` 的 `Auth` 权限验证

# 使用方法

### 添加配置文件 `tp5tool`

~~~php
return [
    /**
     * 用户模型
     */
    "user"      => "\\app\\model\\User",

    /**
     * 用户组模型
     */
    "role"      => "\\app\\model\\Role",

    /**
     * 权限模型
     */
    "rule"      => "\\app\\model\\Rule",

    /**
     * 用户&权限关联表
     */
    "role_rule" => "role_rule",
];
~~~

### 添加模型对应关系
> 在 用户模型&&用户组模型&&权限模型 三个模型里 引入对应的 `Traits`
~~~php
    use \Tp5Tool\Auth\Traits\AuthUser;
    use \Tp5Tool\Auth\Traits\AuthRule;
    use \Tp5Tool\Auth\Traits\AuthRole; 
~~~

#示例

###用户是否有该权限
~~~php
    $user = \app\model\User::find(1);
    $viod = (new \Tp5Tool\Auth\Auth($user))->can(['index/index', 'index/main'], true);
~~~

###用户组所有权限
~~~php
    $role = \app\model\Role::find(1);
    $ruleRows = (new \Tp5Tool\Auth\Auth())->ruleByRole($role);
~~~