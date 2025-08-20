@extends('vendorpanel.include.main')
@section('content')

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Pages</a></li>
    <li><a href="#">Edit Profile</a></li>
    <li class="active"><?= @@$detail[0]->v_name; ?></li>
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
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <strong>Important Note!</strong> First need to verified your "Email & Phone" Number than you can run your shop on this platform. Press button "Verify".
            </div>
        </div>
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
            <div class="panel panel-default tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Vendor Detail</a></li>
                    <li><a href="#tab2" data-toggle="tab">Department</a></li>
                    <li><a href="#tab3" data-toggle="tab">Settings</a></li>
                    <li><a href="#tab4" data-toggle="tab">Store Timing</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane panel-body active" id="tab1">
                        <div class="panel panel-default form-horizontal">
                            <div class="panel-body">
                                <h3><span class="fa fa-info-circle"></span> Shop Name : {{$vendor->business_name??'' }}</h3>
                                <p>Description : {{$vendor->business_desc??'' }}</p>
                            </div>
                            <div class="panel-body form-group-separated">
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
                                    <label class="col-md-4 col-xs-5 control-label">Tan Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base">
                                        <?php if (@$detail[0]->v_tan_img !== '') { ?>
                                            <a href="../../<?php echo @$detail[0]->v_tan_img ?>" target='_blank'>
                                                <img src="../../<?php echo @$detail[0]->v_tan_img ?>" style="width: 100px">
                                            </a>
                                        <?php } else {
                                            echo "no image | ";
                                        }
                                        echo @$detail[0]->v_no_tan; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">CIN</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_cin_img !== '') { ?>

                                            <a href="../../<?php echo @$detail[0]->v_cin_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_cin_img ?>" style="width: 100px"></a>

                                        <?php } else {
                                                                                        echo "no image | ";
                                                                                    }
                                                                                    echo @$detail[0]->v_no_cin;  ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Business Name (Business Detail)</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_b_name_b_detail ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Business PAN</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_business_pan ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">PAN Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_pan_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_pan_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_pan_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address Proof</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_address_proof ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address Proof Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_address_proof_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_address_proof_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_address_proof_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Cancalled cheque Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_cancle_cheque_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_cancle_cheque_img ?>" target='_blank'>
                                                <img src="../../<?php echo @$detail[0]->v_cancle_cheque_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Bank City</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_bank_city ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Branch</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_bank_branck ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Business Name (Personal Detail)</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_personal_buss_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Personal PAN</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_personal_pan ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">PAN Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_personal_pan_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_personal_pan_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_personal_pan_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address Proof</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_address_proof ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address Proof Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_address_proof_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_address_proof_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_address_proof_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Cancalled cheque Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_cancle_cheque_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_cancle_cheque_img ?>" target='_blank'>
                                                <img src="../../<?php echo @$detail[0]->v_cancle_cheque_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Bank City</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_bank_city ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Branch</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_bank_branck ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Business Name (Personal Detail)</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_personal_buss_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Personal PAN</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_personal_pan ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">PAN Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_personal_pan_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_personal_pan_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_personal_pan_img ?>" style="width: 100px"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address Proof</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_personal_addr_proof ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address Proof Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_personal_addr_proof_img == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_personal_addr_proof_img ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_personal_addr_proof_img ?>" style="width: 100px;"></a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Cancalled cheque Image</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php if (@$detail[0]->v_personal_cancle_cheque == '') {
                                                                                        echo 'no image'; ?>
                                        <?php } else { ?>
                                            <a href="../../<?php echo @$detail[0]->v_personal_cancle_cheque ?>" target='_blank'><img src="../../<?php echo @$detail[0]->v_personal_cancle_cheque ?>" style="width: 100px;"></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Account Holder Name</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_acc_holder_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Account Number</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_acc_number ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">IFSC Code</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_ifsc_code ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Bank Name</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_bank_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Branch</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_branch_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">City</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_city_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">State</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_state_name ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Description</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_description ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address 1</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_reg_address1 ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Address 2</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_reg_address2 ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">State/Province</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_state_province_reg ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">City</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_city_reg ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Shop Area</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_area_reg ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Postal Code</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_postal_code_reg ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-xs-5 control-label">Admin Status</label>
                                    <div class="col-md-8 col-xs-7 line-height-base"><?php echo @$detail[0]->v_admin_status ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane panel-body" id="tab2">
                        <p>Feel free to contact us for any issues you might have with our products.</p>
                        <div class="row">
                            <h1>Department</h1>
                            <?php if (!empty($category)) {
                                foreach ($category as $value) { ?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default p-0  m-t-20">
                                            <div class="panel-body p-0">
                                                <div class="list-group no-border mail-list">
                                                    <a href="#" class="list-group-item active"><i class="fa fa-star text-warning"></i><b><?php echo $value->v_cat_name ?></b></a>
                                                    <?php if (isset($value->children)) {
                                                        foreach ($value->children as $child) {
                                                    ?>
                                                            <a href="#" class="list-group-item"><?php echo $child->v_subcat_name ?></a>
                                                    <?php }
                                                    }  ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                    <div class="tab-pane panel-body" id="tab3">
                        <!--<p>Feel free to contact us for any issues you might have with our products.</p>-->
                        <form role="form" action="vendor_admin/Home/UpdateProfile" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Display Name</label>
                                <input type="text" value="<?php echo @$detail[0]->v_display_name ?>" name="dname" class="form-control" placeholder="Display name" />
                                <input type="hidden" value="<?php echo @$detail[0]->v_id ?>" name="v_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Business Category</label>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    @php
                                    $selectedCategories = explode(',', $vendor->v_business_cat ?? '');
                                    @endphp

                                    @foreach ($categories as $category)
                                    @php
                                    $isChecked = in_array($category->id, $selectedCategories);
                                    @endphp
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="categoryCheckbox{{ $category->id }}"
                                            name="buss_cat[]"
                                            value="{{ $category->id }}"
                                            {{ $isChecked ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="categoryCheckbox{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                            <!--                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea class="form-control" placeholder="Your message" rows="3"></textarea>
                                        </div>-->
                            <div class="form-group">
                                <label for="OrderValue">Minimum Order Value</label>
                                <input type="number" value="<?php echo @$detail[0]->fld_minimum_order_value ?>" name="minimum_order_value" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="FullName">Business Description</label>
                                <textarea style="height: 125px;" class="form-control" name="bdesc"><?php echo @$detail[0]->v_business_desc ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="FullName">Store Open Time</label>
                                <select name="open_time" class="form-control" required="">
                                    <option value="">Select Open Time Slot</option>
                                    <?php
                                    //  $qty = $this->db->where('sos_status','1')->get('store_opening_slots');
                                    // if($qty ->num_rows() >0)
                                    // {
                                    //     foreach($qty ->result() as $rowt)
                                    //     { 
                                    ?>
                                    <option <?php //if(@$detail[0]->fld_open_time == $rowt->sos_id) { echo "selected='selected'";}
                                            ?> value="<?php //echo $rowt->sos_id;
                                                        ?>"><?php //echo $rowt->sos_open_close;
                                                            ?></option>
                                    <?php //} }
                                    ?>
                                </select>
                                <label for="FullName">Store Closed Time</label>
                                <select name="closed_time" class="form-control" required="">
                                    <option value="">Select Store Closed Time</option>
                                    <?php
                                    //  $qty1 =$this->db->where('sos_status','1')->get('store_opening_slots');
                                    // if($qty1 ->num_rows() >0)
                                    // {
                                    //     foreach($qty1 ->result() as $rowt1)
                                    //     {
                                    ?>
                                    <option <? php // if(@$detail[0]->fld_close_time == $rowt1->sos_id) { echo "selected='selected'";}
                                            ?> value="<? php // echo $rowt1->sos_id;
                                                        ?>"><?php //echo $rowt1->sos_open_close;
                                                            ?></option>
                                    <?php // } }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="FullName">Service Offered </label>
                                <select name="service_offered" class="form-control" required="">
                                    <option <?php if (@$detail[0]->v_service_offered == "") {
                                                echo "selected='selected'";
                                            } ?> value="">Select Service Offered</option>
                                    <option <?php if (@$detail[0]->v_service_offered == "0") {
                                                echo "selected='selected'";
                                            } ?> value="0">Pickup</option>
                                    <option <?php if (@$detail[0]->v_service_offered == "1") {
                                                echo "selected='selected'";
                                            } ?> value="1">Delivery</option>
                                    <option <?php if (@$detail[0]->v_service_offered == "2") {
                                                echo "selected='selected'";
                                            } ?> value="2">Both</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="FullName">Business Logo</label>
                                <input type="file" name="buslogo" class="form-control">
                                <input type="hidden" value="<?php echo @$detail[0]->v_business_logo ?>" name="business_logo" class="form-control">
                                <?php if (@$detail[0]->v_business_logo == '') {

                                    echo "";
                                } else { ?>

                                    <img src="../../<?php echo @$detail[0]->v_business_logo ?>" style="width: 100px">

                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="FullName">Business Name</label>
                                <input type="text" value="<?php echo @$detail[0]->v_business_name ?>" name="busname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="Email">GSTIN provisional(ID)</label>
                                <input type="text" value="<?php echo @$detail[0]->v_gstin ?>" name="gstin" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="Username">TAN</label>
                                <input type="file" name="tan" class="form-control">
                                <input type="hidden" name="tan" value="<?php echo @$detail[0]->v_tan_img ?>" class="form-control">
                                <?php if (@$detail[0]->v_tan_img == '') {

                                    echo "";
                                } else { ?>

                                    <img src="../../<?php echo @$detail[0]->v_tan_img ?>" style="width: 100px">

                                <?php } ?>

                            </div>

                            <div class="form-group">

                                <label for="Username">CIN</label>

                                <input type="file" name="cin" class="form-control">

                                <input type="hidden" name="cin" value="<?php echo @$detail[0]->v_cin_img ?>" class="form-control">

                                <?php if (@$detail[0]->v_cin_img == '') {

                                    echo "";
                                } else { ?>

                                    <img src="../../<?php echo @$detail[0]->v_cin_img ?>" style="width: 100px">

                                <?php } ?>

                            </div>

                            <div class="form-group">

                                <label>Business Name (Business Detail)</label>

                                <input type="text" name="v_b_detail" value="<?php echo @$detail[0]->v_b_name_b_detail ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Business PAN</label>

                                <input type="text" name="buss_pan" value="<?php echo @$detail[0]->v_business_pan ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>PAN Image</label>

                                <input type="file" name="pan_img" class="form-control">

                                <input type="hidden" name="pan_img" value="<?php echo @$detail[0]->v_pan_img ?>" class="form-control">

                                <img src="../../<?php echo @$detail[0]->v_pan_img ?>" style="width: 100px">

                            </div>

                            <div class="form-group">

                                <label>Address Proof</label>

                                <input type="text" name="add_proof" value="<?php echo @$detail[0]->v_address_proof ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Address Proof Image</label>

                                <input type="file" name="add_img" class="form-control">

                                <input type="hidden" name="add_img" value="<?php echo @$detail[0]->v_address_proof_img ?>" class="form-control">

                                <img src="../../<?php echo @$detail[0]->v_address_proof_img ?>" style="width: 100px">

                            </div>

                            <div class="form-group">

                                <label>Cancalled cheque</label>

                                <input type="file" name="cancle_cheque_img" class="form-control">

                                <input type="hidden" name="cancle_cheque_img" value="<?php echo @$detail[0]->v_cancle_cheque_img ?>" class="form-control">

                                <img src="../../<?php echo @$detail[0]->v_cancle_cheque_img ?>" style="width: 100px">

                            </div>

                            <div class="form-group">

                                <label>Bank City</label>

                                <input type="text" name="back_city" value="<?php echo @$detail[0]->v_bank_city ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Branch</label>

                                <input type="text" name="branch" value="<?php echo @$detail[0]->v_bank_branck ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Business Name (Personal Detail)</label>

                                <input type="text" name="bus_name" value="<?php echo @$detail[0]->v_personal_buss_name ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Personal PAN</label>

                                <input type="text" name="p_pan" value="<?php echo @$detail[0]->v_personal_pan ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>PAN Image</label>

                                <input type="file" name="personal_pan_img" class="form-control">

                                <input type="hidden" name="personal_pan_img" value="<?php echo @$detail[0]->v_personal_pan_img ?>" class="form-control">

                                <img src="../../<?php echo @$detail[0]->v_personal_pan_img ?>" style="width: 100px">

                            </div>

                            <div class="form-group">

                                <label>Address Proof</label>

                                <input type="text" name="addr_proof" value="<?php echo @$detail[0]->v_personal_addr_proof ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Address Proof Image</label>

                                <input type="file" name="proof_img" class="form-control">

                                <input type="hidden" name="proof_img" value="<?php echo @$detail[0]->v_personal_addr_proof_img ?>" class="form-control">

                                <img src="../../<?php echo @$detail[0]->v_personal_addr_proof_img ?>" style="width: 100px">

                            </div>

                            <div class="form-group">

                                <label>Cancalled cheque</label>

                                <input type="file" name="cancalled_cheque" value="<?php echo @$detail[0]->v_personal_cancle_cheque ?>" class="form-control">



                                <input type="hidden" name="cancalled_cheque" value="<?php echo @$detail[0]->v_personal_cancle_cheque ?>" class="form-control">

                                <img src="../../<?php echo @$detail[0]->v_personal_cancle_cheque ?>" style="width: 100px">



                            </div>

                            <div class="form-group">

                                <label>Account Holder Name</label>

                                <input type="text" name="acc_hol_name" value="<?php echo @$detail[0]->v_acc_holder_name ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Account Number</label>

                                <input type="text" name="acc_nub" value="<?php echo @$detail[0]->v_acc_number ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>IFSC Code</label>

                                <input type="text" name="ifsc_code" value="<?php echo @$detail[0]->v_ifsc_code ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Bank Name</label>

                                <input type="text" name="back_name" value="<?php echo @$detail[0]->v_bank_name ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Branch</label>

                                <input type="text" name="bbranch" value="<?php echo @$detail[0]->v_branch_name ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>City</label>

                                <input type="text" name="city" value="<?php echo @$detail[0]->v_city_name ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>State</label>

                                <input type="text" name="state" value="<?php echo @$detail[0]->v_state_name ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Description</label>

                                <textarea style="height: 125px;" name="ddesc" class="form-control"><?php echo @$detail[0]->v_description ?></textarea>

                            </div>

                            <div class="form-group">

                                <label>Address 1</label>

                                <input type="text" name="add1" value="<?php echo @$detail[0]->v_reg_address1 ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Address 1</label>

                                <input type="text" name="add2" value="<?php echo @$detail[0]->v_reg_address2 ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>State/Province</label>

                                <input type="text" name="state_pro" value="<?php echo @$detail[0]->v_state_province_reg ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>City</label>

                                <input type="text" name="c_city" value="<?php echo @$detail[0]->v_city_reg ?>" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Postal Code</label>

                                <input type="text" name="post_code" value="<?php echo @$detail[0]->v_postal_code_reg ?>" class="form-control">

                            </div>

                            <div class="form-group">
                                <label>Shop Area</label>
                                <input type="text" name="shop_area" value="<?php echo @$detail[0]->v_area_reg ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-xs-5">
                                    <button class="btn btn-primary pull-right btn-lg">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane panel-body" id="tab4">
                        <!--<p>Feel free to contact us for any issues you might have with our products.</p>-->
                        <form role="form" action="vendor_admin/Home/UpdateStoretime" method="post" enctype="multipart/form-data">

                            <?php $days = array();
                            for ($i = 0; $i < 7; $i++) {
                                $j = $i;
                                $days[$i] = jddayofweek($i, 1);
                            ?>
                                <div class="form-group">
                                    <?php echo $days[$i]; ?>
                                    <input type="hidden" name="day_name_<?= $j + 1 ?>" value="<?php echo $days[$i]; ?>" />
                                    <?php if (@@$storetime[$i]->id) { ?>
                                        <input type="hidden" name="day_id_<?= $j + 1 ?>" value="<?php echo @$storetime[$i]->id; ?>" />
                                    <?php } ?>
                                    <input type="radio" name="day_oc_<?= $j + 1 ?>" value="1" <?php if (@$storetime[$i]->status == 1) echo "checked"; ?> required="required">
                                    <label for="1">Open</label>
                                    <input type="radio" name="day_oc_<?= $j + 1 ?>" value="0" <?php if (@$storetime[$i]->status == 0) echo "checked"; ?> required="required">
                                    <label for="0">Close</label>


                                    <select name="open_time_<?= $j + 1 ?>" class="form-control">
                                        <option value="">Select Open Time Slot</option>
                                        <?php
                                        //    $qty = $this->db->where('sos_status','1')->get('store_opening_slots');
                                        //     if($qty ->num_rows() >0)
                                        //     {
                                        //         foreach($qty ->result() as $rowt)
                                        //         {
                                        ?>
                                        <option <?php //if(@$storetime[$i]->toTime == $rowt->sos_open_close) { echo "selected='selected'";}
                                                ?> value="<? php //echo $rowt->sos_open_close;
                                                            ?>"><?php //echo $rowt->sos_open_close;
                                                                ?></option>
                                        <? php // } }
                                        ?>
                                    </select>

                                    <select name="closed_time_<?= $j + 1 ?>" class="form-control">
                                        <option value="">Select Store Closed Time</option>
                                        <?php
                                        //    $qty1 = $this->db->where('sos_status','1')->get('store_opening_slots');
                                        //     if($qty1 ->num_rows() >0)
                                        //     {
                                        //         foreach($qty1 ->result() as $rowt1)
                                        //         {
                                        ?>
                                        <option <?php //if(@$storetime[$i]->endTime == $rowt1->sos_open_close) { echo "selected='selected'";}
                                                ?> value="<?php // echo $rowt1->sos_open_close;
                                                            ?>"><?php //echo $rowt1->sos_open_close;
                                                                ?></option>
                                        <?php // }  }
                                        ?>
                                    </select>
                                </div>

                            <?php } // for loop end for weekdays
                            ?>
                            <div class="form-group">
                                <div class="col-md-12 col-xs-5">
                                    <button class="btn btn-primary btn-rounded pull-center">Save</button>
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
            url: '{{ route("vendor.updateImage") }}', // Define this route in your web.php
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
                } else {
                    alert('Upload failed');
                }
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