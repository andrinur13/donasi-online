<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignImageModel extends Model
{
    use HasFactory;
    protected $table = 'campaign_images';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['id', 'campaign_id', 'file_name', 'is_primary','created_at', 'updated_at'];

}
