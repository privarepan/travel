<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function upload(Request $request)
    {
        $rules = [
            'file' => 'required|image',
        ];
        $request->validate($rules);

        $path = $request->file('file')->store('images','public');
        return $this->success(['path' => $path]);
    }
}
