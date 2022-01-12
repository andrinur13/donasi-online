<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['id', 'name', 'guard_name', 'created_at', 'updated_at'];
}
