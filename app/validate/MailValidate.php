<?php

namespace app\validate;


use think\Validate;

class MailValidate extends Validate
{
    protected $rule = [
        'email' => 'email',
    ];

    protected $message = [
        'email' => '邮箱格式错误'
    ];
}
