<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentApproval;
use App\Models\Payment;
use App\Models\TravelPayment;
use Auth;

class ReportController extends Controller
{
    public function approve($id){

        $user = Auth::user();
        if($user->type != 'APPROVER'){
            return "User nije APPROVER";
        }   
        $approval = PaymentApproval::find($id);
        $approval->update(['status' => 'APPROVED']);

        return $approval;

    }
    
    public function report(){
        $approvedPayments = PaymentApproval::where('status','APPROVED')->get();
        $approvedPaymentsIds = $approvedPayments->pluck('payment_id');

        $travelPaymentsApprovals = [];
        $regularPaymentsApprovals = [];

        foreach($approvedPayments as $approval){
            if($approval->payment_type == 'regular'){
                $regularPaymentsApprovals [$approval->payment_id]= $approval;
            }
            elseif($approval->payment_type == 'travel'){
                $travelPaymentsApprovals [$approval->payment_id]= $approval;
            }
        }
        $travelPaymentsIds = array_keys($travelPaymentsApprovals);
        $regularPaymentsIds = array_keys($regularPaymentsApprovals);
        $travelPayments = TravelPayment::whereIn('id',$travelPaymentsIds)->get();
        $regularPayments = Payment::whereIn('id',$regularPaymentsIds)->get();
        
        $regularUserIds = $regularPayments->pluck('user_id')->toArray();
        $travelUserIds = $travelPayments->pluck('user_id')->toArray();
        $userIds = array_merge($regularUserIds,$travelUserIds);

        foreach($userIds as $id){
            $totalAmounts[$id] = [
                'regular' => 0,
                'travel' => 0,
                'total' => 0,
            ];
        }
        foreach($regularPayments as $payment){
            $totalAmounts[$payment->user_id]['regular'] += $payment->total_amount;
        }
        foreach($travelPayments as $payment){
            $totalAmounts[$payment->user_id]['travel'] += $payment->total_amount;
        }
        foreach($totalAmounts as $key => $amount){
            $totalAmounts[$key]['total'] = number_format($amount['regular'] + $amount['travel'],2);
        }
        
        return $totalAmounts;

    }
}
