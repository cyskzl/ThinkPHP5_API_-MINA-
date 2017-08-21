<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/15
 * Time: 2:09
 */

function adate($data)
{

    global $newdata;

    $newdata = date('Y-m-d',strtotime("$data +1 day"));

    $w = date('w',strtotime($newdata));

//    while ($w == '0' || $w == '6'){
//
//        $newdata = date('Y-m-d',strtotime("$newdata +1 day"));
//
//        $w = date('w',strtotime($newdata));
//
//    }

    if ($w == '0' || $w == '6'){
        adate($newdata);
    }

    return $newdata;
}

echo adate('2017-8-19');
