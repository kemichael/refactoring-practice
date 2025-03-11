@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <h1>商品一覧</h1>
            </div>
            <!-- 検索フォーム-->
            <form action="{{ route('lists') }}" method="GET" id="search-form">
                <div class="search">
                    <div class="row">
                        <!-- 商品名 -->
                        <input type="text" placeholder='商品名' name='keyword' class="form-control search-input col-auto" >
                        <span class="col-auto">&nbsp; &nbsp; </span>
                        <!-- 会社名select -->
                        <select name="search-company" class="form-control search-input col-auto">
                            <option value="">会社名</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <input type="number" name="min_price" placeholder="価格下限" class="form-control search-input col-auto">
                        <span class="col-auto">〜</span>
                        <input type="number" name="max_price" placeholder="価格上限" class="form-control search-input col-auto">
                    </div>
                    <div class="row">
                        <input type="number" name="min_stock" placeholder="在庫下限" class="form-control search-input col-auto">
                        <span class="col-auto">〜</span>
                        <input type="number" name="max_stock" placeholder="在庫上限" class="form-control search-input col-auto">
                    </div>
                    <input type="submit" class="btn btn-info search-btn" value="検索" id="search-btn">
                </div>
            </form>
            <div>
                <button onclick="location.href='{{ route('regist') }}'" class="btn btn-primary">新規登録</button>
            </div>
            <!-- 商品一覧表示 -->
            <div id="products-table">
                <table border="1" class="table table-striped" id="pr-table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>画像</th>
                            <th>商品名</th>
                            <th>会社名</th>
                            <th>価格</th>
                            <th>在庫</th>
                            <th>コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td><img src="{{ asset($product->img_path) }}" alt="商品画像" class="img_view"></td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->company_name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->comment }}</td>
                                <td><input type="button" value='詳細' onclick="location.href='{{ route('detail', ['id' => $product->id]) }}'" class="btn btn-primary"></td>
                                <td>
                                    <form action="{{ route('destroy',['id' => $product->id ])}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" class="btn btn-danger delete-btn" value="削除" data-delete-id="{{ $product->id}}"></td>
                                    </form>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
