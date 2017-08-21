<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ThemeException;
use think\Controller;
use think\Request;

class Theme extends Controller
{
    /**
     * @url /theme?ids=id1,id2,id3,.....
     * @return 一组theme模型
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);

        $result = ThemeModel::with(['topicImg','headImg'])->select($ids);

        if (empty($result)){
            throw new ThemeException();
        }

        return $result;
    }

    /**
     * @url /theme/:id
     */
    public function getComplexOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProduct($id);

        if (empty($theme)){
            throw new ThemeException();
        }

        return $theme;

    }

}
