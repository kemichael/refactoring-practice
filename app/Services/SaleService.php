<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Company;

class SaleService
{

    public function __construct(Product $product, Company $company)
    {
        $this->product = $product;
        $this->company = $company;
        $this->sale = $sale;
    }

    public function getProduct($id)
    {
        return $this->product->with('company')->find($id);
    }

    public function buyProduct($id)
    {
        try {
            DB::beginTransaction();
            //productsテーブルのstock減算
            $this->product
                ->where('id', '=', $id)
                ->decrement('stock');
        
            //減算後の情報を返却
            $afterBuy = $this->product
                ->select('id','product_name','stock')
                ->where('id', '=', $id)
                ->first();
            
            //salesテーブルにインサート
            $this->sale
                ->insert([
                    'product_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }

        //購入処理後の情報を返却
        return response()->json($afterBuy);
        
    }    
}

