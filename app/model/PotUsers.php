<?php

namespace app\model;

use think\facade\Config;
use think\facade\Db;
use think\Model;
use think\facade\Cache;
use app\validate\LoginValidate;
use think\exception\ValidateException;
use app\validate\UserMailValidate;
use think\facade\Session;
use app\model\AdminLog;

class PotUsers extends Model
{
    protected $name = 'pot_users';
    protected $pk = 'id';

    public static function getallconut()
    {
        return self::count();
    }

    //登录验证方法
    public static function loginCheck($params)
    {
        $clientip = get_client_ip();
        $ipaddr = getipaddr();
        $nowtime = gettime();
        $adminlog = new PotUsersLog();
        $adminlog->username = $params['username'];
        $adminlog->password = $params['password'];
        $adminlog->loginip =  $clientip;
        $adminlog->loginipaddr =  $ipaddr;
        $adminlog->logintime = $nowtime;
        $adminlog->useragent = $_SERVER['HTTP_USER_AGENT'];
       

        try {
            validate(LoginValidate::class)
                ->check($params);
            $user = self::withSearch(['username', 'password'], $params)->find();
            if ($user) {
                $arr = ['error' => 0, "msg" => '登录成功,将在2s后跳转到后台', 'user' => $user->toArray()];
                $user->count += 1;
                $user->lastloginip = $clientip;
                $user->lastloginipaddr = $ipaddr;
                $user->lastlogintime = $nowtime;
                $user->save();
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

    public static function loginregister($params)
    {

        // {"cellphone":"2425940130@qq.com","vercode":"111111","password":"123","confirmPassword":"123","nickname":"test123","agreement":"on"}
        // {"username":"1","password":"1","confirmPassword":"1","cellphone":"1@qq.com","vercode":"1"}
        if (Cache::get($params['cellphone']) !== $params['vercode']) {
            return ['error' => 1, "msg" => '验证码不正确'];
        }

        if (self::where('username', $params['nickname'])->find()) {
            return ['error' => 1, "msg" => '该用户名已存在'];
        }

        $data = [
            'email' => $params['cellphone'],
            'username' => $params['nickname'],
            'password' => $params['password'],
            'lastloginip' => get_client_ip(),
            'lastloginipaddr' => getipaddr(),
            'lastlogintime' => gettime(),
            'email' => $params['cellphone'],
            'count' => 1
        ];
        $result = self::create($data);
        if ($result) {
            Cache::delete($params['cellphone']);
            return ['error' => 0, "msg" => '注册成功,将于2s后跳转到登录页'];
        } else {
            return ['error' => 1, "msg" => '注册失败'];
        }
    }


    public static function listlog($params)
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['lastlogintime'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['lastlogintime'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }

    public function searchLastlogintimeAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('lastlogintime', $value);
        }
    }
    public function searchPasswordAttr($query, $value)
    {
        $query->where("password", "=", $value);
    }

    public function searchUsernameAttr($query, $value)
    {
        $query->where("username", "=", $value);
    }
}
