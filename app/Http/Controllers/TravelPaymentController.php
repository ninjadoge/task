<?php

namespace App\Http\Controllers;

use App\Models\TravelPayment;
use App\Models\PaymentApproval;
use Auth;

use Illuminate\Http\Request;

class TravelPaymentController extends Controller
{
    public function index(){
        return TravelPayment::all();
    }

    public function save(Request $request){
        $user = Auth::user();
        $request->validate([
            'total_amount' => 'required',
        ]);
        
        $payment = TravelPayment::create(
            [
                'total_amount' => $request->total_amount,
                'user_id' => $user->id,
            ]
        );

        $paymentApproval = PaymentApproval::create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'payment_type' => 'travel',
            'status' => 'DISAPPROVED'
        ]);

        return $payment;
    }

    public function show($id){
        $payment = TravelPayment::find($id);
        return $payment;
    }

    public function update(Request $request, $id){
        $payment = TravelPayment::find($id);
        $payment->update($request->all());

        return $payment;

    }

    public function destroy($id){
        return TravelPayment::destroy($id) ? "Travel payment obrisan" : "Travel payment nije obrisan";
    }
}
