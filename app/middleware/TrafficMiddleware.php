<?php

namespace app\middleware;

use Closure;
use think\Request;
use app\model\Logte;
use app\model\PotDnslog;
use think\facade\Session;
use app\model\NodeView;

class TrafficMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        // 执行请求并获取响应,若果session中有admin,则不保存流量
        $response = $next($request);
        if (Session::has("info") && Session::get('info.username') === 'admin')
            return $response;

        // 抓取请求报文
        $requestHeaders = $request->header();
        $requestContent = $request->getContent();
        $requestMethod = $request->method();
        $requestUri = $request->url();

        // 格式化为流量格式
        $RequestStr = $requestMethod . ' ' . $requestUri . ' HTTP/1.1' . "\n";
        $RequestStr .= $this->formatHeaders($requestHeaders);
        $RequestStr .= "\n" . $requestContent . "\n\n";

        $matchresult = $this->wafmatch(urldecode($RequestStr));


        // 保存数据
        $log = new Logte();
        $log->userip = get_client_ip();
        $log->useraddrip = getipaddr();
        $log->date = date('Y-m-d H:i:s');
        $log->requests = base64_encode(urldecode($RequestStr));

        $log->wafstatus = $matchresult[0];
        $log->waf = $matchresult[1];
        $log->wafmatches = $matchresult[2];
        $log->confusion = 0;


        // 混淆-/etc/passwd  海康威视流媒体管理服务器后台任意文件读取漏洞   
        // /systemLog/downFile.php?fileName=../../../../../../../../../../../../../../../etc/passwd
        $decodedRequestStr = urldecode($RequestStr);
        $responseContent = ' ';
        if (strpos($decodedRequestStr, 'systemLog/downFile.php?fileName=')) {
            $filePaths = [
                '../../etc/passwd' => 'root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
sys:x:3:3:sys:/dev:/usr/sbin/nologin
sync:x:4:65534:sync:/bin:/bin/sync
games:x:5:60:games:/usr/games:/usr/sbin/nologin
man:x:6:12:man:/var/cache/man:/usr/sbin/nologin
lp:x:7:7:lp:/var/spool/lpd:/usr/sbin/nologin
mail:x:8:8:mail:/var/mail:/usr/sbin/nologin
news:x:9:9:news:/var/spool/news:/usr/sbin/nologin
uucp:x:10:10:uucp:/var/spool/uucp:/usr/sbin/nologin
proxy:x:13:13:proxy:/bin:/usr/sbin/nologin
www-data:x:33:33:www-data:/var/www:/usr/sbin/nologin
backup:x:34:34:backup:/var/backups:/usr/sbin/nologin
mysql:x:1:1:mysql:/usr/sbin:/usr/sbin/nologin',
                '../../etc/hostname' => 'iZ2zrhywnj8wlb49jjpimhZ',
                '../../etc/hosts' => '127.0.0.1       localhost

# The following lines are desirable for IPv6 capable hosts
::1     localhost ip6-localhost ip6-loopback
ff02::1 ip6-allnodes
ff02::2 ip6-allrouter',
                '../root/.bash_history' => 'exit
ls
cd /usr/lib64/
ls
ls -al libcrypto*
make
sudo su
ls -al libcrypto*
ls -al libssl*
ln -s libssl.so.10 libssl3.so
ln -s  libssl3.so libssl.so.10
ls -al libssl*
chmod -R 777  libssl.so.1.0.2k
sudo su
cd /usr/lib64/
ls
ls libcrypt*
mv libcrypto.so.10 libcrypto.so.10.bak
sudo su
cd 
ls
cd C_sdk/
dir
make
sudo su
clear
ls
rz
yum install lszrz
sudo su
clear
ls
cd C_sdk/
ld
ls
make run
make
make clean
sudo su
ls
cd C_sdk/
ls
make run
dmesg
make run
yum search autofs
sudo yum install libsss_autofs autofs -y
make run
dmesg
make run
dmesg
ls
cd ../
ls
rm openssl-1.0.2k*
ls
sudo rm -rf openssl-1.0.2k*
ls
rz
ls
mkdir tes
ls
cd /home/liyonglei/.ssh
sudo su -
s2
sudo su -'
            ];

            foreach ($filePaths as $path => $content) {
                if (strpos($decodedRequestStr, $path) !== false) {
                    $responseContent = $content;
                    break;
                }
            }
            $response = $response->code(200);
            $response->content($responseContent);
            $log->confusion = 1;
        }

        if ($request->baseUrl() === "/.git/config") {
            $response->content('
[core]
	repositoryformatversion = 0
	filemode = true
	bare = false
	logallrefupdates = true
[remote "origin"]
	url = https://github.com/
	fetch = +refs/heads/*:refs/remotes/origin/*
[branch "master"]
	remote = origin
	merge = refs/heads/master

');
            $response = $response->code(200);
            $log->confusion = 1;
        }

        // sql注入    // 触发条件必须在cookies中或post中
        $pattern = '/\b(?:extractvalue|updatexml)\b.*(?:concat).*?(?:md5\((.*?)\))/';
        if (preg_match($pattern, urldecode($requestContent .= isset($requestHeaders['cookie']) ? $requestHeaders['cookie'] : ''), $matches)) {
            $response->content('{"success":true,"error":0,"msg":"PATH syntax error: \'~' . md5($matches[1]) . '\'"}');
            $response = $response->code(200);
            $log->confusion = 1;
        }

        // dnslog混淆
        $pattern = '/\b(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9-]+\.[a-zA-Z0-9-]+\.[a-zA-Z]{2,})/';
        $charArray = ['java', 'schemas', 'about', 'convert', 'services', 'xmlns', 'docs', 'developer'];
        if (preg_match_all($pattern, $decodedRequestStr, $matchess)) {
            foreach ($matchess[1] as $domain) {
                $segments = explode('.', $domain);
                if (strlen($segments[0]) >= 4 && strlen($segments[1]) >= 4 && strlen($segments[2]) <= 5 && !in_array($segments[0], $charArray)) {
                    $ip = gethostbyname($domain);
                    if ($ip !== $domain) {
                        $dnslog = new PotDnslog();
                        $dnslog->domains = $domain;
                        $dnslog->ip = $ip;
                        $dnslog->ipaddr = getsipaddr($ip);
                        $dnslog->date = $log->date;
                        $dnslog->payload = $log->requests;
                        $dnslog->save();
                        if ($request->baseUrl() !== "/") {
                            $response = redirect('/');
                        }
                        $log->confusion = 1;
                    }
                }
            }
        }

        // 抓取响应报文
        $responseHeaders = $response->getHeader();
        $responseContent = $response->getContent();
        $responseStatus = $response->getCode();
        $responseReason = $this->getResponseReason($responseStatus);

        // 下载报文排除
        if (
            $request->baseUrl() === "/index/download"
        ) {
            $responseContent = "木马文件";
        }

        $ResponseStr = 'HTTP/1.1 ' . $responseStatus . ' ' . $responseReason . "\n";
        $ResponseStr .= $this->formatHeaders($responseHeaders);
        $ResponseStr .= "\n" . $responseContent . "\n";
        $log->statuscode = $responseStatus;
        $log->response = base64_encode(urldecode($ResponseStr));
        $log->save();
        return $response;
    }

    // 流量标签
    private function wafmatch($payload)
    {
        $flag = false;
        $matches = [];
        $rerule = NodeView::getlist();
        foreach ($rerule as $rule) {
            if ($rule['status'] == 0) {
                continue;
            }
            $result = preg_match_all(
                $rule['key'],
                $payload,
                $out,
                PREG_SET_ORDER
            );
            if ($result) {
                $flag = true;
                foreach ($out as &$match) {
                    $matches[$rule['name']][] = $match;
                }
            }
        }

        $outputString = '';
        foreach ($matches as $ruleName => $ruleMatches) {
            $outputString .= "Rule Name: " . $ruleName . " ";
            foreach ($ruleMatches as $index => $match) {
                $ruleMatches[$index] = array_unique($match);
            }
            foreach ($ruleMatches as $index => $match) {
                $outputString .= "Match " . ($index + 1) . " -> " . implode("; ", $match) . " ";
            }
            $outputString .= "\n";
        }
        $allKeys = '';
        foreach ($matches as $ruleName => $ruleMatches) {
            $allKeys .= $ruleName . " ";
        }
        if ($flag) {
            return [1, $allKeys, $outputString];
        } else {
            return [0, NULL, NULL];
        }
    }

    private function formatHeaders($headers)
    {
        $formatted = "";
        foreach (array_reverse($headers) as $name => $value) {
            $formatted .= "{$name}: {$value}\n";
        }
        return $formatted;
    }

    private function getResponseReason($statusCode)
    {
        $statusReasons = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            103 => 'Early Hints',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            208 => 'Already Reported',
            226 => 'IM Used',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Payload Too Large',
            414 => 'URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a Teapot',
            421 => 'Misdirected Request',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Too Early',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
        ];

        return $statusReasons[$statusCode] ?? 'Unknown';
    }
}
