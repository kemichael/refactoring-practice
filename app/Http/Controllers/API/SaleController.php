<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Product;

class SaleController extends Controller
{

    protected $saleService;

    public function __construct(ProductService $saleService){
        $this->saleService = $saleService;
    }
    public function buy(Request $request){


        $id = $request->input('product_id');

        $products  = $this->saleService->getProduct($id);

        //商品なし
        if(!$product){
            return response()->json('商品がありません');
        }
        //在庫なし
        if($product->stock <= 0){
            return response()->json('在庫がありません');
        }

        $this->saleService->buyProduct($id);

    }
}
