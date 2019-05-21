<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/20
 * Time: 下午9:08
 */
namespace app\admin\controller;
use app\admin\model\category;
use think\Controller;

class Article extends Controller
{
    public function add()
    {
        $re = $this->request;
        if ($re->isPost()){
            $data = $re->only(['title','category_id','author','content','status']);
            //验证
            $rule = [
                'title' => 'require|length:1,50',
                'category_id' => 'require|min:1',
                'author' =>'length:2,10',
                'content' => 'require|length:10,65535',
                'status' => 'in:0,1'
            ];
            $msg=[
                'title.require' => '标题为必填项',
                'title.length:1,50' => '标题长度过长或过短',
                'category_id.require' => '请选择正确的分类信息',
                'category_id.min'=>'请选择正确的分类信息',
                'author.length' => '署名长度应在2-10之间',
                'content.require' => '文章内容为必填项',
                'content.length' =>'文章内容长度过长或者过短',
                'status.in' => '文章状态有误'
            ];
           $check = $this->validate($data,$rule,$msg);
           if ($check !== true){
               $this->error($check);
           }
           $data['aid'] = session('adminLoginInfo')->id;
           //入库
            if (\app\admin\model\article::create($data)){
                $this->success('添加成功',url('admin/Article/lists'));
            }else{
                $this->error('添加失败');
            }
        }
        if ($re->isGet()){
            //获取分类信息
            $all = category::where('pid',0)->all();
            $this->assign('all',$all);
            return $this->fetch();
        }
    }

    /**
     *  使用ajax获取文章新闻的分类
     */
    public function ajaxCategory()
    {
        $pid = $this->request->param('id',0);
        $data = category::where('pid',$pid)->select();
//        print_r($data);
        return json($data);
    }
    /**
     * 文章列表
     *
     */
    public function lists()
    {
        $list = \app\admin\model\article::with('category')->order('create_time DESC')->paginate(2);
        $this->assign('list',$list);
        return $this->fetch();
    }

}


















