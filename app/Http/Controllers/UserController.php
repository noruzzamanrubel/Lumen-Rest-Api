<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(){
        $users= User::all();
        return response()->json($users);
    }

    public function create(Request $request){

        //data validation
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

        //data insert
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
            'message' => 'Your Data Is Insert Successfully'
        ]);
    }

    public function authenticate(Request $request){
        //data validation
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);
    
        } catch (ValidationException $c) {
            return response()->json([
                'success'=> false,
                'message'=> $e->getMessage(),
            ]);
        }

        $token= app('auth')->attempt($request->only('email', 'password'));
        
        if($token){
            return response()->json([
                'success' => true,
                'message'=> 'User Authenticate',
                'token'=> $token,
            ]);
        }
        return response()->json([
            'success' => false,
            'message'=> 'Invalid User',
        ], 400);
    }

    
}
