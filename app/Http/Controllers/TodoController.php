<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

use App\Http\Requests;

class TodoController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        Todo::create($request->all());
        return redirect('/');
    }

    public function update(Todo $todo, Request $request) {
        $todo->update($request->all());
        return $todo;
    }
    
}
