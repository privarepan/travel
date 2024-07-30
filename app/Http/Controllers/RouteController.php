<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $routes = Route::latest()
            ->when($request->filled('q'),function (Builder $query)use($request){
                $query->where('city','like',"%$request->q%")
                    ->orWhere('title','like',"%$request->q%");
            })
            ->get();
        return $this->success($routes);
    }

    public function city()
    {
        $city = Route::select('city')->groupBy('city')->get();
        return $this->success($city);
    }
}
