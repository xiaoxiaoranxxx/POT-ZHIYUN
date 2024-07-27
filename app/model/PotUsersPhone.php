<?php

namespace app\model;

use think\facade\Session;
use think\Model;

class PotUsersPhone extends Model
{
    protected $name = 'pot_users_phone';
    protected $pk = 'id';

    public static function sendphone($params)
    {
        if (!preg_match('/^1[3456789]\d{9}$/', $params['phone'])) {
            return ['error' => 1, "msg" => '手机号格式错误'];
        }
        $data = [
            'phone' => $params['phone'],
            'date' => gettime(), // 设置创建时间
            'userid' => Session::get('users.id'),
            'username' => Session::get('users.username')
        ];
        self::create($data);
        return ['error' => 1, "msg" => '该手机号发送失败,请确保手机号真实有效'];
    }


    public static function listlog($params)
    {

        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['date'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['date'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }

    public function searchDateAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('date', $value);
        }
    }
}
