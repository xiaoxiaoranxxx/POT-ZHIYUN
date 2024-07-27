<?php

namespace app\model;

use think\Model;


class LogteViewAgday extends Model
{
    protected $name = 'agday';
    // 近一个月每天流量态势 
    public static function listlog()
    {
        $data = self::select();
        return $data->toArray();
    }
}
