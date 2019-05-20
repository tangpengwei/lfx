<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/20
 * Time: 下午7:09
 */
namespace app\admin\controller;

use app\admin\model\admin;
use think\Controller;

class Login extends Controller
{
    /**
     * 退出登录
     */
    public function out()
    {
        session('adminLoginInfo',null);
        $this->redirect('admin/Login/in');
    }



    /**
     * 登录操作
     */
    public function in()
    {
        $re = $this->request;
        if ($re->isPost()){
            //接受数据
            $data = $re->only(['mobile','password']);
            //验证数据
            $rule = [
                'mobile' => 'require|mobile',
                'password' =>'require|length:6,12'
            ];
            $msg= [
                'mobile.require' => '手机号为必填项',
                'mobile.mobile' => '手机号格式错误',
                'password.require' => '密码为必填项',
                'password.length' => '密码长度不对'
            ];
            $info = $this->validate($data,$rule,$msg);
            if ($info!== true){
                return $this->error($info);
            }
            $admin = admin::where('mobile',$data['mobile'])->find();
            if (!$admin){
                $this->error('您输入的手机号或者密码有误');
            }
            if (password_verify($data['password'],$admin->password)){
                //登录成功
                session('adminLoginInfo',$admin);
                $this->success('成功',url('admin/Index/index'));
            }else{
                $this->error('您输入的手机号或则密码有误');
            }





        }
        if ($re->isGet()){
            return $this->fetch();
        }
    }
}


















