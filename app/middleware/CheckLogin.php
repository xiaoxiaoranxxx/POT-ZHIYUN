<?php


namespace app\middleware;

use think\facade\Session;

class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has("info")) {
            return redirect('/');
        } else {
            return $next($request);
        }
        
    }
}
