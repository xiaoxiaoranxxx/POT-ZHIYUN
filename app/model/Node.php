<?php

namespace app\model;

use think\Model;


class Node extends Model
{
    protected $name = 'cms_node';
    // 近一个月状态码分布 

    public static function getlist()
    {
        $data = self::select();
        return $data->toArray();
    }

    public static function add($params)
    {
        // {"dataType":"node","path":"1"}
        // {"dataType":"re","interest":"1","revalue":"111111"}
        $data = [
            'name' => $params['path'],
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
        // {"dataType":"del","interest":"2"}

        $check = NodeRe::where('node', $params['interest'])->find();
        if($check){
            return ['error' => 1, "msg" => '该值有正则,请删除正则后再删除'];
        }
        $result = self::where('id', $params['interest'])->delete();
        if ($result) {
            return ['error' => 0, "msg" => '删除成功'];
        } else {
            return ['error' => 1, "msg" => '删除失败'];
        }

    }
    

}
