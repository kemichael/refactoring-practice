<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    // 一覧表示
    public function ichiran(Request $request){
        $namae = $request->input('keyword');
        $searchCompany = $request->input('search-company');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');

        $query = DB::table('products')
                    ->join('companies', 'products.company_id', '=', 'companies.id')
                    ->select('products.*', 'companies.company_name');

        if($namae) {
            $query->where('products.product_name', 'like', "%{$namae}%");
        }

        if($searchCompany) {
            $query->where('products.company_id', '=', $searchCompany);
        }


        if($min_price) {
            $query->where('products.price', '>=', $min_price);
        }


        if($max_price) {
            $query->where('products.price', '<=', $max_price);
        }


        if($min_stock) {
            $query->where('products.price', '>=', $min_stock);
        }

        if($max_stock) {
            $query->where('products.price', '<=', $max_stock);
        }

        $products = $query->simplePaginate(10);

        $companies = DB::table('companies')->get();
        return view('lists',['products' => $products, 'companies' => $companies]);

    }


    //新規登録画面表示
    public function showStoreForm(){

        $companies = DB::table('companies')->get();

        return view('regist', compact('companies'));
    }


    //新規登録処理
    public function registSubmit(Request $request) {
    $model = New product;
        DB::beginTransaction();
        try{
            $request->validate([
                'product_name' => 'required|max:255',
                'company_id' => 'required|integer',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|max:255',
                'img_path' => 'nullable|image|max:2048',
            ]);
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
    public function registEdit(Request $request, $id){
        $request->validate([
            'product_name' => 'required|max:255',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|max:255',
            'img_path' => 'nullable|image|max:2048',
        ]);
        DB::beginTransaction();
        try{
            $image = $request->file('img_path');
            if($image){
                // ファイル名を取得
                $filename = $image->getClientOriginalName();
                // storageに保存
                $image->storeAs('public/images', $filename);
                // 文字列作成
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
    // トランザクション開始
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
