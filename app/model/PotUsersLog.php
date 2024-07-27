<?php

namespace app\model;


use think\Model;

class PotUsersLog extends Model
{
    protected $name = 'pot_users_log';
    protected $pk = 'id';


    public static function listlog($params)
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['loginip', 'logintime'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['loginip', 'logintime'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }

    public function searchLoginipAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('loginip', $value);
        }
    }

    public function searchLogintimeAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('logintime', $value);
        }
    }

}
