<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',  // 会社名
    ];

    // Productとのリレーションシップ
    public function products()
    {
        return $this->hasMany(Product::class); // Companyは多くのProductを持つ
    }
}

