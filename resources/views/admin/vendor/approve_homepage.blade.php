@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">{{$title}}</h3>
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
                    <h3 class="panel-title">{{$title}}</h3>
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
                                        <th>Is Home</th>
                                        <th>Order By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendors as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($vendor->business_logo)
                                                    <img src="{{ asset('public/' . $vendor->business_logo) }}" alt="Logo" width="50" height="50">
                                                @else
                                                    <img src="{{ asset('public/default.png') }}" alt="Default Logo" width="50" height="50">
                                                @endif
                                            </td>
                                            <td>
                                                <strong>Name:</strong> {{ $vendor->name }}<br>
                                                <strong>Email:</strong> {{ $vendor->email }}<br>
                                                <strong>Phone:</strong> {{ $vendor->phone ?? $vendor->business_contact_no }}

                                            </td>
                                            <td>
                                                <strong>Business Name:</strong> {{ $vendor->business_name }}<br>
                                                <strong>Address:</strong> {{ $vendor->area }},{{ $vendor->city->city_name ?? '' }} - {{ $vendor->postal_code }}
                                            </td>
                                            <td>
                                                @if($vendor->is_home_request == 1)
                                                    <span class="label label-warning">Requested</span>
                                                @elseif($vendor->is_home_request == 2)
                                                    <span class="label label-success">Approved</span>
                                                @endif
                                            </td>
                                            <td>{{ $vendor->order_by ?? '—' }}</td>
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
                                        <p class="text-center text-danger">No approved vendors found</p>
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
