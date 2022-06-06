@extends('layouts.admin')

@section('title', 'Bonuspoints')

@section('classes', 'admin admin-bonuspoints admin-bonuspoints-create')

@section('content-header')
<x-admin.content-header :navs="['bonuspoints', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Bonuspoints</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.bonuspoints.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Amount
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Amount ထည့်ပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <input type="text" name="amount" class="form-control form-control-sm" placeholder="Name" value="{{ old('amount') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Points
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Points ထည့်ပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <input type="text" name="points" class="form-control form-control-sm" placeholder="Name" value="{{ old('points') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Role
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Role ရွေးပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <select name="role_id" class="form-select">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="from-group">
                        <button type="submit" class="btn btn-sm btn-secondary">
                            <small class="me-2"><i class="fas fa-save"></i></small>
                            <span>Save</span>
                        </button>
                    </div>
                </div>
            </div>
    </form>

</div>

@endsection