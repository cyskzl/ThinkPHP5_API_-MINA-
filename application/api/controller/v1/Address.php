<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/20
 * Time: 15:00
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;


class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];


    public function createOrUpdateAddress()
    {
        $validata = new AddressNew();
        $validata->goCheck();
        //根据Token来获取uid
        //根据uid来查用户数据，判断用户是否存在，如果不存在就抛出异常
        //如果用户存在，获取从客户端提交过来的用户信息
        //根据用户地址信息是否存在，从而判断是添加地址还是更新地址

        $uid = TokenService::getCurrentUid();

        $user= UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }

        $dataArray = $validata->getDataByRule(input('post.'));

        $userAddress = $user->address;

        if (!$userAddress){
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }

        return json(new SuccessMessage(),201);

    }

}