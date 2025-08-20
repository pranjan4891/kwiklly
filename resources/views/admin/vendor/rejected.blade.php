@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Rejected Vendors</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Rejected Vendors List</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Business Logo</th>
                                        <th>Vendor Info</th>
                                        <th>Business Info</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendors as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($vendor->business_logo)
                                                    <img src="{{ asset('storage/vendor/logo/' . $vendor->business_logo) }}" alt="Logo" width="50" height="50">
                                                @else
                                                    <img src="{{ asset('images/default.png') }}" alt="Default Logo" width="50" height="50">
                                                @endif
                                            </td>
                                            <td>
                                                <strong>Name:</strong> {{ $vendor->name }}<br>
                                                <strong>Email:</strong> {{ $vendor->email }}<br>
                                                <strong>Phone:</strong> {{ $vendor->phone ?? $vendor->business_contact_no }}<br>
                                                <strong>Address:</strong> {{ $vendor->landmark }}, {{ $vendor->area }}, {{ $vendor->city->city_name?? '' }} - {{ $vendor->postal_code }}<br>
                                                <strong>State:</strong> {{ $vendor->state->state_name?? '' }}<br>
                                            </td>
                                            <td>
                                                <strong>Business Name:</strong> {{ $vendor->business_name }}<br>
                                                <strong>GSTIN:</strong> {{ $vendor->gstin }}<br>
                                                <strong>PAN:</strong> {{ $vendor->pan_number }}<br>
                                                <strong>Category:</strong> {{ $vendor->business_category }}<br>
                                                <strong>Contact No:</strong> {{ $vendor->business_contact_no }}<br>
                                                <strong>Address:</strong> {{ $vendor->business_address }}<br>
                                                <strong>Description:</strong> {{ $vendor->business_description }}<br>
                                            </td>
                                            <td>
                                                @if($vendor->status == 0)
                                                    <span class="label label-warning">Pending</span>
                                                @elseif($vendor->status == 1)
                                                    <span class="label label-success">Approved</span>
                                                @elseif($vendor->status == 2)
                                                    <span class="label label-danger">Rejected</span>
                                                @else
                                                    <span class="label label-default">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.vendor.show', $vendor->uuid) }}" class="btn btn-info btn-xs"><button class="btn btn-info btn-xs">View    </button></a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                           <td>—</td>
                                            <td>—</td>
                                            <td>—</td>
                                            <td>—</td>
                                            <td>—</td>
                                            <td>—</td>
                                        </tr>
                                        <p class="text-center text-danger">No rejected vendors found</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
