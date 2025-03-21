<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    // 商品一覧の取得
    public function getProducts(Request $request)
    {
        $name = $request->input('keyword');
        $searchCompany = $request->input('search-company');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');

        $query = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name');

        if ($name) {
            $query->where('products.product_name', 'like', "%{$name}%");
        }

        if ($searchCompany) {
            $query->where('products.company_id', '=', $searchCompany);
        }

        if ($min_price) {
            $query->where('products.price', '>=', $min_price);
        }

        if ($max_price) {
            $query->where('products.price', '<=', $max_price);
        }

        if ($min_stock) {
            $query->where('products.stock', '>=', $min_stock);
        }

        if ($max_stock) {
            $query->where('products.stock', '<=', $max_stock);
        }

        return $query->simplePaginate(10);
    }

    // 商品の登録処理
    public function createProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $image = $request->file('img_path');
            $img_path = $image ? 'storage/images/' . $image->getClientOriginalName() : null;
            if ($image) {
                $image->storeAs('public/images', $image->getClientOriginalName());
            }

            DB::table('products')->insert([
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'img_path' => $img_path
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 商品詳細の取得
    public function getProductDetail($id)
    {
        return DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name')
            ->where('products.id', '=', $id)
            ->first();
    }

    // 商品情報の更新
    public function updateProduct(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $image = $request->file('img_path');
            $img_path = $image ? 'storage/images/' . $image->getClientOriginalName() : null;
            if ($image) {
                $image->storeAs('public/images', $image->getClientOriginalName());
            }

            $updateData = [
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
            ];

            if ($img_path) {
                $updateData['img_path'] = $img_path;
            }

            DB::table('products')
                ->where('products.id', '=', $id)
                ->update($updateData);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 商品削除
    public function deleteProduct($id)
    {
        DB::beginTransaction();
        try {
            DB::table('products')->where('products.id', '=', $id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 会社一覧の取得
    public function getCompanies()
    {
        return DB::table('companies')->get();
    }
}
