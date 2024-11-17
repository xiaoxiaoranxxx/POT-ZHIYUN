<?php

namespace app\middleware;

use Closure;
use think\Request;
use app\model\Logte;
use app\model\PotDnslog;
use think\facade\Session;
use app\model\NodeView;
use app\model\Vuln;
class TrafficMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $response = $next($request);
        if (Session::has("info") && Session::get('info.username') === 'admin')
            return $response;

        // 抓取请求报文
        $requestHeaders = $request->header();
        $requestContent = $request->getContent();
        $requestMethod = $request->method();
        $requestUri = $request->url();

        $RequestStr = $requestMethod . ' ' . $requestUri . ' HTTP/1.1' . "\n";
        $RequestStr .= $this->formatHeaders($requestHeaders);
        $RequestStr .= urldecode("\n" . $requestContent . "\n\n");
        $matchresult = $this->wafmatch($RequestStr);

        // 保存数据
        $log = new Logte();
        $log->userip = get_client_ip();
        $log->useraddrip = getipaddr();
        $log->date = date('Y-m-d H:i:s');
        $log->requests = base64_encode($RequestStr);

        $log->wafstatus = $matchresult[0];
        $log->waf = $matchresult[1];
        $log->wafmatches = $matchresult[2];
        $log->confusion = 0;

        $Vulnlist = Vuln::getlist();
        foreach ($Vulnlist as $rule) {
            if ($rule['status'] == 0) {
                continue;
            }
            switch ($rule['range']) {
                case 'requestContent':
                    $retest = $requestContent; 
                    break;
                case 'RequestStr':
                    $retest = $RequestStr; 
                    break;
                case 'requestUri':
                    $retest = $requestUri; 
                    break;
            }
            $result = preg_match(
                $rule['rerequest'],
                $retest,
            );
            if ($result) {
                $response = $response->code(200);
                $response->content($rule['response']);
                $log->confusion = 1;
                break;
            }
        }

        // sql注入    // 触发条件必须在cookies中或post中
        // $pattern = '/\b(?:extractvalue|updatexml)\b.*(?:concat).*?(?:md5\((.*?)\))/';
        // if (preg_match($pattern, urldecode($requestContent .= isset($requestHeaders['cookie']) ? $requestHeaders['cookie'] : ''), $matches)) {
        //     $response->content('{"success":true,"error":0,"msg":"PATH syntax error: \'~' . md5($matches[1]) . '\'"}');
        //     $response = $response->code(200);
        //     $log->confusion = 1;
        // }
        
        // dnslog混淆
        $pattern = '/\b(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9-]+\.[a-zA-Z0-9-]+\.[a-zA-Z]{2,})/';
        $charArray = ['java', 'schemas', 'about', 'convert', 'services', 'xmlns', 'docs', 'developer'];
        if (preg_match_all($pattern, $RequestStr, $matchess)) {
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
