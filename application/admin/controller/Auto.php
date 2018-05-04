<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2017/4/23
 * Time: 14:40
 */

namespace app\admin\controller;


use think\Controller;

class Auto extends Controller
{
    public function index(){
       return $this->fetch();

    }
}