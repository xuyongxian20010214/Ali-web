<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
class WxUserModel extends Model
{
    //微信用户表

    public $table = 'p_wx_users';
    public $timestamps = false;

    //微信 access_token
    public static $weixin_access_token_key = 'str:weixin:access_token_key:';
	
    /**
	微信 获取access_token
    */
    public static function getAccessToken()
    {
	$access_token = Redis::get(self::$weixin_access_token_key);
	if(!$access_token){
            $url = ' https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APP_SECRET');
	    //echo $url;die;
            $data = json_decode(file_get_contents($url),true);
	    $access_token = $data['access_token'];
	    //写入缓存
	    Redis::set(self::$weixin_access_token_key,$access_token);
	    //设置过期时间
	    Redis::expire(self::$weixin_access_token_key,3600);	    
	}
       	    return $access_token;	
    }
}

