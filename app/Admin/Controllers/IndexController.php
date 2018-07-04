<?php

namespace App\Admin\Controllers;

class IndexController extends Controller
{
     

    public function home()
    {
        return view('admin.home');
    }

    public function filemanager()
    {
        return view('admin.media');
    }


}
