@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center mb-3">
	<a href="{{ route('admin.giftLog.index') }}" class="btn btn-sm btn-primary me-3">
		<i class="fas fa-arrow-left"></i>
	</a>
	<h4 class="mb-0 page-title">Edit gift status</h4>
</div>

@if(session()->has('success'))
<p class="alert alert-secondary">{{ session()->get('success') }}</p>
@endif

<div class="row">
	<div class="col-md-6 col-lg-5 col-10">
		<div class="box shadow-sm bg-white px-3 py-4">
			<div class="text-center mb-3">
				<img src="{{ $log->gift->thumbnail }}" alt="{{ $log->gift->name }}" class="border" style="max-height: 150px;">
			</div>			
			<div class="row">
				<div class="ps-2 w-25">
					<p>Gift Name</p>
					<p>User Name</p>
					<p>Date</p>
					<p>Status</p>
				</div>
				<div class="ps-2 w-75">
					<p>{{ $log->gift->name }}</p>
					<p>{{ $log->user->name }} ({{ $log->user->role->name }})</p>
					<p>{{ \Carbon\Carbon::parse($log->created_at)->format('d M, Y') }}</p>
					<form action="{{ route('admin.giftLog.update', $log->id) }}" class="d-flex" method="post">
						@csrf
						@method('put')
						<div class="form-group w-50">
							<select class="custom-select" name="status_id">
								<option value="">Select status</option>
								@foreach($statuses as $status)
								<option value="{{ $status->id }}" {{ $status->id == $log->status_id ? 'selected' : '' }}>{{ $status->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group ms-2 w-25">
							<button type="submit" class="btn btn-sm btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection