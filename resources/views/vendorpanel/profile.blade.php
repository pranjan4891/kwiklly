@extends('vendorpanel.include.main')
@section('content')
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
   <li><a href="#">Home</a></li>
   <li><a href="#">Pages</a></li>
   <li><a href="#">Edit Profile</a></li>
   <li class="active"><?= @@$vendor->v_name; ?></li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">
   <h2><span class="fa fa-cogs"></span> Edit Profile</h2>
</div>
<!-- END PAGE TITLE -->
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
   <div class="row">

   </div>
   <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12">
         <form action="#" class="form-horizontal">
            <div class="panel panel-default">
               <div class="panel-body form-group-separated">
                  <div class="form-group">
                     <label class="col-md-5 col-xs-5 control-label">#ID</label>
                     <div class="col-md-7 col-xs-7">
                        <input type="text" value="{{$vendor->uuid??''}}" class="form-control" disabled />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-5 col-xs-5 control-label">Registration Date</label>
                     <div class="col-md-7 col-xs-7">
                        <input type="text" value="{{$vendor->created_at??''}}" class="form-control" disabled />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-5 col-xs-5 control-label">Business Name</label>
                     <div class="col-md-7 col-xs-7">
                        <input type="text" value="{{$vendor->business_name??''}}" class="form-control" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-5 col-xs-5 control-label">E-mail</label>
                     <div class="col-md-7 col-xs-7">
                        <input type="text" value="{{$vendor->email??''}}" class="form-control" />
                     </div>
                  </div>
                  <!-- <div class="form-group">
                     <div class="col-md-12 col-xs-12">
                         <a href="#" class="btn btn-danger btn-block btn-rounded" data-toggle="modal" data-target="#modal_change_password">Change password</a>
                     </div>
                     </div> -->
               </div>
            </div>
         </form>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12">
         <form id="vendorImageForm" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            <div class="panel panel-default">
               <div class="panel-body form-group-separated">
                  {{-- Business Logo --}}
                  <div class="form-group">
                     <div class="col-md-5 col-xs-5">
                        <label class="control-label">Business Logo</label>
                        <input type="file" name="business_logo" id="business_logo" class="form-control" />
                     </div>
                     <div class="col-md-7 col-xs-7">
                        <img id="preview_logo"
                           src="{{ asset('public/' . ($vendor->business_logo ?? 'uploads/no-image.jpg')) }}"
                           width="160" class="mt-2" />
                     </div>
                  </div>
                  {{-- Business Banner --}}
                  <div class="form-group">
                     <div class="col-md-5 col-xs-5">
                        <label class="control-label">Business Banner</label>
                        <input type="file" name="business_banner" id="business_banner" class="form-control" />
                     </div>
                     <div class="col-md-7 col-xs-7">
                        <img id="preview_banner"
                           src="{{ asset('public/' . ($vendor->business_banner ?? 'uploads/no-image.jpg')) }}"
                           width="160" class="mt-2" />
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
      {{-- <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="panel panel-default">
            <div class="panel-body">
               <h3><span class="fa fa-cog"></span> Delivery Charges</h3>
               <p>Please choose payment type</p>
               <div id="result"></div>
            </div>
            <div class="panel-body form-horizontal form-group-separated">
               <?php if (!empty($paymode)) {
                  $checked1 = '';
                  $checked2 = '';
                  $checked3 = '';
                  foreach ($paymode as $paymod) {
                      if (($paymod->pm_name == 'Cash On Delivery') && ($paymod->vp_status == '1')) {
                          $checked1 = 'checked';
                      }
                      if ($paymod->pm_name == 'Online Payments' && $paymod->vp_status == '1') {
                          $checked2 = 'checked';
                      }
                      if ($paymod->pm_name == 'Net Banking' && $paymod->vp_status == '1') {
                          $checked3 = 'checked';
                      }
                      //echo $paymod->pm_name."<br>";

                  ?>
               <?php }
                  } else {
                      $checked1 = '';
                      $checked2 = '';
                      $checked3 = '';
                  }  ?>
               <div class="form-group">
                  <label class="col-md-6 col-xs-6 control-label paymod">Cash On Delivery</label>
                  <div class="col-md-6 col-xs-6">
                     <label class="switch">
                     <input type="checkbox" <?= $checked1; ?> name="paym" value="1" />
                     <span></span>
                     </label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-md-6 col-xs-6 control-label paymod">Online Payments</label>
                  <div class="col-md-6 col-xs-6">
                     <label class="switch">
                     <input type="checkbox" <?= $checked2; ?> name="paym" value="2" />
                     <span></span>
                     </label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-md-6 col-xs-6 control-label paymod">Net Banking</label>
                  <div class="col-md-6 col-xs-6">
                     <label class="switch">
                     <input type="checkbox" <?= $checked3; ?> name="paym" value="3" />
                     <span></span>
                     </label>
                  </div>
               </div>
            </div>
         </div>
      </div> --}}
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3><span class="fa fa-cog"></span> Delivery Charges</h3>
                    <p>Please enter delivery charge</p>
                </div>

                <form id="deliveryChargesForm" class="form-horizontal form-group-separated">
                    @csrf

                    <div class="panel-body">
                        <!-- Delivery Charges -->
                        <div class="form-group">
                            <label class="col-md-6 col-xs-6 control-label paymod">Charge (Rs.)</label>
                            <div class="col-md-6 col-xs-6">
                                <input type="number" name="delivery_charge" value="{{ $deliveryCharge->delivery_charge ?? '' }}" class="form-control" placeholder="Enter charges" required>
                            </div>
                        </div>

                        <!-- Delivery Range -->
                        <div class="form-group">
                            <label class="col-md-6 col-xs-6 control-label paymod">Range (km)</label>
                            <div class="col-md-6 col-xs-6">
                                <input type="number" name="delivery_range" value="1" class="form-control" placeholder="Enter range" readonly>
                            </div>
                        </div>

                        <!-- Status (Readonly) -->
                        <div class="form-group">
                            <label class="col-md-6 col-xs-6 control-label paymod">Status</label>
                            <div class="col-md-6 col-xs-6">
                                @php
                                    $statusText = 'Pending';
                                    if($deliveryCharge && isset($deliveryCharge->status)) {
                                        switch ($deliveryCharge->status) {
                                            case 1: $statusText = 'Approved'; break;
                                            case 2: $statusText = 'Rejected'; break;
                                            default: $statusText = 'Pending';
                                        }
                                    }
                                @endphp
                                <input type="text" class="form-control {{ ($deliveryCharge && $deliveryCharge->status == 1) ? 'text-success' : (($deliveryCharge && $deliveryCharge->status == 2) ? 'text-danger' : 'text-warning') }}" value="{{ $statusText }}" readonly style="font-weight: bold;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

   </div>
   <!--------------------------------------------------------------------------------  -->
   <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        @if (session('success'))
         <div class="alert alert-success">
            {{ session('success') }}
         </div>
         @endif
         @if (session('error'))
         <div class="alert alert-danger">
            {{ session('error') }}
         </div>

        @endif
         <div class="panel panel-default tabs">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#tab1" data-toggle="tab">Vendor Detail</a></li>

               <li><a href="#tab2" data-toggle="tab">Settings</a></li>
               <li><a href="#tab3" data-toggle="tab">Store Timing</a></li>
               <li><a href="#tab4" data-toggle="tab">Location Share</a></li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane panel-body active" id="tab1">
                  <div class="panel panel-default form-horizontal">
                     <div class="panel-body form-group-separated">
                        <div class="panel-body">
                           <h3><span class="fa fa-info-circle"></span> Shop Name : {{$vendor->business_name??'' }}</h3>
                           <p>Description : {{$vendor->business_desc??'' }}</p>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Admin Status</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$vendor->v_admin_status ?></div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Email</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->email??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Mobile Number</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->phone??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">GSTIN provisional(ID)</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->gstin??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Landmark</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->landmark??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Business Address</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->business_address??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Account Holder Name</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->account_holder_name??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Account Number</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->account_number??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">IFSC Code</label>
                           <div class="col-md-8 col-xs-7 line-height-base">{{$vendor->ifsc_code??'' }}</div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Bank Name</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$vendor->v_bank_name ?></div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Bank City</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$vendor->v_bank_city ?></div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Branch</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$vendor->v_bank_branck ?></div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Cancel Cheque Image</label>
                           <div class="col-md-8 col-xs-7 line-height-base">
                              @if (!empty($vendor->cancel_cheque_image))
                              <a href="{{ asset('public/'.$vendor->cancel_cheque_image) }}" target="_blank">
                              <img src="{{ asset('public/'.$vendor->cancel_cheque_image) }}" style="width: 100px">
                              </a>
                              @else
                              no image |
                              @endif
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">PAN Image</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$vendor->v_pan_img == '') {
                              echo 'no image'; ?>
                              <?php } else { ?>
                              <a href="../../<?php echo @$vendor->v_pan_img ?>" target='_blank'><img src="../../<?php echo @$vendor->v_pan_img ?>" style="width: 100px"></a>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Address Proof</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$vendor->v_address_proof ?></div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 col-xs-5 control-label">Address Proof Image</label>
                           <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$vendor->v_address_proof_img == '') {
                              echo 'no image'; ?>
                              <?php } else { ?>
                              <a href="../../<?php echo @$vendor->v_address_proof_img ?>" target='_blank'><img src="../../<?php echo @$vendor->v_address_proof_img ?>" style="width: 100px"></a>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="tab-pane panel-body" id="tab2">
                  <!--<p>Feel free to contact us for any issues you might have with our products.</p>-->
                  <!-----Start UpdateProfile Form-------------------->
                  <form action="{{ route('vendor.update.profile') }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="row">
                        {{-- Display Name --}}
                        <div class="col-3">
                           <label for="display_name">Display Name</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="display_name" id="display_name" class="form-control" value="{{ old('display_name', $vendor->display_name) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Display Name --}}
                        <div class="col-3">
                           <input type="checkbox" class="form-check-input" name="is_home_request" id="is_home_request" value="1" {{ $vendor->is_home_request == 1 ? 'checked' : '' }}>

                           <label for="display_name">Request for Display Home Page</label>
                        </div>
                        <div class="col-6">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Business Categories --}}
                        <div class="col-3">
                           <label for="business_category">Business Categories</label>
                        </div>
                        <div class="col-6">
                           @php
                           $selectedCategories = explode(',', $vendor->business_category ?? '');
                           @endphp
                           @foreach ($categories as $category)
                           <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="cat{{ $category->id }}" name="business_category[]" value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                              <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                           </div>
                           @endforeach
                        </div>
                     </div>
                     <div class="row">
                        {{-- Minimum Order Value --}}
                        <div class="col-3">
                           <label for="minimum_order_value">Minimum Order Value Free Delivery</label>
                        </div>
                        <div class="col-6">
                           <input type="number" name="minimum_order_value" id="minimum_order_value" class="form-control" value="{{ old('minimum_order_value', $vendor->minimum_order_value) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Minimum Order For Free Cook --}}
                        <div class="col-3">
                           <label for="delivery_range">Minimum Order For Gift </label>
                        </div>
                        <div class="col-6">
                           <input type="number" class="form-control" value="{{  $vendor->minimum_order_for_cook }}" name="minimum_order_for_cook" id="minimum_order_for_cook">
                        </div>dy_text
                     </div>
                     <div class="row">
                        {{-- Minimum Order For Free Cook --}}
                        <div class="col-3">
                           <label for="delivery_range">Dynamic Gift Text </label>
                        </div>
                        <div class="col-6">
                           <input type="text" class="form-control" value="{{  $vendor->dy_text }}" name="dy_text" id="dy_text">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="delivery_charge">Delivery Charge (Rs.)</label>
                        </div>
                        <div class="col-6">
                           <input type="number" class="form-control" value="{{  $vendor->delivery_charge }}" readonly>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="delivery_range">Delivery Range (KM)</label>
                        </div>
                        <div class="col-6">
                           <input type="number" class="form-control" value="{{  $vendor->delivery_range }}" readonly>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="delivery_time">Delivery Charge Status</label>
                        </div>
                        <div class="col-6">
                           @if ($vendor->delivery_charge_status == 1)
                                <input type="text" class="form-control" value="Active" readonly>
                           @else
                                <input type="text" class="form-control" value="Inactive" readonly>
                           @endif
                        </div>
                     </div>
                     <div class="row">
                        {{-- Business Description --}}
                        <div class="col-3">
                           <label for="business_description">Business Description</label>
                        </div>
                        <div class="col-6">
                           <textarea name="business_description" id="business_description" class="form-control" rows="4">{{ old('business_description', $vendor->business_description) }}</textarea>
                        </div>
                     </div>


                     <div class="row">
                        {{-- Service Offered --}}
                        <div class="col-3">
                           <label for="service_offered">Service Offered</label>
                        </div>
                        <div class="col-6">
                           <select name="service_offered" id="service_offered" class="form-control">
                              <option value="">Select</option>
                              <option value="0" {{ $vendor->service_offered == '0' ? 'selected' : '' }}>Pickup</option>
                              <option value="1" {{ $vendor->service_offered == '1' ? 'selected' : '' }}>Delivery</option>
                              <option value="2" {{ $vendor->service_offered == '2' ? 'selected' : '' }}>Both</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        {{-- Business Name --}}
                        <div class="col-3">
                           <label for="business_name">Business Name</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('business_name', $vendor->business_name) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- GSTIN --}}
                        <div class="col-3">
                           <label for="gstin">GSTIN</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="gstin" id="gstin" class="form-control" value="{{ old('gstin', $vendor->gstin) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Landmark --}}
                        <div class="col-3">
                           <label for="landmark">Landmark</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="landmark" id="landmark" class="form-control" value="{{ old('landmark', $vendor->landmark) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Business Address --}}
                        <div class="col-3">
                           <label for="business_address">Business Address</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="business_address" id="business_address" class="form-control" value="{{ old('business_address', $vendor->business_address) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Email --}}
                        <div class="col-3">
                           <label for="email">Email</label>
                        </div>
                        <div class="col-6">
                           <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $vendor->email) }}">
                        </div>
                     </div>
                     <div class="row">
                        {{-- Mobile Number --}}
                        <div class="col-3">
                           <label for="phone">Mobile Number</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}">
                        </div>
                     </div>
                     {{-- Bank Details --}}
                     <div class="row">
                        <div class="col-3">
                           <label for="account_holder_name">Account Holder Name</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="account_holder_name" id="account_holder_name" class="form-control" value="{{ old('account_holder_name', $vendor->account_holder_name) }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="account_number">Account Number</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number', $vendor->account_number) }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="ifsc_code">IFSC Code</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="{{ old('ifsc_code', $vendor->ifsc_code) }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="bank_name">Bank Name</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name', $vendor->bank_name) }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="bank_city">Bank City</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="bank_city" id="bank_city" class="form-control" value="{{ old('bank_city', $vendor->bank_city) }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <label for="bank_branch">Branch</label>
                        </div>
                        <div class="col-6">
                           <input type="text" name="bank_branch" id="bank_branch" class="form-control" value="{{ old('bank_branch', $vendor->bank_branch) }}">
                        </div>
                     </div>
                     {{-- File Uploads --}}
                     @php
                     $fileFields = [
                     ['label' => 'Cancelled Cheque', 'name' => 'cancel_cheque_image', 'path' => $vendor->cancel_cheque_image],
                     ['label' => 'PAN Image', 'name' => 'pan_image', 'path' => $vendor->pan_image],
                     ['label' => 'Address Proof', 'name' => 'address_proof_image', 'path' => $vendor->address_proof_image],
                     ['label' => 'TAN', 'name' => 'tan_image', 'path' => $vendor->tan_image],
                     ['label' => 'CIN', 'name' => 'cin_image', 'path' => $vendor->cin_image],
                     ['label' => 'Personal PAN', 'name' => 'personal_pan_image', 'path' => $vendor->personal_pan_image],
                     ['label' => 'Personal Address Proof', 'name' => 'personal_address_proof_image', 'path' => $vendor->personal_address_proof_image]
                     ];
                     @endphp
                     @foreach ($fileFields as $file)
                     <div class="row">
                        <div class="col-3">
                           <label for="{{ $file['name'] }}">{{ $file['label'] }}</label>
                        </div>
                        <div class="col-6">
                           <input type="file" name="{{ $file['name'] }}" id="{{ $file['name'] }}" class="form-control">
                           @if (!empty($file['path']))
                           <div class="mt-2">
                              <img src="{{ asset('public/' . $file['path']) }}" alt="{{ $file['label'] }}" width="100">
                           </div>
                           @endif
                        </div>
                     </div>
                     @endforeach
                     {{-- Submit Button --}}
                     <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                           <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                     </div>
                  </form>
                  <!-----End UpdateProfile FOrm---------------------->
               </div>
                <div class="tab-pane panel-body" id="tab3">
                    <form action="{{ route('vendor.update.store.time') }}" method="POST">
@csrf
                        @php
                            $days = [];
                            for ($i = 0; $i < 7; $i++) {
                                $days[$i] = jddayofweek($i, 1);
                            }
                        @endphp

                        @foreach ($days as $index => $day)
                            @php
                                $dayData = $storetime[$day] ?? null;
                            @endphp

                            <div class="form-group">
                                <label>{{ $day }}</label>

                                <input type="hidden" name="day_name_{{ $index + 1 }}" value="{{ $day }}">

                                @if (!empty($dayData['id']))
                                    <input type="hidden" name="day_id_{{ $index + 1 }}" value="{{ $dayData['id'] }}">
                                @endif

                                {{-- Open/Close Radio --}}
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="day_oc_{{ $index + 1 }}" value="1"
                                            {{ old("day_oc_" . ($index + 1), $dayData['status'] ?? '') == 1 ? 'checked' : '' }} required> Open
                                    </label>
                                    <label>
                                        <input type="radio" name="day_oc_{{ $index + 1 }}" value="0"
                                            {{ old("day_oc_" . ($index + 1), $dayData['status'] ?? '') == 0 ? 'checked' : '' }} required> Close
                                    </label>
                                </div>

                                {{-- Open Time --}}
                                <select name="open_time_{{ $index + 1 }}" class="form-control">
                                    <option value="">Select Open Time Slot</option>
                                    @foreach ($timeSlots as $slot)
                                        <option value="{{ $slot->slot_time }}"
                                            {{ old("open_time_" . ($index + 1), $dayData['startTime'] ?? '') == $slot->slot_time ? 'selected' : '' }}>
                                            {{ $slot->slot_time }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Closed Time --}}
                                <select name="closed_time_{{ $index + 1 }}" class="form-control">
                                    <option value="">Select Store Closed Time</option>
                                    @foreach ($timeSlots as $slot)
                                        <option value="{{ $slot->slot_time }}"
                                            {{ old("closed_time_" . ($index + 1), $dayData['endTime'] ?? '') == $slot->slot_time ? 'selected' : '' }}>
                                            {{ $slot->slot_time }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach


                        <div class="form-group">
                            <div class="col-md-12 col-xs-5">
                                <button type="submit" class="btn btn-warning">Update Time Schedule</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane panel-body" id="tab4">
                    <div class="alert alert-warning">
                        <strong>Info!</strong> Share your location with delivery boy
                        <button type="button" class="btn btn-info btn-rounded" onclick="openLocationShareModal()">Share Location</button>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Location Contact Information</h3>
                        </div>
                        <div class="panel-body">
                            <div id="locationContactsContainer">
                                <p>Loading contacts...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script>
   $(document).ready(function() {
       $('input[type="checkbox"][name="paym"]').click(function() {
           var paymod = $(this).val();
           //console.log(paymod);
           $.ajax({
               url: "vendor_admin/Home/UpdatePaymod",
               method: "POST",
               data: {
                   paymod: paymod
               },
               success: function(data) {
                   $('#result').html(data);
                   window.location.reload(true);

               }
           });
       });
   });
</script>
<script>
  $(document).ready(function () {
    function uploadImage(fieldName) {
        let formData = new FormData();
        let file = $('#' + fieldName)[0].files[0];
        formData.append(fieldName, file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route("vendor.updateImage") }}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === true) {
                    if (fieldName === 'business_logo') {
                        $('#preview_logo').attr('src', response.path);
                    } else {
                        $('#preview_banner').attr('src', response.path);
                    }
                    alert('Image updated successfully');
                } else {
                    alert('Upload failed: ' + response.message);
                }
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    }

    $('#business_logo').change(function () {
        uploadImage('business_logo');
    });

    $('#business_banner').change(function () {
        uploadImage('business_banner');
    });
});
</script>
<script>
    $(document).ready(function () {
        $('#deliveryChargesForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('vendor.delivery-charges.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status) {
                        alert(response.message);

                        // Update form fields with latest DB values
                        $('input[name="delivery_charge"]').val(response.data.delivery_charge);
                        $('input[name="delivery_range"]').val(response.data.delivery_range);

                        // Update status text + color
                        let statusField = $('#statusField'); // make sure input has id="statusField"
                        statusField.val(response.data.status_text);

                        // Remove old classes
                        statusField.removeClass('text-success text-danger text-warning');

                        // Add new class based on status
                        if (response.data.status == 1) {
                            statusField.addClass('text-success');
                        } else if (response.data.status == 2) {
                            statusField.addClass('text-danger');
                        } else {
                            statusField.addClass('text-warning');
                        }
                    } else {
                        alert('Something went wrong!');
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    for (let key in errors) {
                        errorMsg += errors[key][0] + '\n';
                    }
                    alert(errorMsg);
                }
            });
        });
    });
