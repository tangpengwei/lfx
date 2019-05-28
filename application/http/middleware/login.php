<?php

namespace app\http\middleware;

use think\Controller;

class login extends Controller
{
    public function handle($request, \Closure $next)
    {
        if (!session('adminLoginInfo')){
            return $this->redirect('admin/Login/in');
        }
        return $next($request);
    }
}
