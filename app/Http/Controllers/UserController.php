<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PaymentApproval;
use Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'type' => 'in:APPROVER,NONAPPROVER',
            'password' => 'required|string|confirmed',
        ]);
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'type' => $data['type'] ,
            'password' => bcrypt($data['password']),
        ]);

        $apiToken = $user->createToken('TokenSeed')->plainTextToken;

        $response = [
            'user' =>  $user,
            'token' =>  $apiToken,
        ];

        return response($response, 201); 
    }
    
    public function destroyToken(Request $request){
        $user = Auth::user();
        $user->tokens()->delete();
        
        return ['message' => 'Token više nije aktivan'];

    }
    
    public function test(Request $request){
        $approval = new PaymentApproval;
        $approval->user_id = 2;
        $approval->payment_id = 3;
        $approval->payment_type = 'travel';
        $approval->status = 'DISAPPROVED';
        $approval->save();
        return $approval;
    }
}
