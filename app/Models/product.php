<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name','img_path','price','stock','company_id','comment','created_at','updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeSearch($query, $filters)
    {
        if (!empty($filters['keyword'])) {
            $query->where('product_name', 'like', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['search_company'])) {
            $query->where('company_id', $filters['search_company']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['min_stock'])) {
            $query->where('stock', '>=', $filters['min_stock']);
        }

        if (!empty($filters['max_stock'])) {
            $query->where('stock', '<=', $filters['max_stock']);
        }

        return $query;
    }
}

