<?php

namespace App\Http\Controllers;

use App\Models\CampaignImageModel;
use App\Models\CampaignModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use PDF;

class CampaignController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->query('search_campaign')) {
            $search_key = $request->query('search_campaign');
            $data = [
                'campaign' => CampaignModel::where('campaigns.name', 'LIKE', '%' . $search_key . '%')->select('campaigns.*')->get()
            ];
        } else {
            $data = [
                'campaign' => CampaignModel::select('campaigns.*')->get()
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
            'goal_amount' => $request->goal_amount,
            // 'gambar' => $request->file('gambar')
        ];

        $photo = $request->file('gambar');

        // dd($photo);

        $rules = [
            'name' => 'required',
            'short_description' => 'required',
            'goal_amount' => 'required',
            // 'gambar' => 'requried|image'
        ];

        $validation = Validator::make($data, $rules);
        if($validation->fails()) {
            return redirect('/dashboard/campaign')->with('error', 'Cek kelengkapan data Anda');
        }

        $slug = str_replace(' ', '-', $request->name);
        $slug = strtolower($slug);
        $slug = $slug . '-' . time();

        $data['slug'] = $slug;



        $nama_file = 'uploads/' . $slug . '.' . $photo->getClientOriginalExtension();

        $photo->move(public_path('uploads/'),  $nama_file);
        $file_name = $nama_file;


        $create_campaign = CampaignModel::create($data);

        // dd($create_campaign);

        $campaignimgdata = [
            'campaign_id' => $create_campaign->id,
            'file_name' => $nama_file
        ];


        $img_campaign = CampaignImageModel::create($campaignimgdata);


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
        $data = CampaignModel::join('campaign_images', 'campaigns.id', 'campaign_images.campaign_id')->select('campaigns.*', 'campaign_images.file_name')->get();

        if($data != null) {
            foreach($data as $item) {
                $item->file_name = env('APP_URL') . $item->file_name;
            } 
        }

        return response([
            'meta' => [
                'message' => 'List of Campaigns',
                'code' => 200,
                'status' => 'success'
            ],
            'data' => $data
        ]);
    }

    public function getDetailCampaigns(Request $request) {
        $id = $request->id;

        $data = CampaignModel::where('slug', $id)->join('campaign_images', 'campaigns.id', 'campaign_images.campaign_id')->select('campaigns.*', 'campaign_images.file_name')->first();

        if($data != null) {
            $data->file_name = env('APP_URL') . $data->file_name;
        }

        return response([
            'meta' => [
                'message' => 'Detail of Campaigns',
                'code' => 200,
                'status' => 'success'
            ],
            'data' => $data
        ]);
    }

    public function report()
    {
        $campaign = CampaignModel::all();
 
    	$pdf = PDF::loadview('campaign_pdf',['campaign'=>$campaign]);
    	return $pdf->download('laporan-campaign.pdf');
    }
}
