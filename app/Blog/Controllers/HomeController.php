<?php

namespace App\Blog\Controllers;

class HomeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        $this->middleware('auth', [
            'only' => [
                'logged',
            ],
        ]);

    }

    /**
     * GET | This shows the final landing page, in which it is the newsfeed.
     *
     * @return mixed
     */
    public function index()
    {

        return view('welcome');
    }

    public function logged()
    {

        return view('logged');
    }
}