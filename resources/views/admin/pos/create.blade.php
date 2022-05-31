@extends('layouts.pos')

@section('title', 'POS')

@section('classes', 'admin admin-pos admin-pos-create')

@section('content')


<pos-index :order="{{ $order }}" :skus="{{ $skus }}" :statuses="{{ $statuses }}"></pos-index>

@endsection