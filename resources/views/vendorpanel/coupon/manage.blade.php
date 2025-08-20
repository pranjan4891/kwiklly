@extends('vendorpanel.include.main')

@section('content')
{{-- Add this in <head> --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

{{-- Add before closing </body> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>



<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Pages</a></li>
    <li class="active">Coupon</li>
</ul>

<div class="page-content-wrap">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ isset($editCoupon) ? 'Edit Coupon' : 'Add Coupon' }}</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('vendor.coupon.store') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="coupon_id" value="{{ $editCoupon->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label">Coupon Code</label>
                            <div class="col-md-5">
                                <input type="text" name="coupon_code" class="form-control" required value="{{ old('coupon_code', $editCoupon->coupon_code ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Discount Type</label>
                            <div class="col-md-5">
                                <select name="discount_type" class="form-control" required>
                                    <option value="percent" {{ (isset($editCoupon) && $editCoupon->discount_type == 'percent') ? 'selected' : '' }}>Percent (%)</option>
                                    <option value="flat" {{ (isset($editCoupon) && $editCoupon->discount_type == 'flat') ? 'selected' : '' }}>Flat (₹)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Discount Value</label>
                            <div class="col-md-5">
                                <input type="number" name="discount_value" class="form-control" required value="{{ old('discount_value', $editCoupon->discount_value ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Start Date</label>
                            <div class="col-md-5">
                                <input type="text" id="start_datepicker" name="start_date" class="form-control" required value="{{ old('start_date', $editCoupon->start_date ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">End Date</label>
                            <div class="col-md-5">
                                <input type="text" id="end_datepicker" name="end_date" class="form-control" required value="{{ old('end_date', $editCoupon->end_date ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button class="btn btn-success" type="submit">{{ isset($editCoupon) ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('vendor.coupon.manage') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Coupon List</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Coupon Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $index => $coupon)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $coupon->coupon_code }}</td>
                                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                                        <td>{{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : '₹' . $coupon->discount_value }}</td>
                                        <td>{{ $coupon->start_date }}</td>
                                        <td>{{ $coupon->end_date }}</td>
                                        <td>{{ $coupon->status }}</td>
                                        <td>
                                            <a href="{{ route('vendor.coupon.manage', ['edit_id' => $coupon->id]) }}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('vendor.coupon.delete', $coupon->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">No coupons found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(function () {
    $("#start_datepicker, #end_datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        minDate: 0 // disables past dates
    });
});
</script>
@endsection

@push('scripts')

@endpush

