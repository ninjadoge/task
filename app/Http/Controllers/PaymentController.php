<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentApproval;
use Auth;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        return Payment::all();
    }

    public function save(Request $request){
        $user = Auth::user();
        $request->validate([
            'total_amount' => 'required',
        ]);

        $payment = Payment::create(
            [
                'total_amount' => $request->total_amount,
                'user_id' => $user->id,
            ]
        );
        $paymentApproval = PaymentApproval::create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'payment_type' => 'regular',
            'status' => 'DISAPPROVED'
        ]);
        return $payment;
    }

    public function show($id){
        $payment = Payment::find($id);
        return $payment;
    }

    public function update(Request $request, $id){
        $payment = Payment::find($id);
        $payment->update($request->all());

        return $payment;

    }

    public function destroy($id){
        return Payment::destroy($id) ? "Payment obrisan" : "Payment nije obrisan";
    }

    
}
