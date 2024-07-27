<?php

namespace app\model;
use think\Model;


class PotViewAgcount extends Model
{
    protected $name = 'agcount';
    // 蜜罐每日新增差 
    public static function listlog()
    {
        $data=self::select();
        return $data->toArray();
    }
}
