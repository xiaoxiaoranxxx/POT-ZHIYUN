<?php

namespace app\model;

use think\Model;


class NodeView extends Model
{
    protected $name = 'wafmatch';
    // 近一个月状态码分布 

    public static function getlist()
    {
        $data = self::select();
        return $data->toArray();
    }

    public static function listlog($params)
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;
        $users = self::order('re_id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }


}
