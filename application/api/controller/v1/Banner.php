<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/12
 * Time: 19:31
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use app\lib\exception\ParamterException;

;
class Banner
{
    /**
     * 获取指定id的banner信息
     * @http GET
     * @id  bannerde id号
     * @url /banner/:id
     */
    public function getBanner($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);


        if (empty($banner)){
          throw new BannerMissException();
        }

        $c = config('setting.img_prefix');

        return $banner;
    }

}