<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('hello/:name', 'index/hello');
//用户登录的路由
Route::rule('login','admin/Login/in')->method('GET,POST');
//后台首页
Route::get('admin$','admin/Index/index');
//console页
Route::get('admin-console','admin/Index/console');
Route::rule('admin-add-category','admin/Index/addCategory')->method('GET，POST');
Route::get('admin-list-category','admin/Index/categoryList');
Route::get('admin-tree-category','admin/Index/categoryTree');
//添加文章
Route::rule('admin-article-add','admin/Article/add')->method('GET,POST');
//ajax获取文章分类
Route::post('admin-article-category','admin/Article/ajaxCategory');
Route::post('admin-article-change-status','admin/Article/changeStatus');
//删除一条记录
Route::post('admin-article-del','admin/Article/del');
//修改一条记录
Route::rule('admin-article-update','admin/Article/update')->method('GET,POST');
