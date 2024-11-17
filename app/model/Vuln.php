<?php

namespace app\model;

use think\Model;
use think\facade\Cache;

class Vuln extends Model
{
    protected $name = 'cms_vuln';
    public static function getlist()
    {
        $data = self::select();
        return $data->toArray();
    }

    public static function add($params)
    {
        $data = [
            'remark' => $params['remark'],
            'range' => $params['range'],
            'rerequest' => $params['rerequest'],
            'date' => gettime(),
            'response' => $params['response'],
            'status' => $params['status']
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
        $result = self::where('range', $params['range'])->where('id', $params['id'])->delete();
        if ($result) {
            return ['error' => 0, "msg" => '删除成功'];
        } else {
            return ['error' => 1, "msg" => '删除失败'];
        }
    }


    public static function updd($params)
    {
        $data = [
            'remark' => $params['remark'],
            'range' => $params['range'],
            'rerequest' => $params['rerequest'],
            'date' => gettime(),
            'response' => $params['response'],
            'status' => $params['status']
        ];
        $result = self::where('id', Cache::get('vulnkeywhere')['id'])->data($data)->update();
        if ($result) {
            return ['error' => 0, "msg" => '修改成功'];
        } else {
            return ['error' => 1, "msg" => '修改失败'];
        }
    }

    public static function vulnkeywhere($params)
    {
        // {"id":24}

        $result = self::where('id', $params['id'])->find();
        if ($result) {
            Cache::set('vulnkeywhere', $result->toArray(), 3600);

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
