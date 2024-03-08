<?php

namespace App\Http\Controllers;

use Hash;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class CustomAuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function registration(){
        return view('auth.registration');
    }
    public function registerUser(RegisterRequest $request){
        // $request->validate([
        //     'name'=>'required',
        //     'email'=>'required|email|unique:users',
        //     'password'=>'required|min:5|max:12'
        // ]);
        // $user = User::create([
        //     'name'=>$request->name,
        //     'email'=>$request->email,
        //     'password'=>Hash::make($request->password)
        // ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $reg = $user->save();
        if($reg){
            return back()->with('success','u have registered successfully');

        }else{
            return back()->with('fail','something wrong');
        }

    }
    public function loginUser(LoginRequest $request){

        $user = User::where('email','=',$request->email)->first();
        if($user){
            if(Hash::check($request->password,$user->password)){
                $request->session()->put('loginId',$user->id);
                return redirect('dashboard');
            }else{
                return back()->with('fail','password isnt correct');
            }
        }else{
            return back()->with('fail','this email isnt registered');
        }
    }
    public function dashboard(){
        // $data = array();
        // if(Session::has('loginId')){
        //     $users = User::where('id','=',Session::get('loginId'))->first();
        // }
        // return view('dashboard',compact('users'));
        if(Session::has('loginId')){
        $users =User::all();
        return view('dashboard',compact('users'));
        }

    }
    public function logout(){
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('login');
        }
    }
}
