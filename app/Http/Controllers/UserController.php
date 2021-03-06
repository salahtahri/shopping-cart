<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getSignup()
    {
    	return view('user.signup');
    }
    public function postSignup(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'email|required|unique:users',
    		'password' => 'required|min:4'	

    	]);

    	$user = New User([
    		'email' => $request['email'],
    		'password' => bcrypt($request['password'])
    	]);

    	$user->save();

    	return redirect()->route('product.index');
    }
    public function getSignin()
    {
    	return view('user.signin');
    }
    public function postSignin(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'email|required',
    		'password' => 'required|min:4'	

    	]);
    	if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
    		return redirect()->route('user.profile');
    	}
    	return redirect()->back();
    }
	public function getProfile()
    {
        $orders = Auth::user()->orders;
        $orders->transform(function($order, $key ){
            $order->cart = unserialize($order->cart);
            return $order;
        });
    	return view('user.profile', ['orders' => $orders]);
    }
    public function getLogout()
    {
    	Auth::logout();
    	return redirect()->back();
    }

}
