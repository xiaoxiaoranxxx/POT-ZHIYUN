<?php

namespace app\model;


use think\Model;

class AdminLog extends Model
{
    protected $name = 'cms_admin_log';
    protected $pk = 'id';


    public static function getmonthconut()
    {
        return self::whereBetweenTime('logintime', strtotime('-31 days'), date('Y-m-d H:i:s'))->count();
    }
    public static function getmontheconut()
    {
        return  self::whereBetweenTime('logintime', strtotime('-31 days'), date('Y-m-d H:i:s'))->where('status', 1)->count();
    }

    public static function listlog($params)
    {

        // $data = $Form->where($where)->order('id DESC')->limit($start, $limit)->select();
        // $params = json_decode('{page: 1, limit: 15, searchParams: "{"loginip":"","logintime":"",status":"1"}"}', true);

        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['loginip', 'logintime', 'status'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['loginip', 'logintime', 'status'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }

    public function searchLoginipAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('loginip', $value);
        }
    }

    public function searchLogintimeAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('logintime', $value);
        }
    }

    public function searchStatusAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('status', $value);
        }
    }
}
