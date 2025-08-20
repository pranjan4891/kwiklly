@extends('vendorpanel.include.main')
@section('content')

<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>                    
                    <li class="active">Dashboard</li>
                </ul>
                <!-- END BREADCRUMB -->                       
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    
                    <!-- START WIDGETS -->                    
                    <div class="row">
                        {{-- <div class="col-md-3">
                            
                            <!-- START WIDGET SLIDER -->
                            <div class="widget widget-default widget-carousel">
                                <div class="owl-carousel" id="owl-example">
                                    <div>                                    
                                        <div class="widget-title">Total Product</div>                                                                        
                                        <div class="widget-subtitle">27/08/2017 15:23</div>
                                        <!-- <div class="widget-int">$total_product</div> -->
                                        <div class="widget-int">40</div>
                                    </div>
                                    <div>                                    
                                        <div class="widget-title">Total Shipped</div>
                                        <div class="widget-subtitle">Order</div>
                                        <div class="widget-int">50</div>
                                        <!-- <div class="widget-int">$this->Mealsnmart_v_model->OrderStatusCount('Shipped')</div> -->
                                    </div>
                                    <div>                                    
                                        <div class="widget-title">Total Delivered</div>
                                        <div class="widget-subtitle">Order</div>
                                        <div class="widget-int">40</div>
                                        <!-- <div class="widget-int">$this->Mealsnmart_v_model->OrderStatusCount('Delivered')</div> -->
                                    </div>
                                    <div>                                    
                                        <div class="widget-title">Total Cancelled</div>
                                        <div class="widget-subtitle">Order</div>
                                        <div class="widget-int">5</div>
                                        <!-- <div class="widget-int">$this->Mealsnmart_v_model->OrderStatusCount('Cancelled')</div> -->
                                    </div>
									
									
                                </div>                            
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                             
                            </div>         
                            <!-- END WIDGET SLIDER -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET MESSAGES -->
                            <div class="widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-archive"></span>
                                </div>                             
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">$total_product</div> -->
                                    <div class="widget-int num-count">39</div>
                                    <div class="widget-title">Total Product</div>
                                    <!--<div class="widget-subtitle">In your account</div>-->
                                </div>      
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" onclick="location.href='base_url()Home/orderdetails/Pending';">
                                <div class="widget-item-left">
                                    <span class="fa fa-tag"></span>
                                </div>
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">$this->Mealsnmart_v_model->OrderStatusCount('Pending')</div> -->
                                    <div class="widget-int num-count">19</div>
                                    <div class="widget-title">Pending Order</div>
                                    <!--<div class="widget-subtitle">On your website</div>-->
                                </div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                            
                        </div> --}}
                        <div class="col-md-3">
                            
                            <!-- START WIDGET CLOCK -->
                            <div class="widget widget-danger widget-padding-sm">
                                <div class="widget-big-int plugin-clock">00:00</div>                            
                                <div class="widget-subtitle plugin-date">Loading...</div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                                <div class="widget-buttons widget-c3">
                                    <div class="col">
                                        <a href="#"><span class="fa fa-clock-o"></span></a>
                                    </div>
                                    <div class="col">
                                        <a href="#"><span class="fa fa-bell"></span></a>
                                    </div>
                                    <div class="col">
                                        <a href="#"><span class="fa fa-calendar"></span></a>
                                    </div>
                                </div>                            
                            </div>                        
                            <!-- END WIDGET CLOCK -->
                            
                        </div>
                    </div>
                    <!-- END WIDGETS --> 
                    
                    {{-- <div class="row">
                        
                        <div class="col-md-3">
                            
                            <!-- START WIDGET MESSAGES -->
                            <div class="widget widget-default widget-item-icon" onclick="location.href='base_url()vendor_admin/Home/orderdetails/Onhold';">
                                <div class="widget-item-left">
                                    <span class="fa fa-archive"></span>
                                </div>                             
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">$this->Mealsnmart_v_model->OrderStatusCount('Onhold')</div> -->
                                    <div class="widget-int num-count">14</div>
                                    <div class="widget-title">Onhold Order</div>
                                    <!--<div class="widget-subtitle">In your account</div>-->
                                </div>      
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" onclick="location.href='base_url()vendor_admin/Home/orderdetails/Shipped';">
                                <div class="widget-item-left">
                                    <span class="fa fa-tag"></span>
                                </div>
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">$this->Mealsnmart_v_model->OrderStatusCount('Shipped')</div> -->
                                    <div class="widget-int num-count">25</div>
                                    <div class="widget-title">Shipped Order</div>
                                    <div class="widget-subtitle">On your website</div>
                                </div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                            
                        </div>
                        
                        <div class="col-md-3">
                            
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" onclick="location.href='base_url()vendor_admin/Home/orderdetails/Delivered';">
                                <div class="widget-item-left">
                                    <span class="fa fa-tag"></span>
                                </div>
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">$this->Mealsnmart_v_model->OrderStatusCount('Delivered')</div> -->
                                    <div class="widget-int num-count">10</div>
                                    <div class="widget-title">Delivered Order</div>
                                    <!--<div class="widget-subtitle">On your website</div>-->
                                </div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" onclick="location.href='base_url()vendor_admin/Home/orderdetails/Cancelled';">
                                <div class="widget-item-left">
                                    <span class="fa fa-tag"></span>
                                </div>
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">$this->Mealsnmart_v_model->OrderStatusCount('Cancelled')</div> -->
                                    <div class="widget-int num-count">50</div>
                                    <div class="widget-title">Cancelled Order</div>
                                    <!--<div class="widget-subtitle">On your website</div>-->
                                </div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                            
                        </div>
                        
                    </div> --}}
					
					{{-- <div class="row">
                        <div class="col-md-3">
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" >
                                <div class="widget-item-left">
                                    <span class="fa fa-shopping-cart "></span>
                                </div>
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">₹ $this->Mealsnmart_v_model->TodayOrderSales()</div> -->
                                    <div class="widget-int num-count">₹ 100</div>
                                    <div class="widget-title">Today's Sale</div>
                                    <!--<div class="widget-subtitle">On your website</div>-->
                                </div>
                                                          
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                        </div>
						
						<div class="col-md-3">
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" >
                                <div class="widget-item-left">
                                    <span class="fa fa-shopping-cart "></span>
                                </div>
                                <div class="widget-data">
                                    <!-- <div class="widget-int num-count">₹ $this->Mealsnmart_v_model->TotalOrderSales()</div> -->
                                    <div class="widget-int num-count">₹ 100</div>
                                    <div class="widget-title">Total Sale</div>
                                    <!--<div class="widget-subtitle">On your website</div>-->
                                </div>
                                                          
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                        </div>
						
                     </div>
					 
					 
                    <div class="row"> --}}
                        <div class="col-md-12">
                            
                            <!-- START SALES BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3>Kwiklly Vendor Dashboard!</h3>                               
                                  </div>
                                <div class="panel-body">                                    
                                    <div class="row stacked">
                                        <div class="col-md-4">                                            
                                    <div class="panel-title-box">
                                        <div class="panel-title-box">
                                        <h3>Welcome ! Vendor - {{$vendor->business_name}} </h3>
                                        <h4 class="title"> Vendor Email - {{$vendor->email}} </h4>
                                        <h4 class="title"> Your Id  - {{$vendor->uuid}}  </h4>
                                    </div>
                                    </div>   
                                        </div>
<!--                                        <div class="col-md-8">
                                            <div id="dashboard-map-seles" style="width: 100%; height: 225px"></div>
                                        </div>-->
                                    </div>                                    
                                </div>
                            </div>
                            <!-- END SALES BLOCK -->
                            
                        </div>
                        
                    </div>                

                    
                    <!-- START DASHBOARD CHART -->
<!--                    <div class="block-full-width">
                        <div id="dashboard-chart" style="height: 250px; width: 100%; float: left;"></div>
                        <div class="chart-legend">
                            <div id="dashboard-legend"></div>
                        </div>                                                
                    </div>                    -->
                    <!-- END DASHBOARD CHART -->
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->                                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
@endsection