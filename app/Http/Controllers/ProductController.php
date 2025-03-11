<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{

    protected $product_model;
    protected $company_model;

    public function __construct(Product $product_model, Company $company_model)
    {
        $this->product_model = $product_model;
        $this->company_model = $company_model;
    }
    // 一覧表示
    public function showList(Request $request){
        $products = $this->product_model->searchProduct($request);
        $companies = $this->company_model->getAllCompanyInfo();
        return view('lists',['products' => $products, 'companies' => $companies]);

    }


    //新規登録画面表示
    public function showStoreForm(){

        $companies = DB::table('companies')->get();

        return view('regist', compact('companies'));
    }


    //新規登録処理
    public function registSubmit(ProductRequest $request) {
    $model = New product;
        DB::beginTransaction();
        try{
            $image = $request->file('img_path');
            if($image){
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/'.$filename;
            }else{
                $img_path = null;
            }

            $companies = DB::table('companies')->get();

            DB::table('products')->insert([
                'product_name'=> $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'img_path' => $img_path
            ]);

            DB::commit();
            return redirect(route('lists'));
        }catch(Exception $e) {
            DB::rollBack();
        }

    }

    //商品詳細画面表示
    public function showDetail($id){
        $product = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name')
            ->where('products.id', '=', $id)
            ->first();

        return view('detail', ['product' => $product]);
    }

    //商品編集画面表示
    public function showEdit($id){
        $companies = DB::table('companies')->get();

        $products = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name')
            ->where('products.id', '=', $id)
            ->first();


        return view ('edit', ['companies' => $companies, 'product' => $product]);
    }

    //商品編集
    public function registEdit(ProductRequest $request, $id){
        DB::beginTransaction();
        try{
            $image = $request->file('img_path');
            if($image){
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/'.$filename;

                DB::table('products')
                ->where('products.id', '=', $id)
                ->update([
                    'product_name'=> $request->input('product_name'),
                    'company_id' => $request->input('company_id'),
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                    'comment' => $request->input('comment'),
                    'img_path' => $img_path
                ]);
            }else{
                DB::table('products')
                ->where('products.id', '=', $id)
                ->update([
                    'product_name'=> $request->input('product_name'),
                    'company_id' => $request->input('company_id'),
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                    'comment' => $request->input('comment'),
                ]);
            }

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
            $products = DB::table('products')
                ->where('products.id', '=', $id) ->delete();

            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('lists');
    }

}
