<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;
use app\middleware\CheckLogin;
use think\facade\Session;
use app\model\Admin;
use app\model\AdminLog;
use app\model\Key;
use app\model\PotNtlm;
use app\model\Logte;
use app\model\PotUsersLog;
use app\model\PotUsers;
use think\facade\Cache;
use app\model\PotMail;
use app\model\PotUsersPhone;
use app\model\LogteViewAgclasse;
use app\model\LogteViewAgstatuscode;
use app\model\LogteViewAgday;
use app\model\LogteViewAgmothe;
use app\model\PotViewAgcount;
use app\model\PotViewAgpot;
use app\model\PotDnslog;

class Xadmin extends BaseController
{
    protected $middleware = [
        CheckLogin::class,
    ];

    public function index()
    {
        return View::fetch();
    }

    public function syskey()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Key::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }

    public function syskeyadd()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Key::add($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }
    public function syskeydel()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Key::del($post);
            return json($list);
        } 
    }

    public function syskeyupdate()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Key::updd($post);
            return json($list);
        }
    }

    public function syskeyedit()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Key::syskeywhere($post);
            return json($list);
        } else {
            View::assign('arr', Cache::get('syskeywhere'));
            return View::fetch();
        }
    }

    public function welcome()
    {
        $array = Logte::getMaliciousTraffic();

        $normalTraffic = array_fill(0, 24, 0);
        $maliciousTraffic = array_fill(0, 24, 0);

        foreach ($array as $item) {
            $hour = $item["hour"];
            $normalTraffic[$hour] = isset($item["total_count"]) ? (int)$item["total_count"] : 0;
            $maliciousTraffic[$hour] = isset($item["malicious_count"]) ? (int)$item["malicious_count"] : 0;
        }
        View::assign('normalTraffic', json_encode($normalTraffic));
        View::assign('maliciousTraffic', json_encode($maliciousTraffic));

        View::assign('alllog', Logte::getallconut());
        View::assign('allelog', Logte::getalleconut());
        View::assign('allregister', Logte::getdayconut());
        View::assign('allntlm', Logte::getdayeconut());
        View::assign('top10data', json_encode(Logte::getTop10Data()));
        return View::fetch();
    }


    public function usersetting()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            if ($post['username'] == Session::get('info.username')) {

                return json(Admin::updatemail($post));
            } else {
                return json(['error' => 1, "msg" => '权限不足']);
            }
        } else {
            View::assign('info', Session::get('info'));
            return View::fetch();
        }
    }

    public function userpassword()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $post['username'] = Session::get('info.username');
            if ($post['new_password'] !== $post['again_password'])
                return json(['error' => 1, "msg" => '两次密码不一致']);

            return json(Admin::updatepassword($post));
        } else {
            return View::fetch();
        }
    }

    public function loginlog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = AdminLog::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }

    public function dnslog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotDnslog::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }

    public function syssetting()
    {
        if ($this->request->isJson()) {
            //暂定
        } else {
            return View::fetch();
        }
    }

    public function potntlmlog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotNtlm::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }

    public function logtelog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Logte::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }


    public function logtewhere()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Logte::listwhere($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }

    public function logteupdate()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = Logte::listupdate($post);
            return json($list);
        } else {
            View::assign('update', Session::pull('update'));
            return View::fetch();
        }
    }

    public function potnloginlog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotUsersLog::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }
    public function potuserslog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotUsers::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }
    public function potmaillog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotMail::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }
    public function potphonelog()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotUsersPhone::listlog($post);
            return json($list);
        } else {
            return View::fetch();
        }
    }


    public function potsituation()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = PotViewAgpot::listlog($post);
            return json($list);
        } else {

            View::assign('agcount', json_encode(PotViewAgcount::listlog()));
            return View::fetch();
        }
    }


    public function situation()
    {
        if ($this->request->isJson()) {
            $post = $this->request->param();
            $list = LogteViewAgmothe::listlog($post);
            return json($list);
        } else {
            $list = LogteViewAgclasse::listlog();
            $filteredResults = [];
            foreach ($list as $item) {
                $waf = $item['waf'];
                $count = $item['count'];
                // 去掉为空或带分号的项
                if ($waf === null || strpos($waf, ';') !== false)
                    continue;
                $filteredResults[] = [
                    'value' => $count,
                    'name' => $waf
                ];
            }
            View::assign('agclasse', json_encode($filteredResults));
            View::assign('agstatuscode', json_encode(LogteViewAgstatuscode::listlog()));
            $list = LogteViewAgday::listlog();
            $normalTraffic = array_fill(0, 32, 0);
            $maliciousTraffic = array_fill(0, 32, 0);
            for ($i = 31; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $dates[] = $date;
            }
            foreach ($list as $item) {
                $day = $item["day"];
                $index = array_search($day, $dates);
                $normalTraffic[$index] = $item["count"];
                $maliciousTraffic[$index] = (int)$item["wafstatus_count"];
            }
            View::assign('normalTraffic', json_encode($normalTraffic));
            View::assign('maliciousTraffic', json_encode($maliciousTraffic));
            $a = Logte::getmonthconut();
            $b = Logte::getmontheconut();
            View::assign('month', $a);
            View::assign('monthe', $b);
            View::assign('monthpercentage', number_format($b / $a * 100, 1));
            $c = AdminLog::getmonthconut();
            $d = AdminLog::getmontheconut();
            View::assign('adminmonth', $c);
            View::assign('adminmonthe', $d);
            View::assign('adminmonthpercentage', number_format($d / $c * 100, 1));
            View::assign('dnsmonth', PotDnslog::getmonthconut());
            return View::fetch();
        }
    }

    public function clear()
    {
        echo '{
  "code": 1,
  "msg": "服务端清理缓存成功"
}';
    }


    public function init()
    {
        echo '{
    "homeInfo": {
        "title": "首页",
        "href": "welcome"
    },
    "logoInfo": {
        "title": "智云后台管理",
        "image": "/static/images/logo.png",
        "href": ""
    },
    "menuInfo": [
        {
            "title": "系统管理",
            "icon": "fa fa-address-book",
            "href": "",
            "target": "_self",
            "child": [
                {
                    "title": "首页",
                    "href": "welcome",
                    "icon": "fa fa-home",
                    "target": "_self"
                },
                {
                    "title": "月流量态势",
                    "href": "situation",
                    "icon": "fa fa-binoculars",
                    "target": "_self"
                },
                {
                    "title": "系统登录日志",
                    "href": "loginlog",
                    "icon": "fa fa-heartbeat",
                    "target": "_self"
                },
                {
                    "title": "Dnslog日志",
                    "href": "dnslog",
                    "icon": "fa fa-coffee",
                    "target": "_self"
                },
                {
                    "title": "指纹KEY定义",
                    "href": "syskey",
                    "icon": "fa fa-bullseye",
                    "target": "_self"
                },
                {
                    "title": "蜜罐流量分析",
                    "href": "logtelog",
                    "icon": "fa fa-user-secret",
                    "target": "_self"
                }
            ]
        },
        {
            "title": "钓鱼攻击",
            "icon": "fa fa-lemon-o",
            "href": "",
            "target": "_self",
            "child": [
                {
                    "title": "态势面板",
                    "href": "potsituation",
                    "icon": "fa fa-dot-circle-o",
                    "target": "_self"
                },
                {
                    "title": "NTLM认证",
                    "href": "potntlmlog",
                    "icon": "fa fa-bomb",
                    "target": "_self"
                },
                {
                    "title": "首页登录日志",
                    "href": "potnloginlog",
                    "icon": "fa fa-fighter-jet",
                    "target": "_self"
                },
                {
                    "title": "首页注册用户管理",
                    "href": "potuserslog",
                    "icon": "fa fa-plane",
                    "target": "_self"
                },
                {
                    "title": "邮箱验证码记录",
                    "href": "potmaillog",
                    "icon": "fa fa-rocket",
                    "target": "_self"
                },
                {
                    "title": "手机验证码记录",
                    "href": "potphonelog",
                    "icon": "fa fa-space-shuttle",
                    "target": "_self"
                }
            ]
        }
    ]
}';
    }
}
