@extends('vendorpanel.include.main')

@section('content')
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            {{-- Flash Messages --}}
            <div class="msg-container">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Coupon List</h3>
                    <div class="pull-right">
                        <a href="{{ route('vendor.coupons.create') }}" class="btn btn-info btn-sm">+ Add Coupon</a>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable" id="datatable">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Coupon Code</th>
                                    <th>Discount Type</th>
                                    <th>Value</th>
                                    <th>Usage Limit</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $index => $coupon)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                                        <td>
                                            {{ $coupon->discount_type === 'percentage'
                                                ? $coupon->discount_value . '%'
                                                : '₹' . $coupon->discount_value }}
                                        </td>
                                        <td>{{ $coupon->max_uses }}</td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vendor.coupons.edit', $coupon->id) }}" class="btn btn-success btn-sm" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <form action="{{ route('vendor.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this coupon?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No coupons found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- /.panel -->
        </div>
    </div>
</div>
@endsection
