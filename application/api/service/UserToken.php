<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/19
 * Time: 6:13
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $weChatAppID;
    protected $weChatSecret;
    protected $weChatLoginUrl;

    /**
     * 初始化小程序用户登录登录信息
     * UserToken constructor.
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->weChatAppID = config('wechat.app_id');
        $this->weChatSecret = config('wechat.app_secret');
        $this->weChatLoginUrl = sprintf(config('wechat.login_url'),$this->weChatAppID,$this->weChatSecret,$this->code);
    }

    /**
     * 获取用户信息
     * @param $code
     * @throws Exception
     */
    public function get()
    {
        $result = curl_get($this->weChatLoginUrl);

        $weChatResult = json_decode($result,true);

        if (empty($weChatResult)){
            throw new Exception('获取session_key及openID时异常,微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$weChatResult);

            if ($loginFail){
                $this->processLoginError($weChatResult);
            }else{
                $grantToken = $this->grantToken($weChatResult);
                return $grantToken;
            }
        }

    }


    private function grantToken($weChatResult)
    {
        //拿到openid
        //数据库里面查询openid是否存在
        //如果存在，则不处理，如果不存在则新增一条user记录
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回到客户端去
        //key：令牌
        //value：weChatResult,uid,scope   //scope是用户权限
        $opendid = $weChatResult['openid'];
        $user = UserModel::getByOpenID($opendid);

        if ($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($opendid);
        }

        $cacheValue = $this->prepareCacheValue($weChatResult,$uid);
        $token = $this->saveToCache($cacheValue);
        return $token;

    }

    private function saveToCache($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire = config('setting.token_expire_in');

        $request = cache($key,$value,$expire);
        if (!$request){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;
    }


    private function prepareCacheValue($weChatResult,$uid)
    {
        $cacheValue = $weChatResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($weChatResult)
    {
        throw new WeChatException([
            'msg' => $weChatResult['errmsg'],
            'errorCode'=> $weChatResult['errcode']
        ]);

    }

}