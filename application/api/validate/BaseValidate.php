<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/12
 * Time: 23:01
 */

namespace app\api\validate;


use app\lib\exception\ParamterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        // 获取http传入的参数
        // 对这些参数做检验
        $request = Request::instance();
        $params = $request->param();

        if (count($params) == count($params,1)){

            $result = $this->batch()->check($params);
        }else{

            foreach ($params as $param){
                $result = $this->batch()->check($param);
            }
        }


        if (!$result) {
            $e = new ParamterException([
                'msg' => $this->error,
            ]);
            throw $e;
        } else {
            return true;
        }
    }

    protected function isPositiveInteger($value, $rult = "", $date = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }

    }

    protected function isNotEmpty($value, $rult = "", $date = '', $field = '')
    {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }

    }

    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id',$arrays) | array_key_exists('uid',$arrays)){
            throw new ParamterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid',
            ]);
        }

        $newArray = [];

        foreach ($this->rule as $key=>$value){
            $newArray[$key] = $arrays[$key];
        }

        return $newArray;

    }

    public function isMobile($vaule)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$vaule);
        if ($result){
            return true;
        }else{
            return false;
        }
    }

}