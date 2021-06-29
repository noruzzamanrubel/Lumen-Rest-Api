<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        $users= User::all();
        return response()->json($users);
    }

    public function create(Request $request){
        
        try {
            $this->validate($request, [
                'full_name' => 'required',
                'username' => 'required | min:6',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);
    
        } catch (ValidationException $c) {
           return response()->json([
               'success'=> false,
               'message'=> $e->getMessage(),
           ]);
        }

        User::insert([
            'full_name'=>$request->full_name,
            'username'=>strtolower($request->username),
            'email'=>strtolower($request->email),
            'password'=>app('hash')->make('password'),
            'created_at'=>carbon::now(),
            'updated_at'=>carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Your Data is Insert Successfully'
        ]);
    }

    
}
