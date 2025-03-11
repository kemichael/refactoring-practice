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

    /**
     * 登録用配列作成
     *
     * @param [type] $request
     * @return array
     */
    private function getInputParameters($request) {
        return [
            'product_name' => $request->input('product_name'),
            'company_id'   => $request->input('company_id'),
            'price'        => $request->input('price'),
            'stock'        => $request->input('stock'),
            'comment'      => $request->input('comment')
        ];
    }

    /**
     * 商品新規登録処理
     *
     * @param [type] $request
     * @return void
     */
    public function storeProduct($request){
        $image = $request->file('img_path');
        $data = $this->getInputParameters($request);
        if($image){
            $data['img_path'] = $this->storeImage($image);
        }else{
            $data['img_path'] = null;
        }

        $this->product_model->storeProduct($data);
    }

    /**
     * storeImage 画像登録関数
     *
     * @param [type] $image
     * @return string
     */
    public function storeImage($image) {
        $filename = $image->getClientOriginalName();
        $image->storeAs('public/images', $filename);
        return 'storage/images/'.$filename;
    }


}
