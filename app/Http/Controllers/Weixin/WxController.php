<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class WxController extends Controller
{
    public $redis_access_token_key = 'str:access:token:'; //微信 access_token
    //微信接口配置
    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'weixin-valid';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 微信
     * 获取Access_Token
     */
    public function AccessToken()
    {
        //获取缓存
        $access_token = Redis::get($this->redis_access_token_key);
        if(!$access_token){
            echo 'No cache!';
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APP_SECRET');
            echo $url;
            $data = json_decode(file_get_contents($url),true);
            $access_token = $data['access_token'];

            //写入缓存
            Redis::set($this->redis_access_token_key,$access_token);
            //s设置过期时间
            Redis::expire($this->redis_access_token_key,3600);
        }else
            return $access_token;
    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->AccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url),true);
        echo '<pre>';print_r($data);echo '</pre>';
    }

    /**
     * 获取用户标签
     */
    public function getUserTags()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->AccessToken();
        $data = json_decode(file_get_contents($url),true);
        var_dump($data); echo '</br>';
        echo '<pre>';print_r($data);echo '</pre>';
    }

    /**
     * 创建用户标签
     * @param null $name
     */
    public function createWxTags($name=null)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->AccessToken();
        
    }
}
















