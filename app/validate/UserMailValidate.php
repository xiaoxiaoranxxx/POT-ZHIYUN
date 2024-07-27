<?php

declare(strict_types=1);

namespace app\validate;

use think\Validate;

class UserMailValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username' => 'require|length:4,10',
        'email' => 'require|email',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require' => '姓名不能为空',
        'email'        => '邮箱格式错误'
    ];
}
