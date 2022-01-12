<?php

namespace App\Http\Controllers;

use App\Models\CampaignModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->query('search_campaign')) {
            $search_key = $request->query('search_campaign');
            $data = [
                'campaign' => CampaignModel::where('campaigns.name', 'LIKE', '%' . $search_key . '%')->join('users', 'users.id', 'campaigns.id')->select('campaigns.*', 'users.name as pic')->get()
            ];
        } else {
            $data = [
                'campaign' => CampaignModel::join('users', 'users.id', 'campaigns.id')->select('campaigns.*', 'users.name as pic')->get()
            ];
        }
        

        return view('campaign.index', $data);
    }

    public function create() {
        return view('campaign.create');
    }

    public function store(Request $request) {
        $data = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount
        ];

        $rules = [
            'name' => 'required',
            'short_description' => 'required',
            'goal_amount' => 'required'
        ];

        $validation = Validator::make($data, $rules);
        if($validation->fails()) {
            return redirect('/dashboard/campaign')->with('error', 'Cek kelengkapan data Anda');
        }

        $slug = str_replace(' ', '-', $request->name);
        $slug = strtolower($slug);
        $slug = $slug . '-' . time();

        $data['slug'] = $slug;

        $create_campaign = CampaignModel::create($data);

        if($create_campaign) {
            return redirect('/dashboard/campaign')->with('success', 'Sukses menambah campaign');
        } else {
            return redirect('/dashboard/campaign')->with('error', 'Gagal menambah campaign');
        }

    }
    
    public function edit($id, Request $request) {
        $data = [
            'id' => $id,
            'campaign' => CampaignModel::where('id', $id)->first()
        ];

        if($data['campaign'] == null) {
            return redirect('/dashboard/campaign')->with('error', 'Data yang Anda cari tidak ditemukan');
        }

        return view('campaign.edit', $data);
    }

    public function update($id, Request $request) {
        $data = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount
        ];

        $rules = [
            'name' => 'required',
            'short_description' => 'required',
            'goal_amount' => 'required'
        ];

        $validation = Validator::make($data, $rules);
        if($validation->fails()) {
            return redirect('/dashboard/campaign')->with('error', 'Cek kelengkapan data Anda');
        }

        $slug = str_replace(' ', '-', $request->name);
        $slug = strtolower($slug);
        $slug = $slug . '-' . time();

        $data['slug'] = $slug;


        $updated = CampaignModel::where('id', $id)->update($data);

        if($updated) {
            return redirect('/dashboard/campaign')->with('success', 'Sukses mengubah campaign');
        } else {
            return redirect('/dashboard/campaign')->with('error', 'Gagal mengubah campaign');
        }
    }

    public function delete($id) {
        if($id != null) {
            $deleted = CampaignModel::where('id', $id)->delete();
            if($deleted) {
                return redirect('/dashboard/campaign')->with('success', 'Sukses menghapus campaign');
            } else {
                return redirect('/dashboard/campaign')->with('error', 'Gagal menghapus campaign');
            }
        } else {
            return redirect('/dashboard/campaign')->with('error', 'ID campaign harus ada');
        }
    }



    // api
    // get all campaigns
    public function getAllCamapigns() {
        $data = CampaignModel::get();

        return response([
            'meta' => [
                'message' => 'List of Campaigns',
                'code' => 200,
                'status' => 'success'
            ],
            'data' => $data
        ]);
    }
}
