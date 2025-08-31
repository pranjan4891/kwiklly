@extends('branch.includes.main')
@section('main')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --secondary: #858796;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .widget-panel {
            border-radius: 8px;
            padding: 20px;
            position: relative;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }

        .widget-panel:hover {
            transform: translateY(-5px);
        }

        .widget-panel i {
            font-size: 2.5rem;
            opacity: 0.7;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .widget-panel h2 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .page-title {
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 1px solid #e3e6f0;
        }

        .vendor_dash {
            float: right;
            font-size: 14px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 30px;
        }

        .card-body h3 {
            color: #4e73df;
            font-weight: 700;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            background-color: #4e73df;
            color: white;
            border: none;
        }

        .table td {
            vertical-align: middle;
        }

        .welcome-header {
            color: #5a5c69;
            font-weight: 700;
        }
        .card-title {
            font-weight: 600;
            color: #4e73df;
            padding: 8px 12px
        }
    </style>
<div class="wraper container-fluid py-4">
        <div class="page-title">
            <h3 class="welcome-header">
                Welcome! {{ $admin->name ?? 'Admin' }}
                <span class="vendor_dash">
                    <a href="{{route('admin.login')}}" class="btn btn-primary btn-sm" target="_blank">Admin Dashboard</a>
                </span>
            </h3>
        </div>

        <div class="row">
            <!-- Total Products -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #4e73df; color: white;">
                    <i class="fas fa-box"></i>
                    <h2 class="m-0 counter">{{ $totalproduct }}</h2>
                    <div><strong>Total Products</strong></div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #f6c23e; color: white;">
                    <i class="fas fa-clock"></i>
                    <h2 class="m-0 counter">{{ $orderPending }}</h2>
                    <div><strong>Pending Orders</strong></div>
                </div>
            </div>

            <!-- Confirmed Orders -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #4e73df; color: white;">
                    <i class="fas fa-check-circle"></i>
                    <h2 class="m-0 counter">{{ $orderComplete }}</h2>
                    <div><strong>Confirmed Orders</strong></div>
                </div>
            </div>

            <!-- Shipped Orders -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #1cc88a; color: white;">
                    <i class="fas fa-shipping-fast"></i>
                    <h2 class="m-0 counter">{{ $orderShipped }}</h2>
                    <div><strong>Shipped Orders</strong></div>
                </div>
            </div>

            <!-- Delivered Orders -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #36b9cc; color: white;">
                    <i class="fas fa-truck-loading"></i>
                    <h2 class="m-0 counter">{{ $orderDeliered }}</h2>
                    <div><strong>Delivered Orders</strong></div>
                </div>
            </div>

            <!-- Cancelled Orders -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #e74a3b; color: white;">
                    <i class="fas fa-times-circle"></i>
                    <h2 class="m-0 counter">{{ $orderCancelled }}</h2>
                    <div><strong>Cancelled Orders</strong></div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="widget-panel widget-style-2" style="background-color: #20c9a6; color: white;">
                    <i class="fas fa-shopping-cart"></i>
                    <h2 class="m-0 counter">{{ $cartItem }}</h2>
                    <div><strong>Cart Items</strong></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Last 5 Latest Products</h3>
                        <a href="{{ route('branch.products') }}" class="btn btn-sm btn-outline-primary">View More <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>

                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Vendor</th>
                                    <th>Category/Sub-Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse($products as $product)
                                <tr>

                                    <td>
                                        @if ($product->featureImage)
                                            <img src="{{ asset('public/' . $product->featureImage->feature_image) }}" alt="Image" style="width: 100px;">
                                        @else
                                            <em>No Image</em>
                                        @endif
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>
                                        @if($product->vendor_id)
                                            {{ $product->vendor->business_name ?? 'N/A' }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->category)
                                            {{ $product->category->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                        /
                                        @if($product->subcategory)
                                            {{ $product->subcategory->sub_cat_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active == 1)
                                            <span class="badge bg-success status-badge">Active</span>
                                        @else
                                            <span class="badge bg-secondary status-badge">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No products found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple counter animation for the statistics
        $(document).ready(function() {
            $('.counter').each(function () {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });
        });
    </script>

@endpush
