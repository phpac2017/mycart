<?php

namespace App\Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
Use Mail;
use Auth;
use Session;

class CustomersController extends Controller
{  

    public function postLogin(Request $request)
    {       
        if(User::where('email', '=', Input::get('email'))->exists())
        {
            if(auth('customer_admin')->attempt(array('email' => $request->input('email'), 'password' => $request->input('password'))))
            {
                $name = auth()->guard('customer_admin')->user()->name;
                $customer_id = auth()->guard('customer_admin')->user()->id;

                $carts_tbl = DB::table('cart_session')->where(['customer_id' => $customer_id])->first();

                if(isset($carts_tbl))
                {
                        $cart_data = json_decode($carts_tbl->data);
                        Session::put('cart_session_test', $cart_data);
                        $show_session = Session::get('cart_session_test');

                        echo "<PRE>";
                        print_r($show_session);
                        die();
                }

                return redirect()->route('customer.dashboard');
            }else{
                return back()->with('error', 'Invalid Credentials');
            }
        }else{
            return back()->with('error', 'No Matches Found in our Records');
        }
    }

    public function logout(Request $request)
    {
        $cart_data = Session::get('cart_session_test');
        $customer_id = auth()->guard('customer_admin')->user()->id;

        $cart_data = Cart::content();

        $cart = [

            'data' => json_encode($cart_data),

        ];

        $carts_tbl = DB::table('cart_session')->where(['customer_id' => $customer_id])->first();

        if(isset($carts_tbl)){

            DB::table('cart_session')->where(['customer_id' => $customer_id])->update($cart);

        } else{

            $cart['customer_id'] =   $customer_id;
            DB::table('cart_session')->insert($cart);

        }

        Auth::logout();

        $request->session()->forget('customer_admin');
        return redirect()->route('login')->with('success', 'You`ve been successfully logged out');
    }
}