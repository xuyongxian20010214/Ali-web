<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Redis;
use Closure;

class CheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        #用户ip
        $uip = $_SERVER['SERVER_ADDR'];
        #访问的路由
        $path = $_SERVER['REQUEST_URI'];
        #加密路由
        $mpath = substr(md5($path),0,10);
        $redis_key = 'str:'.$mpath.'uip:'.$uip;
        //echo 'REDIS_KEY:'.$redis_key;echo '</br>';
        $num = Redis::incr($redis_key);
        echo '您已访问了本页面:'.$num.'次';echo '</br>';
        Redis::expire($redis_key,20);
        if($num>10){
            #防刷
            $response = [
                'errCode' => 40001,
                'errMsg' => '访问过于频繁,请稍后再试!',
                'num' => $num
            ];
            Redis::expire($redis_key,10);
            echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
        }
        return $next($request);
    }
}
