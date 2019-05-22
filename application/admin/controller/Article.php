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

    /** 添加文章
     * @return mixed
     * @throws \think\Exception\DbException
     */
    public function add()
    {

        $re = $this->request;
        //判断是不是GET请求
        if ($re->isPost()){
            //获取我们所取药的值
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
           //数据验证不通过的话 报错
           if ($check !== true){
               $this->error($check);
           }
           //在$data数据中在添加一条aid信息
           $data['aid'] = session('adminLoginInfo')->id;
           //入库
            if (\app\admin\model\article::create($data)){
                $this->success('添加成功',url('admin/Article/lists'));
            }else{
                $this->error('添加失败');
            }
        }
        //判断是否是GET请求
        if ($re->isGet()){
            //获取分类信息 并自定义标签
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
        //获取ajax传输过来的值
        $pid = $this->request->param('id',0);
        //查询出来符合条件的所有数据
        $data = category::where('pid',$pid)->select();
//        print_r($data);
        //返回一个json数据
        return json($data);
    }
    /**
     * 文章列表
     *
     */
    public function lists()
    {
        //通过一对多关联查询出来符合条件的所有数据 并且排列顺序 并自定义标签
        $list = \app\admin\model\article::with('category')->order('create_time DESC')->paginate(2);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 改变状态
     */
    public function changeStatus()
    {
        //接收前端通过ajax发过来的id值
        $id = $this->request->param('id');
        //判断id的值是否有效 如果为null则报错
        if (empty($id)){
            $this->error('非法操作');
        }
        //获取article表中的一个符合的数据
        $obj = \app\admin\model\article::get($id);
        //判断$obj是否为null 如果是 报错
        if (empty($obj)){
            return $this->error('非法操作');
        }
        //获取$obj对象中的status值  并给它赋值
        $obj->status = abs($obj->status-1);
        //更新数据表中的数据
        if ($obj->save()){
            return $this->success('成功','',$obj->status);
        }else{
            return $this->error('失败');
        }
    }

    public function del()
    {
        $id = $this->request->param('id');
        $data = \app\admin\model\article::get($id);
//        if (1){
//            $this->success('成功');
//        }
        if ($data->delete()){
            $this->success('成功');
        }else{
            $this->error('失败');
        }

    }

    public function update()
    {
        if ($this->request->isGet()) {
            $id = $this->request->param('id');
            $list = \app\admin\model\article::get($id)->toArray();
//        print_r($list);
            $this->assign('list', $list);

            return $this->fetch();
        }
        if ($this->request->isPost()){
            $id = $this->request->param('id');
            $data = $this->request->only(['title','content','author']);
            //验证
            $rule = [
                'title' => 'require|length:1,50',
                'author' =>'length:2,10',
                'content' => 'require|length:10,65535',
            ];
            $msg=[
                'title.require' => '标题为必填项',
                'title.length:1,50' => '标题长度过长或过短',
                'author.length' => '署名长度应在2-10之间',
                'content.require' => '文章内容为必填项',
                'content.length' =>'文章内容长度过长或者过短'
            ];
            $check = $this->validate($data,$rule,$msg);
            if ($check!== true){
                $this->error('错误');
            }
          $a = \app\admin\model\article::get($id);
            if ($a->save($data)){
                $this->success('成功',url('admin/Article/lists'));
            }else{
                $this->error('失败');
            }
        }
}


}


















