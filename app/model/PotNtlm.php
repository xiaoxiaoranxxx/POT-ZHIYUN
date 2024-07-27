<?php

namespace app\model;

use think\Model;

class PotNtlm extends Model
{
    protected $name = 'pot_ntlm';
    protected $pk = 'id';


    public static function getallconut()
    {
        return self::count();
    }

    public static function listlog($params)
    {

        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['userip', 'date'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['userip', 'date'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }

    public function searchUseripAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('userip', $value);
        }
    }

    public function searchDateAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('date', $value);
        }
    }
}
