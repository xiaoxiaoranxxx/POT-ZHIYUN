<?php

namespace app\model;


use think\Model;

class Sysinfo extends Model
{
    protected $name = 'cms_sysinfo';
    protected $pk = 'id';

    public static function getlist()
    {
        $result = self::select()->column('value');
        return $result;
    }

    public static function updatelist($params)
    {
        // {"title1":"1","title2":"1","License":"1"}
        // $where = $params['key'];

        $userModel = new self();
        if ($params['dataType'] == "index") {
            $result = $userModel->saveAll([
                ['id' => 1, 'value' => $params['title1']],
                ['id' => 2, 'value' => $params['title2']],
                ['id' => 3, 'value' => $params['License']],
            ]);
        } elseif ($params['dataType'] == "syspath") {
            $result = $userModel->saveAll([
                ['id' => 4, 'value' => $params['path']]
            ]);
        } elseif ($params['dataType'] == "mail") {
            $result = $userModel->saveAll([
                ['id' => 5, 'value' => $params['server']],
                ['id' => 6, 'value' => $params['username']],
                ['id' => 7, 'value' => $params['password']],
                ['id' => 8, 'value' => $params['port']],
            ]);
        } else {
            $result=false;
        }

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
}
