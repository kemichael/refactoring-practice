<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    // 一括代入を許可するカラム
    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    // バリデーションルールを返す（共通化）
    public static function validationRules()
    {
        return [
            'product_name' => 'required|max:255',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|max:255',
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    // リレーション：Product は Company に属する
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // 画像のアップロード処理
    public static function uploadImage($image)
    {
        if ($image) {
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('public/images', $filename);
            return Storage::url($path); // 例: /storage/images/filename.jpg
        }
        return null;
    }

    // 商品の新規作成（画像対応）
    public static function createWithImage($data, $image = null)
    {
        $data['img_path'] = self::uploadImage($image);
        return self::create($data);
    }

    // 商品の更新
    public function updateWithImage($data, $image = null)
    {
        if ($image) {
            $data['img_path'] = self::uploadImage($image);
        }
        return $this->update($data);
    }

    // 商品検索フィルター
    public function scopeFilter($query, $params)
    {
        if (!empty($params['keyword'])) {
            $query->where('product_name', 'like', '%' . $params['keyword'] . '%');
        }

        if (!empty($params['search-company'])) {
            $query->where('company_id', $params['search-company']);
        }

        if (!empty($params['min_price'])) {
            $query->where('price', '>=', $params['min_price']);
        }

        if (!empty($params['max_price'])) {
            $query->where('price', '<=', $params['max_price']);
        }

        if (!empty($params['min_stock'])) {
            $query->where('stock', '>=', $params['min_stock']);
        }

        if (!empty($params['max_stock'])) {
            $query->where('stock', '<=', $params['max_stock']);
        }

        return $query;
    }
}
