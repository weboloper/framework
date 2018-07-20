<?php

namespace App\Admin\Controllers;

class IndexController extends Controller
{
     

    public function home()
    {
        return view('home');
    }

    public function filemanager()
    {
        return view('media');
    }


}
