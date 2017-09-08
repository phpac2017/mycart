<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Purchase;
use Illuminate\Support\Facades\Input;
use \Cart as Cart;



class PagesController extends Controller
{
    /**
     * Display Order Form
     *
     * @return \Illuminate\View\View
     */
    public function getOrder()
    {
        return view('order');
    }

    /**
     * Process Order
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postOrder(Request $request)
    {
        $validator = \Validator::make(Input::all(), [
            'first_name' => 'required|string|min:2|max:32',
            'last_name' => 'required|string|min:2|max:32',
            'email' => 'required|email',
            'product' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Checking is product valid
        
        $product = $request->input('product');
        //echo $request->input('amount');
        $get_amount = $request->input('amount');
        $amount = intval(str_replace(',', '', $get_amount));
        //dd($amount);
        /*$product = $request->input('product');
        switch ($product) {
            case 'book':
                $amount = 1000;
                break;
            case 'game':
                $amount = 2000;
                break;
            case 'movie':
                $amount = 1500;
                break;
            default:
                return redirect()->route('order')
                    ->withErrors('Product not valid!')
                    ->withInput();
        }*/

        $token = $request->input('stripeToken');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $emailCheck = User::where('email', $email)->value('email');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        //$getCustomer = \Stripe\Customer::all();
       // dd($getCustomer);
        // If the email doesn't exist in the database create new customer and user record
        if (!isset($emailCheck)) {
            // Create a new Stripe customer
            try {
                $customer = \Stripe\Customer::create([
                'source' => $token,
                'email' => $email,
                'metadata' => [
                    "First Name" => $first_name,
                    "Last Name" => $last_name
                ]
                ]);
            } catch (\Stripe\Error\Card $e) {
                return redirect()->route('order')
                    ->withErrors($e->getMessage())
                    ->withInput();
            }

            $customerID = $customer->id;

            //dd($customerID);

            // Create a new user in the database with Stripe
            $user = User::create([
            'name' => $first_name,
            'password' => bcrypt('1234'),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'stripe_customer_id' => $customerID,
            ]);
        } else {
            $customerID = User::where('email', $email)->value('stripe_customer_id');
            $user = User::where('email', $email)->first();
        }

        // Charging the Customer with the selected amount
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $customerID,
                'metadata' => [
                    'product_name' => $product
                ]
                ]);
        } catch (\Stripe\Error\Card $e) {
            return redirect()->route('order')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        // Create purchase record in the database
        Purchase::create([
            'user_id' => $user->id,
            'product' => $product,
            'amount' => $amount,
            'stripe_transaction_id' => $charge->id,
        ]);
        Cart::destroy();
        return redirect()->route('order')
            ->with('successful', 'Your purchase was successful!');
    }
}