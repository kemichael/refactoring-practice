<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // 一覧表示
    public function ichiran(Request $request)
    {
        $products = $this->productService->getProducts($request);
        $companies = $this->productService->getCompanies();
        return view('lists', compact('products', 'companies'));
    }

    // 新規登録画面表示
    public function showStoreForm()
    {
        $companies = $this->productService->getCompanies();
        return view('regist', compact('companies'));
    }

    private function productValidationRules(){
        return [
            'product_name' => 'required|max:255',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|max:255',
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    // 新規登録処理
    public function registSubmit(Request $request)
    {
        $request->validate($this->productValidationRules());
        $this->productService->createProduct($request);
        return redirect(route('lists'));
    }

    // 商品詳細画面表示
    public function showDetail($id)
    {
        $product = $this->productService->getProductDetail($id);
        return view('detail', compact('product'));
    }

    // 商品編集画面表示
    public function showEdit($id)
    {
        $product = $this->productService->getProductDetail($id);
        $companies = $this->productService->getCompanies();
        return view('edit', compact('product', 'companies'));
    }

    // 商品編集
    public function registEdit(Request $request, $id)
    {
        $request->validate($this->productValidationRules());
        $this->productService->updateProduct($request, $id);
        return redirect(route('detail', ['id' => $id]));
    }

    // 削除処理
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('lists');
    }
}
