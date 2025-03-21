<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Company;

class ProductService
{

    public function __construct(Product $product, Company $company)
    {
        $this->product = $product;
        $this->company = $company;
    }

    public function getSearchProducts(array $filters){
        return $this->product
            ->search($filters);
    }

    public function getAllCompanies(){
        return $this->company->all();
    }

    public function registProducts(){
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

            $companies = getAllCompanies();

            $this->product->insert([
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

    public function getProductDetail($id)
    {
        return $this->product->with('company')->find($id);
    }

    public function editProduct($request, $id){
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

                $this->product
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
                $this->product
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

    public function deleteProduct($id){
        DB::beginTransaction();
        try{
        $products = $this->product
                    ->where('products.id', '=', $id) ->delete();
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
        }
    }
}

