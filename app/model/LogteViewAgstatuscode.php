<?php

namespace app\model;
use think\Model;


class LogteViewAgstatuscode extends Model
{
    protected $name = 'agstatuscode';
    // 近一个月状态码分布 
    public static function listlog()
    {
        $data=self::select();
        return $data->toArray();
    }
}
