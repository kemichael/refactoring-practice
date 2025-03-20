<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    const PAGINATE_COUNT = 10;

    protected $table = "products";
    /**
     * 商品検索クエリ
     *
     * @param [type] $request
     * @return void
     */
    public function searchProduct($request) {
        $query = DB::table($this->table)
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

    /**
     * 商品新規登録クエリ
     *
     * @param [type] $data
     * @return void
     */
    public function storeProduct($data) {
        DB::table($this->table)->insert($data);
    }

    /**
     * 商品情報更新クエリ
     *
     * @param [type] $data
     * @param [type] $id
     * @return void
     */
    public function updateProduct($data, $id) {
        DB::table($this->table)->where('id', $id)->update($data);
    }

    /**
     * 商品詳細情報取得クエリ
     *
     * @param [type] $id
     * @return void
     */
    public function getProductDetail($id) {
        return DB::table($this->table)
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name')
            ->where('products.id', '=', $id)
            ->first();
    }

    /**
     * 商品削除クエリ
     *
     * @param [type] $id
     * @return void
     */
    public function deleteProduct($id) {
        DB::table($this->table)->where('id', $id)->delete();
    }

}
