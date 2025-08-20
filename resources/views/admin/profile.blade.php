@extends('admin.includes.main')
@section('main')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Admin Profile</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
        <div class="card-body my-3 py-2">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Profile Picture --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center mb-3">
                            <img id="previewImage"
                                 src="{{ $admin->business_logo ? asset('storage/app/public/' . $admin->business_logo) : asset('storage/app/public/uploads/avatar.webp') }}"
                                 class="rounded-circle border"
                                 width="150"
                                 height="150"
                                 alt="Profile Picture">
                        </div>

                    </div>

                    <div class="col-md-3">
                        <h5>Update Business Logo</h5>
                        <input type="file" name="business_logo" class="form-control mt-2" id="imageUpload" accept="image/*" style="margin-top: 40px;">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="margin-top: 10px;margin-buttom:10px; padding:10px;">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;padding:10px;">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control" name="email" value="{{ $admin->email }}" required>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Business Information --}}
                <h5 class="text-primary">Business Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Name</label>
                        <input type="text" class="form-control" name="business_name" value="{{ $admin->business_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Category</label>
                        <input type="text" class="form-control" name="business_category" value="{{ $admin->business_category }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Business Description</label>
                        <textarea class="form-control" name="business_description">{{ $admin->business_description }}</textarea>
                    </div>
                </div>

                {{-- Address Information --}}
                <h5 class="text-primary">Address Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control" name="state" value="{{ $admin->state }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="{{ $admin->city }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Postal Code</label>
                        <input type="text" class="form-control" name="postal_code" value="{{ $admin->postal_code }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Area</label>
                        <input type="text" class="form-control" name="area" value="{{ $admin->area }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Business Address</label>
                        <textarea class="form-control" name="business_address">{{ $admin->business_address }}</textarea>
                    </div>
                </div>

                {{-- Banking Information --}}
                <h5 class="text-primary">Banking Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" value="{{ $admin->bank_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Branch</label>
                        <input type="text" class="form-control" name="bank_branch" value="{{ $admin->bank_branch }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Account Holder Name</label>
                        <input type="text" class="form-control" name="account_holder_name" value="{{ $admin->account_holder_name }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" name="account_number" value="{{ $admin->account_number }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" class="form-control" name="ifsc_code" value="{{ $admin->ifsc_code }}">
                    </div>
                </div>

                {{-- Save Button --}}

                <div class="text-center" style="margin: 10px 0; padding: 10px 0;">
                    <button type="submit" class="btn btn-success px-4">Update Profile</button>
                </div>


            </form>
        </div>
    </div>
</div>

{{-- Image Preview Script --}}
<script>
document.getElementById('imageUpload').addEventListener('change', function(event) {
    let reader = new FileReader();
    reader.onload = function() {
        document.getElementById('previewImage').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
});
</script>

@endsection
