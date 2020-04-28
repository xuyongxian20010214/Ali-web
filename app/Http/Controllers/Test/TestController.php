<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
class TestController extends Controller
{
    //TEST  -测试
    public function index()
    {
       echo __METHOD__;echo '</br>';
        $u = mt_rand(1,99);
        $token = mt_rand(1,99);
        echo 'TOKEN:'. $token;echo '</br>';
        echo 'UID:'.$u;
    }

    /**
     * @return array
     * 冒泡排序
     */
    public function selectSort()
    {
        $arr = array(3,6,9,5,0,2,8,4);
        $count = count($arr);
        if($count<2){
             echo '<pre>';print_r($arr);echo '</pre>';
        }
        for($i=0;$i<$count;$i++){
            for($j=$i+1;$j<$count;$j++){
                if($arr[$i]>$arr[$j])
                {
                    $tmp = $arr[$i];
                    $arr[$i] = $arr[$i];
                    $arr[$i] = $tmp;
                }
            }
        }
        echo '<pre>';print_r($arr);echo '</pre>';
    }
    public function Sort($arr)
    {
        $count = count($arr);
        if($count<2){
            return $arr;
        }
        for($i=0;$i<$count;$i++){
            for($j=$i+1;$j<$count;$j++){
                if($arr[$i]>$arr[$j]){
                    $tmp = $arr[$i];
                    $arr[$i] = $arr[$i];
                    $arr[$i] = $tmp;
                }
            }
        }
        return $arr;
    }

    /**
     * TEST-测试
     * 防刷
     */
    public function FS()
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
        echo '您已访问了本页面'.$num.'次';
        Redis::expire($redis_key,20);
        if($num>10){
            #防刷
            $response = [
                'errCode' => 40001,
                'errMsg' => '访问过于频繁,请稍后再试！',
                'num' => $num
            ];
            Redis::expire($redis_key,10);
            echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
        }
    }

    /**
     * 文件上传
     * @param Request $request
     */
    public function uploadFile(Request $request)
    {
       if($request->isMethod('POST')){
          $file = $request->file('file');
          if($file->isValid()){
              $orgin = $file->getClientOriginalName();
              $ext = $file->getClientOriginalExtension();
              $type = $file->getClientMimeType();
              $file_path = $file->getRealPath();

              $filename = date('Y-m-d H:i:s').'-'.uniqid().'.'.$ext;
              $bool =Storage::disk('uploads')->put($filename,file_get_contents($file_path));
              echo '恭喜xxx上传文件成功';
	      header("Refresh:1;url=/");
          }
           exit;
       }
        return view('uploads.file');


    }
}
