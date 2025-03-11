<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    const PAGINATE_COUNT = 10;

    public function searchProduct($request) {
        $query = DB::table('products')
                    ->join('companies', 'products.company_id', '=', 'companies.id')
                    ->select('products.*', 'companies.company_name');

        if($keyword = $request['keyword']) {
            $query->where('products.product_name', 'like', "%{$keyword}%");
        }

        if($searchCompany = $request['search-company']) {
            $query->where('products.company_id', '=', $searchCompany);
        }


        if($min_price = $request['min_price']) {
            $query->where('products.price', '>=', $min_price);
        }


        if($max_price = $request['max_price']) {
            $query->where('products.price', '<=', $max_price);
        }


        if($min_stock = $request['min_stock']) {
            $query->where('products.price', '>=', $min_stock);
        }

        if($max_stock = $request['max_stock']) {
            $query->where('products.price', '<=', $max_stock);
        }

        return $query->simplePaginate(self::PAGINATE_COUNT);
    }

    public function storeProduct($data) {
        DB::table('products')->insert($data);
    }

}
