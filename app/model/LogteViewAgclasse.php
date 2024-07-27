<?php

namespace app\model;
use think\Model;

class LogteViewAgclasse extends Model
{
    protected $name = 'agclasse';
    // 近一个月恶意分布 
    public static function listlog()
    {
        $data=self::select();
        return $data->toArray();
    }
}
