<?php

namespace app\model;

use think\facade\Config;
use think\facade\Db;
use think\Model;
use app\validate\LoginValidate;
use think\exception\ValidateException;
use app\validate\UserMailValidate;
use think\facade\Session;
use app\model\AdminLog;

class Admin extends Model
{
    protected $name = 'cms_admin';
    protected $pk = 'id';

    //登录验证方法
    public static function loginCheck($params)
    {
        $clientip = get_client_ip();
        $ipaddr = getipaddr();
        $nowtime = gettime();
        $adminlog = new AdminLog();
        $adminlog->username = $params['username'];
        $adminlog->password = $params['password'];
        $adminlog->loginip =  $clientip;
        $adminlog->loginipaddr = $ipaddr;
        $adminlog->logintime = $nowtime;
        $adminlog->useragent = $_SERVER['HTTP_USER_AGENT'];

        try {
            validate(LoginValidate::class)
                ->check($params);
            $user = self::withSearch(['username', 'password'], $params)->find();
            if ($user) {
                $arr = ['error' => 0, "msg" => '登录成功,将于1s后跳转到后台', 'user' => $user->toArray()];
                $user->count += 1;
                $user->lastloginip = $clientip;
                $user->lastloginipaddr = $ipaddr;
                $user->lastlogintime = $nowtime;
                $user->save();
                $adminlog->password = md5($params['password']);
                $adminlog->status = 1;
            } else {
                $arr = ['error' => 1, "msg" => '用户名或者密码错误'];
                $adminlog->status = 0;
            }
        } catch (ValidateException $e) { // 验证失败
            //dump($e->getError());
            $arr = ['error' => 1, "msg" => $e->getError()];
            $adminlog->status = 0;
        } catch (\Exception $e) { // 有异常
            // dump($e->getMessage());
            $arr = ['error' => 1, "msg" => '系统错误'];
            $adminlog->status = 0;
        }

        $adminlog->save();
        return $arr;
    }

    public static function updatemail($params)
    {
        try {
            validate(UserMailValidate::class)
                ->check($params);
            $users = self::where('username', $params['username'])->find();
            $users->email = $params['email'];
            $result = $users->save();
            if ($result) {
                $arr = ['error' => 0, "msg" => '修改成功'];
                Session::set('info.email', $params['email']);
            } else {
                $arr = ['error' => 1, "msg" => '修改失败'];
            }
        } catch (ValidateException $e) { // 验证失败
            $arr = ['error' => 1, "msg" => $e->getError()];
        } catch (\Exception $e) {
            $arr = ['error' => 1, "msg" => '系统错误'];
        }
        return $arr;
    }


    public static function updatepassword($params)
    {
        try {
            $users = self::where('username', $params['username'])->find();
            if ($users->password !== md5($params['old_password']))
                return ['error' => 1, "msg" => '旧密码有误'];
            $users->password = md5($params['again_password']);
            $result = $users->save();
            if ($result) {
                $arr = ['error' => 0, "msg" => '修改成功,需要重新登录'];
                Session::clear();
            } else {
                $arr = ['error' => 1, "msg" => '修改失败'];
            }
        } catch (\Exception $e) {
            $arr = ['error' => 1, "msg" => '系统错误'];
        }
        return $arr;
    }




    public function searchPasswordAttr($query, $value)
    {

        $query->where("password", "=", md5($value));
    }

    public function searchUsernameAttr($query, $value)
    {
        $query->where("username", "=", $value);
    }
}
