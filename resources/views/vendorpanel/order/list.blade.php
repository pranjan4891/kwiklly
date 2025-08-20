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
                                    <form class="cmxform form-horizontal tasi-form" method="get" action="vendor_admin/Home/result_orders">
                                        <div class="form-group">
                                            <label for="cname" class="control-label col-lg-2">Status</label>
                                            <div class="col-lg-5">
                                                <select name="status" class="form-control">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Onhold">Onhold</option>
                                                    <option value="Invoiced">Invoiced</option>
                                                    <option value="ReadyforPickup">ReadyforPickup</option>
                                                    <option value="Dispatched">Dispatched</option>
                                                    <option value="Shipped">Shipped</option>
                                                    <option value="Delivered">Delivered</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cname" class="control-label col-lg-2">Date From </label>
                                            <div class="col-lg-5">
                                                <input class=" form-control" id="datepicker" name="date_from" type="text" aria-required="true">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cname" class="control-label col-lg-2">Date To </label>
                                            <div class="col-lg-5">
                                                <input class=" form-control" id="date_to" name="date_to" type="text" aria-required="true">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <button class="btn btn-success" name="search" value="1" type="submit">Search</button>
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
                            </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table datatable vendor_orderview">
                                            <thead>
                                                <tr>
                                                    <th width="5%">S No.</th>
                                                    <th>Inv No.</th>
                                                    <th>Del. Date</th>
                                                    <th>Del. Time</th>
													<th>Del. Type</th>
													<th>Order Date-Time</th>
                                                    <th>Total</th>
                                                    <th>Cust. Details</th>
                                                    <th>Pay. Status</th>
                                                    <th>Pay. Mode</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php  
                                               if(!empty($order_details)){
                                                $i=1; $deldate = array();
                                                foreach ($order_details as $key) 
                                                {  ?>
                                                    <tr>
                                                    <td><?php echo $i++ ?></td>
                                                    <td><?php echo $key->fld_invno ?></td>
                                                    <td><?php //echo $key->fld_delivery_date; 
															$deldate  = explode(",",$key->fld_delivery_date);
															//if (date('d/m')==$deldate[1] || date('d/m')< $deldate[1])
															//	echo $key->fld_delivery_date;
															
																echo $deldate[1];
																
													?></td>
                                                    <td><?php echo $key->fld_delivery_time; ?></td>
													<td><?php if($deldate[0]=="Express-Delivery")
																echo $deldate[0];
															else 
																echo 'Normal-Delivery'; ?></td>
													<td><?php echo $key->fld_invdate; ?></td>
                                                    <td><?php echo $key->fld_grand_total; ?></td>
                                                    <td>
                                                        <?php echo $key->fld_name; ?><br>
                                                        <?php echo $key->fld_address1; ?> 
                                                        <?php echo $key->fld_city; ?> 
                                                        <?php echo $key->fld_pinocde; ?>
                                                    </td>
                                                    <td><?php echo $key->fld_order_status ?></td>
                                                    <td><?php echo $key->fld_payment_mode ?></td>
                                                    <td>
                                                        <a href='vendor_admin/Home/forderdetails-id=<?php //echo base64_encode($key->fld_id); ?>&vid=<?php echo base64_encode($key->fld_vid);?>'>
                                                            <i class="fa fa-list" data-toggle="tooltip" title="Order Details"></i>
                                                        </a>
                                                        <a href='vendor_admin/Home/update_order_status-id=<?php //echo base64_encode($key->fld_id); ?>'>
                                                            <i class="fa fa-edit" data-toggle="tooltip" title="Update Order Status"></i>
                                                        </a>
														<a href='vendor_admin/Home/downloadinvoice-id=<?php //echo base64_encode($key->fld_id); ?>'>
                                                            <i class="fa fa-file" data-toggle="tooltip" title="Download Invoice"></i>
                                                        </a>
                                                    </td>
                                                    </tr>
                                            <?php } } ?>
                                            <script>
                                                function doconfirm()
                                                {
                                                    job=confirm("Are you sure to delete permanently?");
                                                    if(job!=true)
                                                    {
                                                        return false;
                                                    }
                                                }
                                            </script>
                                                
                                            </tbody>
                                        </table>
                                    </div>
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