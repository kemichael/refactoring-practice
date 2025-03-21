<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function _construct(ProductService $productService) {
        $this->productService = $productService;
    }

    // 一覧表示
    public function ichiran(Request $request){
        list($products, $companies) = $this->productService->ichiran($request);
        return view('lists',['products' => $products, 'companies' => $companies]);

    }


    //新規登録画面表示
    public function showStoreForm(){

        $companies = $this->productService->showStoreForm();

        return view('regist', compact('companies'));
    }


    //新規登録処理
    public function registSubmit(Request $request) {
        $this->productService->registSubmit();
    }

    //商品詳細画面表示
    public function showDetail($id){
        $product = $this->productService->showDetail();

        return view('detail', ['product' => $product]);
    }

    //商品編集画面表示
    public function showEdit($id){
        list($companies, $product) = $this->productService->showEdit($id);
        return view ('edit', ['companies' => $companies, 'product' => $product]);
    }

    //商品編集
    public function registEdit(Request $request, $id){
        $product = $this->productService->registEdit($request, $id);
    }

    //削除処理
    public function destroy($id)
    {
        $product = $this->productService->destroy($id);
    }

}
