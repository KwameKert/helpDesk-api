<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    //

    public function init(){
        $user = Auth::user();

        return response()->json(['user'=>$user], 200);
    }

    public function login(Request $request){

        if(Auth::attempt(['index_no'=>$request->indexNumber,'password'=>$request->password], true))
        {
            return response()->json(Auth::user(),200);
        }else{
            return response()->json(['error'=>'Could error in.'],401);
        }
    }

    public function register(Request $request){
     
        $user = User::where('email',$request->email)->first();
        if(isset($user->id)){
            return response()->json(['error'=>'Email already exists'],401);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = 2;
        $user->gender = $request->gender;
        $user->index_no = $request->indexNumber;
        $user->phone =   $request->phone;
        $user->status = 'Active';
        $user->api_key = Str::random(60);
        $user->password = bcrypt($request->password);
        $user->save();
        Auth::login($user);
        return response()->json($user, 200);
    }

      public function registerMobile(Request $request){
     
        $user = User::where('email',$request->email)->first();
        if(isset($user->id)){
            return response()->json(['error'=>'Email already exists'],401);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = 2;
        $user->gender = $request->gender;
        $user->index_no = $request->indexNumber;
        $user->phone =   $request->phone;
        $user->status = 'Active';
        $user->api_key = Str::random(60);
        $user->password = bcrypt($request->password);
        $user->save();
        Auth::login($user);
        return response()->json(['status'=>'true','message'=>'Registered succefully','api_key'=>$user->api_key]);
    }

    public function logout(){
        Auth::logout();
    }




    public function loginMobile(Request $request){
         if(Auth::attempt(['index_no'=>$request->indexNumber,'password'=>$request->password], true))
        {
            return response()->json(['status'=>'true','user'=>Auth::user()],200);
        }else{
            return response()->json(['error'=>'Could error in.'],401);
        }
    }
}
