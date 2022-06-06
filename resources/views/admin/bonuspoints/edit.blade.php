@extends('layouts.admin')

@section('title', 'Bonuspoints')

@section('classes', 'admin admin-bonuspoints admin-bonuspoints-edit')

@section('content-header')
<x-admin.content-header :navs="['bonuspoints', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Bonuspoints</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.bonuspoints.update', $bonuspoint->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Amount
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Amount ထည့်ပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <input type="text" name="amount" class="form-control form-control-sm" placeholder="Amount" value="{{ old('amount') ?? $bonuspoint->amt }}">
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
                        <input type="text" name="points" class="form-control form-control-sm" placeholder="Points" value="{{ old('points') ?? $bonuspoint->points }}">
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
                            <option value="{{ $role->id }}" {{ $bonuspoint->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
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