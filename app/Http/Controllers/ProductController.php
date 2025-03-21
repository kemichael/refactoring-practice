<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 一覧表示
    public function ichiran(Request $request)
    {
        $query = Product::query()->with('company'); // 会社情報も一緒に取得

        // フィルタリング
        if ($keyword = $request->input('keyword')) {
            $query->where('product_name', 'like', "%{$keyword}%");
        }
        if ($company = $request->input('search-company')) {
            $query->where('company_id', '=', $company);
        }
        if ($min_price = $request->input('min_price')) {
            $query->where('price', '>=', $min_price);
        }
        if ($max_price = $request->input('max_price')) {
            $query->where('price', '<=', $max_price);
        }
        if ($min_stock = $request->input('min_stock')) {
            $query->where('stock', '>=', $min_stock);
        }
        if ($max_stock = $request->input('max_stock')) {
            $query->where('stock', '<=', $max_stock);
        }

        $products = $query->simplePaginate(10);
        $companies = Company::all();  // 会社情報も一緒に取得
        return view('lists', compact('products', 'companies'));
    }

    // 新規登録画面表示
    public function showStoreForm()
    {
        $companies = Company::all();
        return view('regist', compact('companies'));
    }

    // 新規登録処理
    public function registSubmit(Request $request)
    {
        $request->validate([
            'product_name' => 'required|max:255',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|max:255',
            'img_path' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $image = $request->file('img_path');
            if ($image) {
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/' . $filename;
            } else {
                $img_path = null;
            }

            Product::create([
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'img_path' => $img_path
            ]);

            DB::commit();
            return redirect(route('lists'));
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    // 商品詳細画面表
    public function showDetail($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('detail', ['product' => $product]);
    }

    // 商品編集画面表示
    public function showEdit($id)
    {
        $companies = Company::all();
        $product = Product::with('company')->findOrFail($id);
        return view('edit', ['companies' => $companies, 'product' => $product]);
    }

    // 商品編集処理
    public function registEdit(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|max:255',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|max:255',
            'img_path' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $image = $request->file('img_path');
            if ($image) {
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/' . $filename;

                Product::where('id', $id)->update([
                    'product_name' => $request->input('product_name'),
                    'company_id' => $request->input('company_id'),
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                    'comment' => $request->input('comment'),
                    'img_path' => $img_path
                ]);
            } else {
                Product::where('id', $id)->update([
                    'product_name' => $request->input('product_name'),
                    'company_id' => $request->input('company_id'),
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                    'comment' => $request->input('comment'),
                ]);
            }

            DB::commit();
            return redirect(route('detail', ['id' => $id]));
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    // 削除処理
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Product::where('id', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('lists');
    }
}
