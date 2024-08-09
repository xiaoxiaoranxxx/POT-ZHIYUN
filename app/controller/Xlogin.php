<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Session;
use app\model\Admin;
use app\model\Sysinfo;

class Xlogin extends BaseController
{
    public function login()
    {
        if (Session::has("info")) {   //判断Session中是否有info
            return redirect('/xadmin/');  // 重定向到管路员页面
        }
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $rt = Admin::loginCheck($post);  // 登录判断

            if ($rt['error'] == 0) {
                Session::set("info", $rt['user']);   //账号密码成功则创建Session
                return json($rt);
            } else {
                return json($rt);
            }
        } else {
            View::assign('arr', Sysinfo::getlist()[3]);
            return View::fetch();
        }
    }
    public function loginout()
    {
        if ($this->request->isJson()) {
            if (Session::has("info")) {
                Session::clear(); // 退出登录,清楚Session
                return json(["msg" => 'ok']);
            }
        } else {
            return redirect('/');
        }
    }
}
