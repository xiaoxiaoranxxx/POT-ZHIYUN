<?php

namespace app\model;

use think\facade\Session;
use think\Model;

class Logte extends Model
{
    protected $name = 'pot_logte';
    protected $pk = 'id';

    public static function getMaliciousTraffic()
    {

        $query = self::field('HOUR(date) AS hour')
            ->field('COUNT(*) AS total_count')
            ->field('SUM(CASE WHEN wafstatus = 1 THEN 1 ELSE 0 END) AS malicious_count')
            ->whereTime('date', 'today')
            ->group('hour')
            ->order('hour')
            ->select();

        return $query->toArray();
    }

    public static function getTop10Data()
    {
        $result = self::field('l.userip, l.useraddrip, COUNT(*) AS count')
            ->field('SUM(CASE WHEN l.wafstatus = 1 THEN 1 ELSE 0 END) AS wafstatus_count')
            ->alias('l')
            ->whereRaw('DATE(l.date) = CURDATE()')
            ->group('l.userip, l.useraddrip')
            ->order('count', 'desc')
            ->limit(15)
            ->select();
        return $result->toArray();
    }

    public static function getallconut()
    {
        return self::count();
    }
    public static function getalleconut()
    {
        return self::where('wafstatus', 1)->count();
    }

    public static function getdayconut()
    {
        return self::whereDay('date', date('Y-m-d'))->count();
    }

    public static function getdayeconut()
    {
        return self::whereDay('date', date('Y-m-d'))->where('wafstatus', 1)->count();
    }

    public static function getmonthconut()
    {
        return self::whereBetweenTime('date', strtotime('-31 days'), date('Y-m-d H:i:s'))->count();
    }
    public static function getmontheconut()
    {
        return  self::whereBetweenTime('date', strtotime('-31 days'), date('Y-m-d H:i:s'))->where('wafstatus', 1)->count();
    }

    public static function listlog($params)
    {

        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['userip', 'useraddrip', 'date', 'statuscode', 'wafstatus', 'waf', 'confusion'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['userip', 'useraddrip', 'date', 'statuscode', 'wafstatus', 'waf', 'confusion'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }

    public static function listwhere($params)
    {
        // [key=>1]
        $where = $params['key'];
        $data = self::where('id', $where)->find();
        if ($data) {
            $responseData = [
                'code' => 0,
                'msg' => '成功',
                // 'data' => $data->toArray()
            ];
            Session::set('update.id', $data['id']);
            Session::set('update.date', $data['date']);
            Session::set('update.waf', $data['waf']);
        } else {
            $responseData = [
                'code' => 1,
                'msg' => '错误',
            ];
        }

        return $responseData;
    }

    public static function listupdate($params)
    {
        // ['key'=>1,'waf'=>'命令执行']
        $where = $params['key'];
        $result = self::where('id', $where)->data(['waf' => $params['waf']])->update();
        if ($result) {
            $responseData = [
                'code' => 0,
                'msg' => '修改成功'
            ];
        } else {
            $responseData = [
                'code' => 1,
                'msg' => '修改失败',
            ];
        }
        return $responseData;
    }



    public function searchUseripAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('userip', $value);
        }
    }

    public function searchUseraddripAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('useraddrip', 'like', '%' . $value . '%');
        }
    }

    public function searchDateAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('date', $value);
        }
    }

    public function searchStatuscodeAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('statuscode', $value);
        }
    }

    public function searchWafstatusAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('wafstatus', $value);
        }
    }

    public function searchConfusionAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('confusion', $value);
        }
    }

    public function searchWafAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('waf', 'like', '%' . $value . '%');
        }
    }
}
