@extends('layouts.admin')

@section('title', 'Profile')

@section('classes', 'admin admin-profile')

@section('content')

@include('components.admin.message')
    <div class="row">

        <div class="col-md-6 mb-3">

            <div class="bg-sidebar px-4 py-3">
                <h5 class="mb-3 text-secondary">Update Information</h5>
                <form action="{{route('admin.profiles.update',$user->id)}}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                       <label for="name" class="mb-2">အမည်</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Update Your Names" value="{{ auth()->user()->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email" class="mb-2">အီးမေးလ်လိပ်စာ</label>
                         <input type="email" name="email" class="form-control form-control-sm" placeholder="Update Your Email" value="{{ auth()->user()->email }}">
                     </div>
                    <div class="form-group">
                        <label for="phone" class="mb-2">ဖုန်းနံပါတ်</label>
                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Update Your Phones" value="{{ auth()->user()->phone }}">
                    </div>
                    <button type="submit" class="btn btn-secondary">Update</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 mb-3">

            <div class="bg-sidebar px-4 py-3">
                <h5 class="mb-3 text-secondary">Profile</h5>
                <form action="{{route('admin.profiles.upload',auth()->user()->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="profile" class="mb-3 d-block">
                            ဓာတ်ပုံပြောင်းရန်
                        </label>
                    <img src="{{ auth()->user()->getImage() }}" alt="{{$user->name}}" class="user-profile rounded mb-3" width="100" height="100">
                        <div class="py-2 w-100">
                            <input type="file"  name="image" id="profile" value="{{ $user->image }}" >
                        </div>
                        @error('image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-secondary">Upload</button>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-3">

            <div class="bg-sidebar px-4 py-3">
                <h5 class="mb-3">Change Password</h5>
                <form action="{{route('admin.profiles.changepassword',auth()->user()->id)}}" method="post">
                    @csrf
                    @method('patch')

                    <div class="form-group">
                        <label for="old" class="mb-2">လက်ရှိစကား၀ှက်</label>
                        <input type="password" id="old" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" placeholder="Old Password" required>

                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new" class="mb-2">စကား၀ှက်အသစ်</label>
                        <input type="password" id="new" name="new_password" class="form-control form-control-sm @error('new_password') is-invalid @enderror" placeholder="New Password" required autocomplete="new-password">
                        @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="confirm" class="mb-2">စကား၀ှက်အသစ်အားပြန်လည်အတည်ပြုပါ</label>
                        <input type="password" id="confirm" name="new_password_confirmation" class="form-control form-control-sm" placeholder="Confirm New Password" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-secondary">Change</button>
                </form>
            </div>
        </div>
    </div>

@endsection
