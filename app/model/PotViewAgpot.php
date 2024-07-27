<?php

namespace app\model;
use think\Model;


class PotViewAgpot extends Model
{
    protected $name = 'agpot';
    // 蜜罐整合数据 
    // public static function listlog()
    // {
    //     $data=self::select();
    //     return $data->toArray();
    // }

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
