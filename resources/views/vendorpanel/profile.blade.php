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
      <!-- <div class="col-md-6">
         <div class="alert alert-warning" role="alert">
             <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
             <strong>Important!</strong> Main feature of this page is "Change photo" function. Press button "Change photo" and try to use this awesome feature.
         </div>
         </div> -->
      {{-- <div class="col-md-12">
         <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <strong>Important Note!</strong> First need to verified your "Email & Phone" Number than you can run your shop on this platform. Press button "Verify".
         </div>
      </div> --}}
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
      <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="panel panel-default">
            <div class="panel-body">
               <h3><span class="fa fa-cog"></span> Payments</h3>
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
                           <label for="minimum_order_value">Minimum Order Value</label>
                        </div>
                        <div class="col-6">
                           <input type="number" name="minimum_order_value" id="minimum_order_value" class="form-control" value="{{ old('minimum_order_value', $vendor->minimum_order_value) }}">
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
@endsection
