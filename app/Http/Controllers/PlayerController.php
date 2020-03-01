<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lista = UserFollow::whereclub_id(Auth::user()->club_id)
            ->orderby('id','asc')
            ->get();


        return view('club.players.list',compact('lista'));
    }

    public function search(Request $request){
        $busca = $request['busca'];

        $lista = Blind::whereclub_id(Auth::user()->club_id)
            ->where(function ($query) use ($busca) {
                $query->where('title', 'like', '%'.$busca.'%')
                ;
            })
            ->orderby('id','desc')
            ->get();
        return view('club.blind.list',compact('lista','busca'));
    }


}
