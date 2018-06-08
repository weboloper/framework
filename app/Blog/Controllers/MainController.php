<?php

namespace App\Blog\Controllers;

class MainController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        // $this->middleware('auth');
    }

    /**
     * GET | This shows the final landing page, in which it is the newsfeed.
     *
     * @return mixed
     */
    public function index()
    {

        // die(var_dump(22));
        return view('newsfeed.showLandingPage');
    }
}