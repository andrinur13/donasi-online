<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'campaign_id', 'user_id', 'amount', 'status', 'code', 'created_at', 'updated_at'];

}
