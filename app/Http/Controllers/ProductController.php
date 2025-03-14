<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{

    protected $product_model;
    protected $company_model;
    protected $product_service;

    public function __construct(Product $product_model, Company $company_model, ProductService $product_service)
    {
        $this->product_model = $product_model;
        $this->company_model = $company_model;
        $this->product_service = $product_service;
    }

    // 一覧表示
    public function showList(Request $request){
        $products = $this->product_model->searchProduct($request);
        $companies = $this->company_model->getAllCompanyInfo();
        return view('lists',['products' => $products, 'companies' => $companies]);
    }


    //新規登録画面表示
    public function showStoreForm() {
        $companies = DB::table('companies')->get();
        return view('regist', compact('companies'));
    }


    //新規登録処理
    public function registSubmit(ProductRequest $request) {
        DB::beginTransaction();
        try{
            $this->product_service->storeProduct($request);
            DB::commit();
            return redirect(route('lists'));
        }catch(Exception $e) {
            DB::rollBack();
        }
    }

    //商品詳細画面表示
    public function showDetail($id){
        $product = $this->product_model->getProductDetail($id);
        return view('detail', ['product' => $product]);
    }

    //商品編集画面表示
    public function showEdit($id){
        $companies = DB::table('companies')->get();
        $product = $this->product_model->getProductDetail($id);
        return view ('edit', ['companies' => $companies, 'product' => $product]);
    }

    //商品編集
    public function registEdit(ProductRequest $request, $id){
        DB::beginTransaction();
        try{
            $this->product_service->updateProduct($request, $id);
            DB::commit();
            return redirect(route('detail', ['id' => $id]));
        }catch(Exception $e) {
            DB::rollBack();
        }
    }

    //削除処理
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $this->product_model->deleteProduct($id);
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('lists');
    }

}
