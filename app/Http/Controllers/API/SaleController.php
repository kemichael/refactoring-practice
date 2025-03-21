<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Product;
use App\Http\Services\SaleService;

class SaleController extends Controller
{

    protected $saleService;

    public function _construct(SaleService $saleService) {
        $this->saleService = $saleService;
    }

    public function buy(Request $request){
        //購入処理後の情報を返却
        return $this->saleService->buy($request);
        
    }
}
