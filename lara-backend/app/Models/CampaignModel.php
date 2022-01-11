<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignModel extends Model
{
    use HasFactory;
    protected $table = 'campaigns';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'user_id', 'name', 'short_description', 'perks', 'backer_count', 'goal_amount', 'current_amount', 'slug', 'created_at', 'updated_at'];

}
