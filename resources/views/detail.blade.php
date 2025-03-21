@extends('layouts.app')

@section('content')
<div class="container">
<button onclick="location.href='{{ route('lists') }}'" class="btn btn-warning">戻る</button>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <h1>商品詳細</h1>
            </div>
            <div id="detail-area">
                <table>
                    <tr>
                        <td>id</td>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <td>画像</td>
                        <td><img src="{{ asset($product->img_path) }}" alt="商品画像" class="img_view"></td>
                    </tr>
                    <tr>
                        <td>商品名</td>
                        <td>{{ $product->product_name }}</td>
                    </tr>
                    <tr>
                        <td>企業名</td>
                        <td>{{ $product->company_name }}</td>
                    </tr>
                    <tr>
                        <td>ストック</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                    <tr>
                        <td>金額</td>
                        <td>{{ $product->price }}</td>
                    </tr>
                    <tr>
                        <td>コメント</td>
                        <td>{{ $product->comment }}</td>
                    </tr>
                </table>
                <button onclick="location.href='{{ route('edit', ['id' => $product->id])}}'" class="btn btn-primary">商品編集</button>
            </div>
        </div>
    </div>
    
</div>
@endsection
