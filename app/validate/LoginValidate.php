<?php
namespace app\validate;


use think\Validate;

class LoginValidate extends Validate{
    protected $rule=[
        'username'=>'require|length:4,10',
        'password'=>'require',
        'code'=>"require|captcha",
    ];

    protected $message=[
        'username.require'=>'姓名不能为空',
        'password.require'=>'密码不能为空',
        'code.require'=>'验证码不能为空',
        'code.captcha'=>'验证码错误',
    ];

    // 设置两个场景
    // 有验证码 无验证码
    // protected $scene=[
    //     'has_code'=>['name','pwd','code'],
    //     'no_code'=>['name','pwd'],
    //     'add'=>['name','headimg']
    // ];
}