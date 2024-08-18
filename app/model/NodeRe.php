<?php

namespace app\model;

use think\Model;
use think\facade\Cache;

class NodeRe extends Model
{
    protected $name = 'cms_re';
    // 近一个月状态码分布 

    public static function getlist()
    {
        $data = self::select();
        return $data->toArray();
    }

    public static function add($params)
    {
        // {"dataType":"re","interest":"1","status":"1","revalue":"111111"}
        $data = [
            'node' => $params['interest'],
            'key' => $params['revalue'],
            'status' => $params['status'],
            'remark' => $params['remark']
        ];
        $result = self::create($data);
        if ($result) {
            return ['error' => 0, "msg" => '添加成功'];
        } else {
            return ['error' => 1, "msg" => '添加失败'];
        }
    }

    public static function del($params)
    {
        // {"id":1,"name":18}
        $result = self::where('id', $params['name'])->where('node', $params['id'])->delete();
        if ($result) {
            return ['error' => 0, "msg" => '删除成功'];
        } else {
            return ['error' => 1, "msg" => '删除失败'];
        }
    }
    public static function rekeywhere($params)
    {
        // {"id":24}

        $result = self::where('id', $params['id'])->find();
        if ($result) {
            Cache::set('rekeywhere', $result->toArray(), 3600);

            return ['error' => 0, "msg" => '查询成功'];
        } else {
            return ['error' => 1, "msg" => '查询失败'];
        }
    }

    public static function updd($params)
    {
        // {"ID":"23","status":"1","key":"~~","remark":"fffffffffffffffff"}

        $data = [
            'status' => $params['status'],
            'key' => $params['key'],
            'remark' => $params['remark'],
        ];

        $result = self::where('id', Cache::get('rekeywhere')['id'])->data($data)->update();
        if ($result) {
            return ['error' => 0, "msg" => '修改成功'];
        } else {
            return ['error' => 1, "msg" => '修改失败'];
        }
    }

}
