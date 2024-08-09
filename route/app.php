<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use app\model\Sysinfo;

// Route::get('think', function () {
//     return 'hello,ThinkPHP6!';
// });

Route::get('admin', 'index/adminntlm');
Route::get(Sysinfo::getlist()[3], 'xlogin/login');
Route::post(Sysinfo::getlist()[3], 'xlogin/login');

