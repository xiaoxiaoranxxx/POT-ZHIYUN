<?php

namespace app\model;

use think\Model;
use think\facade\Cache;

class Key extends Model
{
    protected $name = 'cms_key';
    protected $pk = 'id';


    public static function getrandomstr()
    {

        $data = self::where('status', 1)->orderRaw('rand()')->find();
        
        return $data->toArray();
    }

    public static function add($params)
    {
        // {"username":"admin","status":"1","title":"1111","favicon":"1111","remark1":"1111","remark2":"111"}
        $data = [
            'name' => $params['username'],
            'title' => $params['title'],
            'favicon' => $params['favicon'],
            'date' => gettime(),
            'key1' => $params['remark1'],
            'key2' => $params['remark2'],
            'status' => $params['status']
        ];
        $result = self::create($data);
        if ($result) {
            return ['error' => 0, "msg" => '添加成功'];
        } else {
            return ['error' => 1, "msg" => '添加失败'];
        }
    }

    // [{"id":12,"date":"2024-08-05 09:28:48","name":"1","title":"1","favicon":"1","key1":"1","key2":"1","status":"1"}]

    public static function del($params)
    {
        // {"id":12,"name":"1"}

        $result = self::where('name', $params['name'])->where('id', $params['id'])->delete();
        if ($result) {
            return ['error' => 0, "msg" => '删除成功'];
        } else {
            return ['error' => 1, "msg" => '删除失败'];
        }
    }


    public static function updd($params)
    {
        // {"username":"1","status":"1","title":"1","favicon":"热热热热","remark1":"1111","remark2":"3"}

        $data = [
            'name' => $params['username'],
            'title' => $params['title'],
            'favicon' => $params['favicon'],
            'date' => gettime(),
            'key1' => $params['remark1'],
            'key2' => $params['remark2'],
            'status' => $params['status']
        ];

        $result = self::where('id', Cache::get('syskeywhere')['id'])->data($data)->update();
        if ($result) {
            return ['error' => 0, "msg" => '修改成功'];
        } else {
            return ['error' => 1, "msg" => '修改失败'];
        }
    }

    public static function syskeywhere($params)
    {
        // {"id":24}

        $result = self::where('id', $params['id'])->find();
        if ($result) {
            Cache::set('syskeywhere',$result->toArray(), 3600);
            
            return ['error' => 0, "msg" => '查询成功'];
        } else {
            return ['error' => 1, "msg" => '查询失败'];
        }
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
