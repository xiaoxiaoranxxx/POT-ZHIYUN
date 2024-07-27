<?php

namespace app\model;

use think\Model;

class PotDnslog extends Model
{
    protected $name = 'pot_dnslog';
    protected $pk = 'id';

    public static function getmonthconut()
    {
        return self::whereBetweenTime('date', strtotime('-31 days'), date('Y-m-d H:i:s'))->count();
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
