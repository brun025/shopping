<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\User;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Firebase\Auth as FirebaseAuth;
use CodeShopping\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    public function store(CustomerRequest $request)
    {
        $data = $request->all();
        $token = $request->token;
        $data['phone_number'] = $this->getPhoneNumber($token);
        $data['photo'] = $data['photo'] ?? null;
        $user = User::createCustomer($data);
        return[
            'token' => \Auth::guard('api')->login($user)
        ];
    }

    private function getPhoneNumber($token){
        $firebaseAuth = app(FirebaseAuth::class);
        return $firebaseAuth->phoneNumber($token);
    }
}
