<?php

namespace App\Admin\Controllers;

class PostsController extends Controller
{   

    /**
     * Initiate grid
     */
    protected static function setGrid()
    {
        parent::$grid = [
            'grid' =>[
                'title'    => [
                    'title'  => 'Title',
                    'order'  => true,
                    'filter' => ['type' => 'input', 'sanitize' => 'string', 'style' => ''],
                ],
                'type' => [
                    'title'  => 'Type',
                    'order'  => true,
                    'filter' => ['type' => 'input', 'sanitize' => 'string', 'style' => ''],
                ] ,
                'user_id' => [
                    'title'  => 'User',
                    'order'  => true,
                    'orderKey'  => 'u.user_id',
                    'filter' => ['type' => 'input', 'sanitize' => 'string', 'style' => ''],
                    'filterKey'  => 'u.user_id',
                ] ,
                'status' => [
                    'title'  => 'Status',
                    'order'  => true,
                    'orderKey'  => 'a.status',
                    'filter' => ['type' => 'input', 'sanitize' => 'string', 'style' => ''],
                    'filterKey'  => 'a.status',
                ] ,

                'null'    => ['title' => 'Actions']
            ],
            'query' => [
                'columns' => [
                    'a.id',
                    'a.title',
                    'a.user_id',
                    'a.type',
                    'a.status',
                 ],
                'joins' => [
                    [
                        'type' => 'join',
                        'model' => 'Users',
                        'on' => 'a.user_id = u.id',
                        'alias' => 'u'
                    ]
                ],
                // 'where' => 'a.type != "pages" AND a.deleted = 0'
                'where' => 'a.type != "pages"'
            ]
        ];
    }

    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function home()
    {
        flash()->session()->success(
                'Welcome to Admin section.'
        );
        return view('admin.home');
    }

    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function index()
    {
        if (empty(parent::$grid)) {
            self::setGrid();
        }
        $this->renderGrid('Posts');

        return view('admin.posts.index')->withGrid(  parent::$grid );
    }

    /**
     * To store a new record
     *
     * @return void
     */
    public function store()
    {
        if (request()->isPost()) {
            // do some stuff ...
        }
    }

    /**
     * To show an output based on the requested ID
     *
     * @param $id
     *
     * @return mixed
     */
    public function show($id)
    {
        return view('{path.to.resources.view}.show')
            ->with('id', $id)
            ->batch([
                'var1' => true,
                'var2' => 'this is another value for $var2',
            ]);
    }

    /**
     * To update a record based on the requested ID
     *
     * @param $id
     *
     * @return void
     */
    public function update($id)
    {
        # process the post request
        if (request()->isPost()) {
            // ...
        }
    }

    /**
     * To delete a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function delete($id)
    {
        # process the request which it must be post and ajax request
        if (request()->isPost()  && request()->isAjax()) {
            // ...
        }
    }

}