</script>


<!-- Location Share Modal -->
<div class="modal fade" id="locationShareModal" tabindex="-1" aria-labelledby="locationShareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationShareModalLabel">Set Location Share Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="locationShareForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Contact Method</label>
                        <select class="form-select" id="contactMethod" name="contact_method" required>
                            <option value="">Select Contact Method</option>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" id="contactLabel">Contact Details</label>
                        <input type="text" class="form-control" id="contactDetail" name="contact_detail" placeholder="Enter email/mobile/WhatsApp number" required>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">This contact information will be used to share your location with delivery personnel.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveLocationContact()">Save Contact</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openLocationShareModal() {
        $('#locationShareModal').modal('show');
    }

    function saveLocationContact() {
        const formData = new FormData(document.getElementById('locationShareForm'));

        $.ajax({
            url: '{{ route("vendor.save.location.contact") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Contact saved successfully!');
                    $('#locationShareModal').modal('hide');
                    loadLocationContacts();
                } else {
                    alert('Failed to save contact: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error saving contact: ' + xhr.responseText);
            }
        });
    }

    function loadLocationContacts() {
        $.ajax({
            url: '{{ route("vendor.get.location.contacts") }}',
            method: 'GET',
            success: function(response) {
                let html = '';
                if (response.contacts && response.contacts.length > 0) {
                    html += '<div class="row">';
                    response.contacts.forEach(function(contact) {
                        html += `
                            <div class="col-md-4 mb-3">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <h5>${contact.contact_method.toUpperCase()}</h5>
                                        <p>${contact.contact_detail}</p>
                                        <button class="btn btn-danger btn-sm" onclick="deleteLocationContact(${contact.id})">Delete</button>
                                        <button class="btn btn-primary btn-sm" onclick="shareLocation(${contact.id})">Share Location</button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html = '<p>No contacts added yet. Add a contact to share your location with delivery personnel.</p>';
                }
                $('#locationContactsContainer').html(html);
            },
            error: function(xhr) {
                $('#locationContactsContainer').html('<p>Error loading contacts.</p>');
            }
        });
    }

    function deleteLocationContact(contactId) {
        if (confirm('Are you sure you want to delete this contact?')) {
            $.ajax({
                url: `{{ url('vendor/location-contact') }}/${contactId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Contact deleted successfully!');
                        loadLocationContacts();
                    } else {
                        alert('Failed to delete contact');
                    }
                },
                error: function(xhr) {
                    alert('Error deleting contact');
                }
            });
        }
    }

    function shareLocation(contactId) {
        // Create a hidden form and submit it to the controller
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('vendor/share-location') }}/${contactId}`;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }

    // Load contacts when page loads
    $(document).ready(function() {
        loadLocationContacts();
    });
</script>
@endsection
