<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    //会社情報を呼び出す
    public function getAllCompanies()
    {
        return DB::table('companies')->get();
    }

    //productのIDから紐づく情報を呼び出す
    public function getProductById($id)
    {
    return DB::table('products')
        ->join('companies', 'products.company_id', '=', 'companies.id')
        ->select('products.*', 'companies.company_name')
        ->where('products.id', '=', $id)
        ->first();
    }

    //削除処理
    public function deleteProduct($id)
    {
    // トランザクション開始
    DB::beginTransaction();
    try{
        DB::table('products')->where('id', '=', $id) ->delete();
        DB::commit();
    }catch(Exception $e) {
        DB::rollBack();
    }        
    }

    // 商品編集機能
    public function updateProduct($id, $data)
    {
        DB::beginTransaction();
        try {
            $updateData = [
                'product_name' => $data['product_name'],
                'company_id' => $data['company_id'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'comment' => $data['comment'] ?? null,
            ];

            if (!empty($data['img_path'])) {
                $filename = $data['img_path']->getClientOriginalName();
                $data['img_path']->storeAs('public/images', $filename);
                $updateData['img_path'] = 'storage/images/' . $filename;
            }

            DB::table('products')
                ->where('id', '=', $id)
                ->update($updateData);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
