<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/27
 * Time: 下午8:58
 */
namespace app\admin\controller;

use app\admin\model\category;
use think\Controller;

class Image extends Controller
{
    public function add()
    {

        if ($this->request->isGet()){
            $list = category::where('pid',0)->select();
            $this->assign('list',$list);
            return $this->fetch();
        }
        if ($this->request->isPost()){
            $thumbs = $this->request->param('xxxx');
            $id = $this->request->param('category');
            $data = [];
            foreach ($thumbs as $v){
                $data[]= ['category_id'=>$id,'location'=>$v];
            }
            $image = new \app\admin\model\image();
            if ($image->saveAll($data)){
                $this->success('成功');
            }else{
                $this->error('失败');
            }

        }

    }

    public function lists()
    {
        
    }


    public function getImageCategory()
    {
        $id = $this->request->param('id');
        $list = category::where('type',2)->where('pid',$id)->select();
        return json($list);
    }
    
    
    
    
}