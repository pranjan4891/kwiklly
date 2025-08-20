@extends('admin.includes.main')

@section('main')
<style>
    .vendor_status {
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 4px;
        margin-left: 10px;
        font-size: 14px;
    }

    .vendor_status.pending {
        background-color: #f0ad4e; /* orange */
        color: #fff;
    }

    .vendor_status.approved {
        background-color: #5cb85c; /* green */
        color: #fff;
    }

    .vendor_status.rejected {
        background-color: #d9534f; /* red */
        color: #fff;
    }

    .vendor_comment {
        margin-left: 10px;
        color: #777;
        font-style: italic;
        font-size: 13px;
    }
    </style>
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">
            Vendor Full Details
            <span class="vendor_status {{ $vendor->status == 0 ? 'pending' : ($vendor->status == 1 ? 'approved' : 'rejected') }}">
                {{ $vendor->status == 0 ? 'Pending' : ($vendor->status == 1 ? 'Approved' : 'Rejected') }}
            </span>
            <em class="vendor_comment">({{ $vendor->admin_comments ?? 'No comment' }})</em>
        </h3>
    </div>


    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Status & Comment Form --}}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Update Status & Comment</strong></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('admin.vendor.updateStatus', $vendor->uuid) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status" required>
                        <option value="0" {{ $vendor->status == 0 ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ $vendor->status == 1 ? 'selected' : '' }}>Approved</option>
                        <option value="2" {{ $vendor->status == 2 ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Admin Comment</label>
                    <textarea name="comment" class="form-control" rows="4">{{ old('comment')?? $vendor->admin_comments }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
               <a href="{{ url()->previous() ?? route('admin.vendor.pending') }}" class="btn btn-default">Back</a>

            </form>
        </div>
    </div>
    {{-- Vendor Information --}}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Vendor Information</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $vendor->name }}</p>
                    <p><strong>Email:</strong> {{ $vendor->email }}</p>
                    <p><strong>Email Verified:</strong> {{ $vendor->email_verified_at }}</p>
                    <p><strong>Phone:</strong> {{ $vendor->phone }}</p>
                    <p><strong>Mobile OTP:</strong> {{ $vendor->mobile_otp }}</p>
                    <p><strong>Mobile Verified:</strong> {{ $vendor->mobile_verified_at }}</p>
                    <p><strong>User Type:</strong> {{ $vendor->user_type }}</p>
                    <p><strong>Status:</strong>
                        @if($vendor->status == 0)
                            <span class="label label-warning">Pending</span>
                        @elseif($vendor->status == 1)
                            <span class="label label-success">Approved</span>
                        @elseif($vendor->status == 2)
                            <span class="label label-danger">Rejected</span>
                        @endif
                    </p>
                    <p><strong>Is Active:</strong> {{ $vendor->is_active ? 'Yes' : 'No' }}</p>
                    {{-- <p><strong>Created At:</strong> {{ $vendor->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $vendor->updated_at }}</p> --}}
                </div>

                <div class="col-md-6">
                    <p><strong>Landmark:</strong> {{ $vendor->landmark }}</p>
                    <p><strong>State:</strong> {{ $vendor->state }}</p>
                    <p><strong>City:</strong> {{ $vendor->city }}</p>
                    <p><strong>Area:</strong> {{ $vendor->area }}</p>
                    <p><strong>Postal Code:</strong> {{ $vendor->postal_code }}</p>
                    <p><strong>Latitude:</strong> {{ $vendor->latitude }}</p>
                    <p><strong>Longitude:</strong> {{ $vendor->longitude }}</p>
                    <p><strong>Display Name:</strong> {{ $vendor->display_name }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Business Information --}}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Business Information</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Business Name:</strong> {{ $vendor->business_name }}</p>
                    <p><strong>Business Address:</strong> {{ $vendor->business_address }}</p>
                    <p><strong>GSTIN:</strong> {{ $vendor->gstin }}</p>
                    <p><strong>Business Category:</strong> {{ $vendor->business_category }}</p>
                    <p><strong>Business Description:</strong> {{ $vendor->business_description }}</p>
                    <p><strong>Business Contact No:</strong> {{ $vendor->business_contact_no }}</p>
                    <p><strong>Open Time:</strong> {{ $vendor->open_time }}</p>
                    <p><strong>Close Time:</strong> {{ $vendor->close_time }}</p>
                </div>

                <div class="col-md-6">
                    <p><strong>Business Logo:</strong><br>
                        @if($vendor->business_logo)
                            <img src="{{ asset('storage/vendor/logo/' . $vendor->business_logo) }}" alt="Business Logo" width="100">
                        @endif
                    </p>
                    <p><strong>Business Banner:</strong><br>
                        @if($vendor->business_banner)
                            <img src="{{ asset('storage/vendor/banner/' . $vendor->business_banner) }}" alt="Business Banner" width="100">
                        @endif
                    </p>
                    <p><strong>Minimum Order Value:</strong> {{ $vendor->minimum_order_value }}</p>
                    <p><strong>Delivery Range:</strong> {{ $vendor->delivery_range }}</p>
                    <p><strong>Service Offered:</strong> {{ $vendor->service_offered }}</p>
                    <p><strong>Delivery Charge:</strong> {{ $vendor->delivery_charge }}</p>
                    <p><strong>Delivery Charge Status:</strong> {{ $vendor->delivery_charge_status }}</p>
                    <p><strong>Deliver From:</strong> {{ $vendor->deliver_from }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Banking Information --}}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Banking Information</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Account Holder:</strong> {{ $vendor->account_holder_name }}</p>
                    <p><strong>Account Number:</strong> {{ $vendor->account_number }}</p>
                    <p><strong>IFSC Code:</strong> {{ $vendor->ifsc_code }}</p>
                    <p><strong>Bank Name:</strong> {{ $vendor->bank_name }}</p>
                    <p><strong>Bank Branch:</strong> {{ $vendor->bank_branch }}</p>
                    <p><strong>Bank City:</strong> {{ $vendor->bank_city }}</p>
                    <p><strong>Cancelled Cheque:</strong><br>
                        @if($vendor->cancel_cheque_image)
                            <img src="{{ asset('storage/vendor/cheque/' . $vendor->cancel_cheque_image) }}" alt="Cancelled Cheque" width="100">
                        @endif
                    </p>
                </div>

                <div class="col-md-6">
                    <p><strong>PAN Number:</strong> {{ $vendor->pan_number }}</p>
                    <p><strong>PAN Image:</strong><br>
                        @if($vendor->pan_image)
                            <img src="{{ asset('storage/vendor/pan/' . $vendor->pan_image) }}" alt="PAN Image" width="100">
                        @endif
                    </p>
                    <p><strong>TAN Number:</strong> {{ $vendor->tan_number }}</p>
                    <p><strong>TAN Image:</strong><br>
                        @if($vendor->tan_image)
                            <img src="{{ asset('storage/vendor/tan/' . $vendor->tan_image) }}" alt="TAN Image" width="100">
                        @endif
                    </p>
                    <p><strong>CIN Number:</strong> {{ $vendor->cin_number }}</p>
                    <p><strong>CIN Image:</strong><br>
                        @if($vendor->cin_image)
                            <img src="{{ asset('storage/vendor/cin/' . $vendor->cin_image) }}" alt="CIN Image" width="100">
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Personal Details --}}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Personal/Proprietor Information</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Personal Business Name:</strong> {{ $vendor->personal_business_name }}</p>
                    <p><strong>Personal PAN:</strong> {{ $vendor->personal_pan }}</p>
                    <p><strong>Personal PAN Image:</strong><br>
                        @if($vendor->personal_pan_image)
                            <img src="{{ asset('storage/vendor/personal_pan/' . $vendor->personal_pan_image) }}" alt="Personal PAN" width="100">
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Personal Address Proof:</strong> {{ $vendor->personal_address_proof }}</p>
                    <p><strong>Personal Address Proof Image:</strong><br>
                        @if($vendor->personal_address_proof_image)
                            <img src="{{ asset('storage/vendor/address/' . $vendor->personal_address_proof_image) }}" alt="Address Proof" width="100">
                        @endif
                    </p>
                    <p><strong>Personal Cancel Cheque:</strong><br>
                        @if($vendor->personal_cancel_cheque)
                            <img src="{{ asset('storage/vendor/personal_cheque/' . $vendor->personal_cancel_cheque) }}" alt="Personal Cheque" width="100">
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
