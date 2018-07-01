<?php

namespace App\Admin\Controllers;

class RolesController extends Controller
{
    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function index()
    {
        return view('{path.to.resources.view}.index');
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
        if (request()->isPost() && request()->isAjax()) {
            // ...
        }
    }

}
