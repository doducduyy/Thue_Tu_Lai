@extends('layouts.master')
@section('title', 'Thêm phương thức thanh toán')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">CẤU HÌNH HỆ THỐNG</span><h1>Thêm phương thức thanh toán</h1></div></header>
    <section class="form-container"><form method="post" action="{{ route('payment_methods.store') }}">@csrf
        <div class="form-grid">
            <div class="form-group"><label for="name">Tên phương thức</label><input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="form-group"><label for="code">Mã tham chiếu</label><input id="code" type="text" name="code" class="form-control" value="{{ old('code') }}" required></div>
            <div class="form-group"><label for="description">Mô tả</label><textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea></div>
        </div><div class="form-actions"><a class="btn-cancel" href="{{ route('payment_methods.index') }}">Hủy</a><button class="btn-save" type="submit">Lưu phương thức</button></div>
    </form></section>
@endsection
