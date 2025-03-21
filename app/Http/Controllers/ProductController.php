<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\UserService;


class ProductController extends Controller
{

    protected $productService;

public function __construct(ProductService $productService){
    $this->productService = $productService;
}

    // 一覧表示
    public function ichiran(Request $request){

        $filters = [
            'keyword'        => $request->input('keyword'),
            'search_company' => $request->input('search-company'),
            'min_price'      => $request->input('min_price'),
            'max_price'      => $request->input('max_price'),
            'min_stock'      => $request->input('min_stock'),
            'max_stock'      => $request->input('max_stock'),
        ];

        $products  = $this->productService->getSearchProducts($filters);
        $companies = $this->productService->getAllCompanies();

        return view('lists',['products' => $products, 'companies' => $companies]);
    }

    //新規登録画面表示
    public function showStoreForm(){

        $companies = $this->productService->getAllCompanies();

        return view('regist', compact('companies'));
    }

    //新規登録処理
    public function registSubmit(Request $request) {

        $this->productService->registProducts($filters);

    }

    //商品詳細画面表示
    public function showDetail($id){
        $products  = $this->productService->getProductDetail($id);
        return view('detail', ['product' => $product]);
    }

    //商品編集画面表示
    public function showEdit($id){
        $companies = $this->productService->getAllCompanies();
        $products  = $this->productService->getProductDetail($id);

        return view ('edit', ['companies' => $companies, 'product' => $product]);
    }

    //商品編集
    public function registEdit(Request $request, $id){
        $this->productService->editProduct($request, $id);
    }

    //削除処理
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('lists');
    }

}
