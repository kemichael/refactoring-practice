<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductService
{

    protected $product_model;
    protected $company_model;

    public function __construct(Product $product_model, Company $company_model)
    {
        $this->product_model = $product_model;
        $this->company_model = $company_model;
    }

    public function storeProduct($request){
        $image = $request->file('img_path');
            if($image){
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/'.$filename;
            }else{
                $img_path = null;
            }


            DB::table('products')->insert([
                'product_name'=> $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'img_path' => $img_path
            ]);
    }

}
