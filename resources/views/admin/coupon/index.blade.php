@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
   <div class="row">
      <div class="col-md-12">
         <div class="msg-container"></div>

         <div class="panel panel-default">
            <div class="panel-heading">
               <div class="row">
                  <div class="col-sm-6">
                     <h3 class="panel-title">Coupons</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                     <a href="{{ route('admin.coupons.create') }}" class="btn btn-info">+ Add Coupon</a>
                  </div>
                  <div class="col-sm-12" style="margin-top: 10px;">

                     {{-- Display validation errors --}}
                     @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                     @endif

                     {{-- Display success or error messages --}}
                     @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                     @endif
                     @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid">
                            <thead>
                            <tr role="row">
                                <th>S No.</th>
                                <th>Coupon Code</th>
                                <th>Discount Type</th>
                                <th>Value</th>
                                <th>Usage Limit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $index => $coupon)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ ucfirst($coupon->discount_type) }}</td>
                                <td>
                                    {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '₹' . $coupon->discount_value }}
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
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-success tooltips" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-danger tooltips" title="Delete" onclick="return confirm('Are you sure want to delete this coupon?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
