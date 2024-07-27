<?php

namespace app\model;
use think\Model;


class LogteViewAgmothe extends Model
{
    protected $name = 'agmothe';
    // 近一个月攻击者聚合
    public static function listlog($params)
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 8;
        $start = ($page - 1) * $limit;
        $data = self::limit($start, $limit)->select();
        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::count(),
            'data' => $data->toArray()
        ];

        return $responseData;
    }
}
