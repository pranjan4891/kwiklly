<?php 
if(isset($this->session->userdata['vendor_login']))
{
   $shop_name= $this->session->userdata['vendor_login']['shop_name'];
   $email= $this->session->userdata['vendor_login']['v_email'];
}
else 
{
   redirect(base_url().'vendor_admin/Home/index');
} 
?>
<body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">
            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="">MEALNMART</a>
                        <a href="<?php echo base_url('vendor_admin/Home/dashboard') ?>" class="x-navigation-control"></a>
                    </li>
                    <li class="xn-profile">
                        <a href="#" class="profile-mini">
						<?php if($detail[0]->v_business_logo==''){ ?>
                                        <img src="../../upload_img/vendor_reg/static_img/store.jpg" alt="Meal n mart" />
                                        <!--<img src="../../upload_img/vendor_reg/static_img/store.jpg" alt="" style="width:200px" class="br-radius">-->
                                        <?php }else{ ?>
                                        <img src="../<?php echo $detail[0]->v_business_logo ?>" alt="Meal n mart" />
                                        <?php } ?>
                            
                        </a>
                        <div class="profile">
                            <div class="profile-image">
							<?php if($detail[0]->v_business_logo==''){ ?>
                                        <img src="../../upload_img/vendor_reg/static_img/store.jpg" />
                                        <!--<img src="../../upload_img/vendor_reg/static_img/store.jpg" alt="" style="width:200px" class="br-radius">-->
                                        <?php }else{ ?>
                                        <img src="../../<?php echo $detail[0]->v_business_logo ?>" alt="" >
                                        <?php } ?>
                                
                            </div>
                            <div class="profile-data">
                                <div class="profile-data-name">Welcome ! Vendor - <?php echo $shop_name ?></div>
                                <div class="profile-data-title">Your Email  - <?php echo $email ?></div>
                            </div>
                            <div class="profile-controls">
                                <a href="<?php echo base_url('vendor_admin/Home/Profile') ?>" class="profile-control-left"><span class="fa fa-info"></span></a>
                                <a href="#" class="profile-control-right"><span class="fa fa-envelope"></span></a>
                            </div>
                        </div>                                                                        
                    </li>
                    <li class="xn-title"><a href="<?php echo base_url('vendor_admin/Home/dashboard') ?>"><span class="fa fa-dashboard"></span> <span class="xn-text">Dashboards</span></a></li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-pencil"></span> <span class="xn-text">Category</span></a>
                        <ul>                            
                            <li><a href="<?php echo base_url('vendor_admin/Home/Category') ?>"><span class="fa fa-list-ul"></span> Category</a></li>
                            <li><a href="<?php echo base_url('vendor_admin/Home/Subcategory') ?>"><span class="fa fa-list-alt"></span> Subcategory</a></li>                            
                        </ul>
                    </li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-files-o"></span> <span class="xn-text">Product</span></a>
                        <ul>
                            <li><a href="<?php echo base_url('vendor_admin/Home/Product') ?>"><span class="fa fa-image"></span> Add Product</a></li>
							<li><a href="<?php echo base_url('vendor_admin/Home/Custompizzaproduct') ?>"><span class="fa fa-image"></span> Add Custom Pizza Product</a></li>
                            <li><a href="<?php echo base_url('vendor_admin/Home/DisplayProduct') ?>"><span class="fa fa-file-text-o"></span> Display Product</a></li>
                            <li><a href="<?php echo base_url('vendor_admin/Home/search_product') ?>"><span class="fa fa-search"></span> Search Product</a></li>
                            <li><a href="<?php echo base_url('vendor_admin/Home/importproducts') ?>"><span class="fa fa-cogs"></span> Import Bulk Products</a></li>
							<li><a href="<?php echo base_url('vendor_admin/Home/importvariantproducts') ?>"><span class="fa fa-cogs"></span> Import Variant Bulk Products</a></li>
                        </ul> 
                    </li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Orders</span></a>                        
                        <ul>
                            <li><a href="<?php echo base_url('vendor_admin/Home/orderdetails'); ?>"><span class="fa fa-heart"></span> Orders List</a></li>                            
                            <li><a href="<?php echo base_url('vendor_admin/Home/orderreport'); ?>"><span class="fa fa-search"></span> Orders Search</a></li>
                        </ul>
                    </li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Manage Coupon</span></a>                        
                        <ul>
                            <li><a href="<?php echo base_url('vendor_admin/Home/coupondetails'); ?>"><span class="fa fa-heart"></span> Coupon</a></li>                            
                            <!--li><a href="<?php echo base_url('Home/orderreport'); ?>"><span class="fa fa-search"></span> Orders Search</a></li-->
                        </ul>
                    </li>                    
                  </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->
            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                
                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <!-- TOGGLE NAVIGATION -->
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
                    </li>
                    <!-- END TOGGLE NAVIGATION -->
                    <!-- SEARCH -->
                    <li class="xn-search">
                        <form role="form">
                            <input type="text" name="search" placeholder="Search..."/>
                        </form>
                    </li>   
                    <!-- END SEARCH -->                    
                    <!-- POWER OFF  -->
                    <li class="xn-icon-button pull-right last">
                        <a href="#"><span class="fa fa-power-off"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <li><a href="<?php echo base_url('vendor_admin/Home/Profile') ?>"><span class="fa fa-user"></span> Profile</a></li>
                            <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>
                        </ul>                        
                    </li> 
                    <!-- END POWER OFF -->                    
                    <!-- MESSAGES -->
                    <li class="xn-icon-button pull-right">
                        <a href="#"><span class="fa fa-comments"></span></a>
                        
                    </li>
                    <!-- END MESSAGES -->
                    <!-- TASKS -->
                    <li class="xn-icon-button pull-right">
                        <a href="#"><span class="fa fa-tasks"></span></a>
                        
                    </li>
                    <!-- END TASKS -->
                    <!-- LANG BAR -->
                    <li class="xn-icon-button pull-right">
                        <a href="#"><span class="flag flag-gb"></span></a>
                        <ul class="xn-drop-left xn-drop-white animated zoomIn">
                            <li><a href="#"><span class="flag flag-gb"></span> English</a></li>
<!--                            <li><a href="#"><span class="flag flag-de"></span> Deutsch</a></li>
                            <li><a href="#"><span class="flag flag-cn"></span> Chinese</a></li>-->
                        </ul>                        
                    </li> 
                    <!-- END LANG BAR -->
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->