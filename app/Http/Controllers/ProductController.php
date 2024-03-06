<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    // デプロイテスト
    // 一覧表示
    public function showList(Request $request){
        Log::info('一覧表示処理スタート');
        //受け取ったform内のname="keyword"とnome="search-company"を変数に詰める。
        $keyword = $request->input('keyword');
        $searchCompany = $request->input('search-company');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');

        $model = New product;
        $products = $model->searchList($keyword, $searchCompany, $min_price, $max_price, $min_stock, $max_stock);
        $companies = DB::table('companies')->get();
        return view('lists',['products' => $products, 'companies' => $companies]);
        
    }


    //新規登録画面表示
    public function showRegistForm(){
        
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
            
            $products = $model->registSubmit($request, $img_path);

            DB::commit();
            return redirect(route('lists'));
        }catch(Exception $e) {
            DB::rollBack();
        }
        
    }

    //商品詳細画面表示
    public function showDetail($id){
        $model = New product();
        $product = $model->getProductById($id);
        return view('detail', ['product' => $product]);
    }

    //商品編集画面表示
    public function showEdit($id){
        $companies = DB::table('companies')->get();
        $model = New product;
        $product = $model->getProductById($id);

        return view ('edit', ['companies' => $companies, 'product' => $product]);
    }

    //商品編集
    public function registEdit(ProductRequest $request, $id){
        $model = New product;
        DB::beginTransaction();
        try{
            $image = $request->file('img_path');
            if($image){
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/'.$filename;
                $model->registEdit($request, $img_path, $id);
            }else{
                $model->registEditNoImg($request, $id);
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
            $model = new product;
            $model -> destroyProduct($id);
            
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('lists');
    }
    
}
