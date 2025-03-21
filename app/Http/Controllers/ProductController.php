<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // 一覧表示
    public function ichiran(Request $request)
    {
        $products = Product::with('company')
            ->filter($request->all())
            ->simplePaginate(10);

        $companies = Company::all();

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
        DB::beginTransaction();
        try {
            $validated = $request->validate(Product::validationRules());
            Product::createWithImage($validated, $request->file('img_path'));
            DB::commit();
            return redirect(route('lists'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors('登録に失敗しました。');
        }
    }

    // 商品詳細画面表示
    public function showDetail($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('detail', compact('product'));
    }

    // 商品編集画面表示
    public function showEdit($id)
    {
        $companies = Company::all();
        $product = Product::with('company')->findOrFail($id);
        return view('edit', compact('product', 'companies'));
    }

    // 商品編集
    public function registEdit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate(Product::validationRules());
            $product = Product::findOrFail($id);
            $product->updateWithImage($validated, $request->file('img_path'));
            DB::commit();
            return redirect(route('detail', ['id' => $id]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors('更新に失敗しました。');
        }
    }

    // 削除処理
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Product::destroy($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return redirect()->route('lists');
    }
}
