<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Product;

class SaleController extends Controller
{
    public function buy(Request $request){


        $id = $request->input('product_id');

        $product = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name')
            ->where('products.id', '=', $id)
            ->first();

        //商品なし
        if(!$product){
            return response()->json('商品がありません');
        }
        //在庫なし
        if($product->stock <= 0){
            return response()->json('在庫がありません');
        }

        try {
            DB::beginTransaction();
            //productsテーブルのstock減算
            DB::table('products')
                ->where('id', '=', $id)
                ->decrement('stock');
        
            //減算後の情報を返却
            $afterBuy = DB::table('products')
                ->select('id','product_name','stock')
                ->where('id', '=', $id)
                ->first();
            
            //salesテーブルにインサート
            DB::table('sales')
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
