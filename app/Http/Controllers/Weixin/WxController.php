<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use App\Model\WxUserModel;

class WxController extends Controller
{
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


	  //微信 access_token
      public $weixin_access_token_key = 'str:weixin:access_token_key:'; 
      /**
          微信 获取access_token
      */
      public function getAccessToken()
      {
          $access_token = Redis::get($this->weixin_access_token_key);
          if(!$access_token){
              $url = ' https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APP_SECRET');
              //echo $url;die;
              $data = json_decode(file_get_contents($url));
              $access_token = $data['access_token'];
              //写入缓存
              Redis::set($this->weixin_access_token_key,$access_token);
              //设置过期时间
              Redis::expire($this->weixin_access_token_key,3600);
          }
              return $access_token;
      }





    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.WxUserModel::getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url),true);
        echo '<pre>';print_r($data);echo '</pre>';
    }

    /**
     * 获取用户标签
     */
    public function getUserTags()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.WxUserModel::getAccessToken();
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
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.WxUserModel::AccessToken();
        $client = new Client();
        $data = [
            'tag' => [
                'name' => '测试标签',
            ],
        ];
        $r = $client->request('POST',$url,[
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $r->getBody();
    }
}
















