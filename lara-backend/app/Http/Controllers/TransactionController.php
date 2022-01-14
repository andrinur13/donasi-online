<?php

namespace App\Http\Controllers;

use App\Models\CampaignModel;
use App\Models\TransactionModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionController extends Controller
{


    public function index()
    {
        $data = [
            'transactions' => TransactionModel::join('users', 'transactions.user_id', 'users.id')->
                                                join('campaigns', 'transactions.campaign_id', 'campaigns.id')->
                                                select('transactions.*', 'campaigns.name as campaign_name', 'users.name as users_name')->
                                                orderBy('created_at', 'desc')->
                                                get()
        ];

        return view('transactions.index', $data);
    }

    public function approve($id)
    {
        $transaction = TransactionModel::where('id', $id)->first();

        if($transaction == null) {
            return redirect('/dashboard/transactions')->with('error', 'Transaksi tidak ditemukan');
        } 

        // get campaigns
        $campaign = CampaignModel::where('id', $transaction->campaign_id)->first();
        try {
            $campaign->current_amount = $campaign->current_amount + $transaction->amount;
            $transaction->status = 'paid';
            
            $campaign->save();
            $transaction->save();
        } catch(Exception $e) {
            return redirect('/dashboard/transactions')->with('error', 'Transaksi tidak dapat diproses');
        }

        return redirect('/dashboard/transactions')->with('success', 'Transaksi berhasil diapprove');

    }

    //
    // api
    public function funding(Request $request) {
        $data = [
            'campaign_id' => $request->campaign_id,
            'user_id' => JWTAuth::user()->id,
            'amount' => $request->amount,
            'status' => 'unpaid',
        ];

        $rules = [
            'campaign_id' => 'required',
            'amount' => 'required',
        ];

        $validation = Validator::make($data, $rules);
        if($validation->fails()) {
            return response([
                'meta' => [
                    'message' => 'failed fund, error validation',
                    'code' => 422,
                    'status' => 'failed'
                ],
                'data' => $validation->errors()
            ], 422);
        }

        $create_transactions = TransactionModel::create($data);

        // return response($create_transactions);

        \Midtrans\Config::$serverKey = env("MIDTRANS_SK");
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $create_transactions->id,
                'gross_amount' => $request->amount,
            ),
            'customer_details' => array(
                'email' => JWTAuth::user()->email,
            ),
        );

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
        } catch(\Exception $e) {
            return response([
                'meta' => [
                    'message' => 'failed fund, error midtrans',
                    'code' => 500,
                    'status' => 'error'
                ],
                'data' => null
            ], 500);
        }

        $create_transactions->code = $paymentUrl;
        $create_transactions->save();
            
        return response([
            'meta' => [
                'message' => 'success create transactions',
                'code' => 200,
                'status' => 'success'
            ],
            'data' => $create_transactions
        ], 200);

    }

    public function myfunding(Request $request) {
        $id_user = JWTAuth::user()->id;


        $transaction = TransactionModel::where('user_id', $id_user)->
                        orderBy('created_at', 'desc')->
                        get();

        foreach($transaction as $item) {
            // get campaign name
            $campaign = CampaignModel::where('id', $item->campaign_id)->first();

            $item->campaign_name = $campaign->name;
        }

        $campaign = CampaignModel::where('id', 7)->first();



        return response([
            'meta' => [
                'message' => 'success get transactions',
                'code' => 200,
                'status' => 'success'
            ],
            'data' => $transaction
        ], 200);
    }


    public function callback(Request $request) {

        $order_id = $request->order_id;
        $status = $request->transaction_status;

        if($status == 'settlement') {
            $this->approve($order_id);
        }
    }
}
