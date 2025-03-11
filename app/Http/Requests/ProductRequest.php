<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required | max:255',
            'company_id'   => 'required | max:10 | integer',
            'price'        => 'required | integer',
            'stock'        => 'required | integer',
            'comment'      => 'max:10000',
        ];
    }

    public function attributes()
    {
        return [
            'product_name' => '商品名',
            'company_id'   => 'メーカー',
            'price'        => '価格',
            'stock'        => '在庫',
            'comment'      => 'コメント',
        ];
    }

    public function messages() {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'product_name.max'      => ':attributeは:max字以内で入力してください。',
            'company_id.required'   => ':attributeは必須項目です',
            'company_id.max'        => ':attributeは:max字以内で入力してください。',
            'company_id.integer'    => ':attributeは数字で入力してください。',
            'price.required'        => ':attribute必須項目です。',
            'price.integer'         => ':attributeは数字で入力してください。',
            'stock.required'        => ':attribute必須項目です。',
            'stock.integer'         => ':attributeは数字で入力してください。',
            'comment.max'           => ':attribute:max字以内で入力してください。',
        ];
    }
}
