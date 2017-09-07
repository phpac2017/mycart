@extends('layouts.pay')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h1 class="text-primary" style="text-align: center;">Create Order</h1>
    </div>
</div>

@if (session()->has('successful'))
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
          <div class="alert alert-success"><strong>{{session()->get('successful')}}</strong></div>
      </div>
    </div>
@endif

<div class="row">
  <div class="col-md-6 col-md-offset-3">
    {!! Form::open(['url' => route('order-post'), 'data-parsley-validate', 'id' => 'payment-form']) !!}
      <?php
        if(Auth::guest() || sizeof(Cart::content()) === 0){
          $username = '';
          $email = ''; 
        }else{
          $username = Auth::user()->name;
          $email = Auth::user()->email;
        }

      ?>
      <div class="form-group" id="first-name-group">
          {!! Form::label('firstName', 'First Name:') !!}
          {!! Form::text('first_name', $username, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'First name is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#first-name-group'
              ]) !!}
      </div>

      <div class="form-group" id="last-name-group">
          {!! Form::label('lastName', 'Last Name:') !!}
          {!! Form::text('last_name', null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Last name is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#last-name-group'
              ]) !!}
      </div>

      <div class="form-group" id="email-group">
          {!! Form::label('email', 'Email address:') !!}
          {!! Form::email('email', $email, [
              'class' => 'form-control',
              'placeholder'                   => 'email@example.com',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Email name is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-class-handler'    => '#email-group'
              ]) !!}
      </div>
      <?php 
        if(sizeof(Cart::content()) > 0){
          foreach(Cart::content() as $item){
            $items[] = $item->name;
          }
          $cart_total = "$".Cart::total();
          $products = implode(',', $items);
        }else{
          $items = array();
          $cart_total = '';
          $products = '';
        }
        
      ?>
      <div class="form-group" id="product-group">
          {!! Form::label('product', 'Products:') !!}          
          {{ Form::textarea('product', $products, ['class' => 'form-control', 'readonly' => 'true']) }}
         
      </div>

      <div class="form-group" id="product-group">
          {!! Form::label('amount', 'Amount to be paid:') !!}
          {{ Form::hidden('amount', Cart::total(), array('id' => 'amount')) }}
          {!! Form::text('disp_amount', $cart_total, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'readonly' => 'true'
              ]) !!}
      </div>

      <div class="form-group" id="cc-group">
          {!! Form::label(null, 'Credit card number:') !!}
          {!! Form::text(null, null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-stripe'                   => 'number',
              'data-parsley-type'             => 'number',
              'maxlength'                     => '16',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-class-handler'    => '#cc-group'
              ]) !!}
      </div>

      <div class="form-group" id="ccv-group">
          {!! Form::label(null, 'Card Validation Code (3 or 4 digit number):') !!}
          {!! Form::text(null, null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-stripe'                   => 'cvc',
              'data-parsley-type'             => 'number',
              'data-parsley-trigger'          => 'change focusout',
              'maxlength'                     => '4',
              'data-parsley-class-handler'    => '#ccv-group'
              ]) !!}
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group" id="exp-m-group">
              {!! Form::label(null, 'Ex. Month') !!}
              {!! Form::selectMonth(null, null, [
                  'class'                 => 'form-control',
                  'required'              => 'required',
                  'data-stripe'           => 'exp-month'
              ], '%m') !!}
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group" id="exp-y-group">
              {!! Form::label(null, 'Ex. Year') !!}
              {!! Form::selectYear(null, date('Y'), date('Y') + 10, null, [
                  'class'             => 'form-control',
                  'required'          => 'required',
                  'data-stripe'       => 'exp-year'
                  ]) !!}
          </div>
        </div>
      </div>

        <div class="form-group">
            {!! Form::submit('Place order!', ['class' => 'btn btn-primary btn-order', 'id' => 'submitBtn', 'style' => 'margin-bottom: 10px;']) !!}
        </div>

        <div class="row">
          <div class="col-md-12">
              <span class="payment-errors" style="color: red;margin-top:10px;"></span>
          </div>
        </div>

    {!! Form::close() !!}

  </div>

  {{-- Show $request errors after back-end validation --}}
  <div class="col-md-6 col-md-offset-3">
      @if ( count( $errors ) > 0 )
          <div class="alert alert-danger fade in">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4>The following errors occurred</h4>
              <ul>
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
  </div>

</div>

@endsection