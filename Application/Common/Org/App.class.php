<?php
namespace Common\Org;
class App {

    public static function installed($platform){
        $platformConfig = ['wechat', 'qq', 'weibo', 'alipay'];
        if(in_array($platform, $platformConfig)){
            return self::encode([
                'action' => 'ThirdPartyInstalled',
                'data'=>[
                    'platform'=>$platform
                ]
            ]);
        }else{
            return '';
        }
    }

    public static function login($platform){
        $platformConfig = ['wechat', 'qq', 'weibo'];
        if(in_array($platform, $platformConfig)){
            return self::encode([
                'action' => 'ThirdPartyLogin',
                'data'=>[
                    'platform'=>$platform
                ]
            ]);
        }else{
            return '';
        }
    }

    public static function share($platform, $title, $content, $url, $imageUrl){
        $platformConfig = ['weibo', 'qq', 'qqzone', 'wechat_session', 'wechat_timeline'];
        if(in_array($platform, $platformConfig)) {
            return self::encode([
                'action' => 'ThirdPartyShare',
                'data' => [
                    'title' => $title,
                    'content' => $content,
                    'url' => $url,
                    'image_url' => $imageUrl,
                    'platform' => $platform
                ]
            ]);
        }else{
            return '';
        }
    }

    public static function wechatPay($payInfo){
        return self::encode([
            'action' => 'payWechatPay',
            'data'=>[
                'pay_info'=>$payInfo
            ]
        ]);
    }

    public static function alipay($payInfo){
        return self::encode([
            'action' => 'payAlipay',
            'data'=>[
                'pay_info'=>$payInfo
            ]
        ]);
    }

    public static function openCamera(){
        return self::encode([
            'action' => 'openCamera',
            'data'=>[]
        ]);
    }

    public static function saveImage($url){
        return self::encode([
            'action' => 'saveImage',
            'data'=>[
                'url' => $url
            ]
        ]);
    }

    public static function callPhone($phone){
        return self::encode([
            'action' => 'callPhone',
            'data'=>[
                'phone' => $phone
            ]
        ]);
    }

    public static function pasteBoard($content){
        return self::encode([
            'action' => 'pasteBoard',
            'data'=>[
                'content' => $content
            ]
        ]);
    }

    public static function encode($str){
        return rawurlencode(\Common\Org\Des::share()->encode($str));
    }

    public static function location($json){
        return 'app://lbqb.com/?json='.$json;
    }
}
