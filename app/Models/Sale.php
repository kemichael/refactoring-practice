<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;
    // protected $fillable =['id', 'product_id', 'created_at', 'updated_at'];
    protected $fillable =['product_id'];

}
