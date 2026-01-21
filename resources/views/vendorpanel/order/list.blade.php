@extends('vendorpanel.include.main')
@section('content')

<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Pages</a></li>
                    <li class="active">Order List</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE TITLE -->
<!--                <div class="page-title">
                    <h2><span class="fa fa-arrow-circle-o-left"></span> Edit Product</h2>
                </div>-->
                <div class="page-title">
                    <h3 class="title">Order List</h3>
                </div>
                <!-- END PAGE TITLE -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                            <div class="row">
                            <div class="col-sm-12">
                            <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title"> Search Orders

                                </h3>
                                <div class="panel-body">
                                <div class=" form">
                                    <form class="cmxform form-horizontal tasi-form" method="get" action="{{ route('vendor.orderlist') }}">
                                        <div class="form-group">
                                            <label for="status" class="control-label col-lg-2">Status</label>
                                            <div class="col-lg-5">
                                                <select name="status" class="form-control">
                                                    <option value="">All Status</option>
                                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Packed" {{ request('status') == 'Packed' ? 'selected' : '' }}>Packed</option>
                                                    <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                                    <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_from" class="control-label col-lg-2">Date From</label>
                                            <div class="col-lg-5">
                                                <input class="form-control datepicker" id="datepicker_from" name="date_from" value="{{ request('date_from') }}" type="text" placeholder="DD/MM/YYYY">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_to" class="control-label col-lg-2">Date To</label>
                                            <div class="col-lg-5">
                                                <input class="form-control datepicker" id="datepicker_to" name="date_to" value="{{ request('date_to') }}" type="text" placeholder="DD/MM/YYYY">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <a href="{{ route('vendor.orderlist') }}" class="btn btn-default">
                                                    <i class="fa fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                 <div class="row">
                    <div class="col-md-12">
                            <!-- START DEFAULT DATATABLE -->
                            <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Orders Details</h3>
                                <div class="pull-right">
                                    <span id="order-count" class="badge badge-info">{{ $vendorOrders->total() }} Orders</span>
                                </div>
                            </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table datatable vendor_orderview">
                                            <thead>
                                                <tr>
                                                    <th width="5%">S No.</th>
                                                    <th>Inv No.</th>
                                                    <th>Estimated Delivery Date-Time</th>
                                                    <th>Total</th>
                                                    <th>Cust. Details</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($orderDetails as $key)
                                                    <tr>
                                                        <td>{{ $loop->iteration + (($vendorOrders->currentPage() - 1) * $vendorOrders->perPage()) }}</td>
                                                        <td>{{ $key->fld_invno }}<br>{{ $key->fld_invdate }}</td>
                                                        <td>{{ $key->estimated_delivery_datetime ? \Carbon\Carbon::parse($key->estimated_delivery_datetime)->format('d/m/Y H:i A') : 'N/A' }}</td>
                                                        <td>{{ $key->fld_grand_total }}</td>
                                                        <td>
                                                            <strong>{{ $key->fld_name }}</strong><br>
                                                            {{ $key->customer_phone }}<br>
                                                            {{ $key->customer_email }}<br>
                                                            {{ $key->fld_address1 }}<br>
                                                            {{ $key->fld_city }} - {{ $key->fld_pinocde }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $currentStatus = $key->delivery_status ?? 'pending';
                                                                // Define status hierarchy
                                                                $statusHierarchy = [
                                                                    'pending' => 1,
                                                                    'packed' => 2,
                                                                    'shipped' => 3,
                                                                    'delivered' => 4,
                                                                    'cancelled' => 5
                                                                ];
                                                                $currentLevel = $statusHierarchy[$currentStatus] ?? 1;
                                                                
                                                                // Determine which statuses to disable
                                                                $isDisabled = function($status) use ($currentStatus, $statusHierarchy, $currentLevel) {
                                                                    // Final statuses cannot be changed
                                                                    if (in_array($currentStatus, ['delivered', 'cancelled'])) {
                                                                        return true;
                                                                    }
                                                                    // Can't go back to previous statuses
                                                                    $statusLevel = $statusHierarchy[$status] ?? 0;
                                                                    return $statusLevel < $currentLevel;
                                                                };
                                                            @endphp
                                                            <select class="form-control status-select"
                                                                    data-order-id="{{ $key->vendor_order_id }}"
                                                                    data-current-status="{{ $currentStatus }}"
                                                                    {{ in_array($currentStatus, ['delivered', 'cancelled']) ? 'disabled' : '' }}>
                                                                <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }} {{ $isDisabled('pending') ? 'disabled' : '' }}>Pending</option>
                                                                <option value="packed" {{ $currentStatus == 'packed' ? 'selected' : '' }} {{ $isDisabled('packed') ? 'disabled' : '' }}>Packed</option>
                                                                <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }} {{ $isDisabled('shipped') ? 'disabled' : '' }}>Shipped</option>
                                                                <option value="delivered" {{ $currentStatus == 'delivered' ? 'selected' : '' }} {{ $isDisabled('delivered') ? 'disabled' : '' }}>Delivered</option>
                                                                <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }} {{ $isDisabled('cancelled') ? 'disabled' : '' }}>Cancelled</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('vendor.order.details', $key->vendor_order_id) }}"
                                                               class="btn btn-xs btn-info"
                                                               data-toggle="tooltip"
                                                               title="Order Details">
                                                                <i class="fa fa-list"></i>
                                                            </a>
                                                            <button class="btn btn-xs btn-warning update-status-btn"
                                                                    data-order-id="{{ $key->vendor_order_id }}"
                                                                    data-toggle="tooltip"
                                                                    title="Update Order Status">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <a href="{{ route('vendor.order.download.invoice', $key->vendor_order_id) }}"
                                                               class="btn btn-xs btn-default"
                                                               data-toggle="tooltip"
                                                               title="Download Invoice"
                                                               target="_blank">
                                                               <i class="fa fa-file"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            <div class="alert alert-info">
                                                                <i class="fa fa-info-circle"></i> No orders found matching your criteria.
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    {{-- @if($vendorOrders->hasPages())
                                        <div class="panel-footer">
                                            <div class="pull-right">
                                                {{ $vendorOrders->appends(request()->query())->links() }}
                                            </div>
                                            <div class="pull-left">
                                                Showing {{ $vendorOrders->firstItem() }} to {{ $vendorOrders->lastItem() }} of {{ $vendorOrders->total() }} orders
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                            <!-- END DEFAULT DATATABLE -->

                            </div>
                        </div>
                </div>
                <!-- PAGE CONTENT WRAPPER -->
            </div>
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

@endsection
@push('scripts')

  <!-- JavaScript for order status management -->
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize date pickers
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

                // Handle status change via select dropdown
                $('.status-select').on('change', function() {
                    var $select = $(this);
                    
                    // Check if select is disabled
                    if ($select.prop('disabled')) {
                        // Reset to current status
                        var currentStatus = $select.data('current-status');
                        $select.val(currentStatus);
                        showNotification('error', 'This order status cannot be changed.');
                        return;
                    }
                    
                    var orderId = $select.data('order-id');
                    var newStatus = $select.val();
                    var currentStatus = $select.data('current-status');

                    if (newStatus === currentStatus) {
                        return; // No change
                    }
                    
                    // Check if selected option is disabled
                    var selectedOption = $select.find('option:selected');
                    if (selectedOption.prop('disabled')) {
                        // Reset to current status
                        $select.val(currentStatus);
                        showNotification('error', 'Cannot revert order status. You can only move forward in the order process.');
                        return;
                    }

                    updateOrderStatus(orderId, newStatus, $select);
                });

                // Handle status update via button click
                $('.update-status-btn').on('click', function() {
                    var orderId = $(this).data('order-id');
                    var $select = $('select[data-order-id="' + orderId + '"]');
                    
                    // Check if select is disabled
                    if ($select.prop('disabled')) {
                        showNotification('error', 'This order status cannot be changed.');
                        return;
                    }
                    
                    var newStatus = $select.val();
                    var currentStatus = $select.data('current-status');
                    
                    if (newStatus === currentStatus) {
                        showNotification('info', 'Order status is already set to ' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + '.');
                        return;
                    }
                    
                    // Check if selected option is disabled
                    var selectedOption = $select.find('option:selected');
                    if (selectedOption.prop('disabled')) {
                        showNotification('error', 'Cannot revert order status. You can only move forward in the order process.');
                        $select.val(currentStatus); // Reset to current
                        return;
                    }

                    updateOrderStatus(orderId, newStatus, $select);
                });

                function updateOrderStatus(orderId, status, $selectElement) {
                    if (confirm('Are you sure you want to update the order status?')) {
                        // Show loading state
                        var $updateBtn = $('button[data-order-id="' + orderId + '"]');
                        var $select = $selectElement || $('select[data-order-id="' + orderId + '"]');
                        
                        $updateBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                        $select.prop('disabled', true);

                        $.ajax({
                            url: '{{ route("vendor.order.update.status", ":id") }}'.replace(":id", orderId),
                            type: 'POST',
                            data: {
                                status: status,
                                _token: '{{ csrf_token() }}',
                                _method: 'PUT'
                            },
                            success: function(response) {
                                // Show success message
                                showNotification('success', 'Order status updated successfully!');

                                // Refresh the page to show updated data
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating status:', error);
                                
                                // Get error message from response
                                var errorMessage = 'Failed to update order status. Please try again.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                
                                showNotification('error', errorMessage);

                                // Re-enable buttons and select
                                $updateBtn.prop('disabled', false).html('<i class="fa fa-edit"></i>');
                                $select.prop('disabled', false);
                                
                                // Reset select to current status
                                var currentStatus = $select.data('current-status');
                                $select.val(currentStatus);
                            }
                        });
                    } else {
                        // Reset select to current status if user cancels
                        if ($selectElement) {
                            var currentStatus = $selectElement.data('current-status');
                            $selectElement.val(currentStatus);
                        }
                    }
                }

                function showNotification(type, message) {
                    // Simple notification system - you can replace this with your preferred notification library
                    var alertClass = 'alert-info';
                    if (type === 'success') {
                        alertClass = 'alert-success';
                    } else if (type === 'error') {
                        alertClass = 'alert-danger';
                    } else if (type === 'info') {
                        alertClass = 'alert-info';
                    } else if (type === 'warning') {
                        alertClass = 'alert-warning';
                    }
                    
                    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissable fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                        '<strong>' + type.charAt(0).toUpperCase() + type.slice(1) + '!</strong> ' + message +
                        '</div>';

                    // Remove existing alerts
                    $('.alert').remove();

                    // Add new alert
                    $('.page-title').after(alertHtml);

                    // Auto-hide after 5 seconds
                    setTimeout(function() {
                        $('.alert').fadeOut();
                    }, 5000);
                }

                // Initialize tooltips
                $('[data-toggle="tooltip"]').tooltip();

                // Update order count if status changes
                $('.status-select').on('change', function() {
                    var count = $('.status-select').length;
                    $('#order-count').text(count + ' Orders');
                });
            });
        </script>
@endpush
