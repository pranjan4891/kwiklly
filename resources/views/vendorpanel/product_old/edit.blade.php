<?php 
   if(isset($this->session->userdata['vendor_login'])){
   $shop_name = $this->session->userdata['vendor_login']['shop_name'];
   $email     = $this->session->userdata['vendor_login']['v_email'];
   $v_id      = $this->session->userdata['vendor_login']['id'];
    } else { redirect(base_url().'vendor_admin/Home/index'); } ?>                

<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>                    
                    <li><a href="#">Pages</a></li>
                    <li class="active">Edit Product</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE TITLE -->
<!--                <div class="page-title">                    
                    <h2><span class="fa fa-arrow-circle-o-left"></span> Edit Product</h2>
                </div>-->
                <div class="page-title"> 
                    <h3 class="title">Edit Product
                    </h3> 
                    <?php if ($this->session->flashdata('msg1')):?>

            <span class="form_error"><?php echo $this->session->flashdata('msg1'); ?></span>

            <?php endif; ?>
                </div>                
                <!-- END PAGE TITLE -->                

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">Enter Product Details</h3></div>
                            <div class="panel-body">
                            <form class="form-horizontal" role="form" action="<?php echo base_url('vendor_admin/Home/UpdateProduct') ?>" method="post"  enctype="multipart/form-data">                                    

                <div class="form-group">
                    <label class="col-md-2 control-label">Product Name</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="product_name" value="<?php echo $product[0]->v_product_name ?>">
                        <input type="hidden" class="form-control" id="product_id" name="product_id" value="<?php echo base64_encode($product[0]->v_product_id) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="example-email">Category</label>
                    <div class="col-md-10">
                        <select class="form-control" name="cat_id" onchange="get_subcat(this.value)">
                            <!-- <option value="<?php echo $product[0]->v_cat_id ?>"><?php echo $product[0]->v_cat_name ?></option> -->
                            <?php foreach ($cat as $value) { ?>
                               <option <?php if($value->v_cat_id == $product[0]->v_cat_id) { echo "selected='selected'";}?> value="<?php echo $value->v_cat_id ?>"><?php echo $value->v_cat_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-md-2 control-label" for="example-email">Sub category</label>

                    <div class="col-md-10">

                        <select class="form-control" name="subcat_id" id="subcat_name">

                            <!-- <option value="<?php echo $product[0]->v_subcat_id ?>"><?php echo $product[0]->v_subcat_name ?></option> -->

                            <?php foreach ($subcat as $value) { ?>

                                <option <?php if($value->v_subcat_id == $product[0]->v_subcat_id) { echo "selected='selected'";}?> value="<?php echo $value->v_subcat_id ?>"><?php echo $value->v_subcat_name ?></option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <!-- <div class="form-group">

                    <label class="col-md-2 control-label">Product Type</label>

                    <div class="col-md-10">

                            <input type="radio" name="type" value="Veg" <?php if (isset($product[0]->v_product_type) && $product[0]->v_product_type=="Veg"){ echo "checked=''";}?>> Veg<br>

                            <input type="radio" name="type" value="Non Veg" <?php if (isset($product[0]->v_product_type) && $product[0]->v_product_type=="Non Veg") {echo "checked=''"; echo "string";}?>>  Non Veg<br>

                    </div>

                </div> -->

                <div class="form-group">

                    <label class="col-md-2 control-label">Product Actual Price <span style="color: red">In Rs.</span></label>

                    <div class="col-md-10">

                        <input type="text" class="form-control" name="prod_act_p" id="prod_act_p" value="<?php echo $product[0]->v_product_cost; ?>">

                    </div>

                </div> 

                <div class="form-group">

                    <label class="col-md-2 control-label">Product Selling Price <span style="color: red">In Rs.</span></label>

                    <div class="col-md-10">

                        <input type="text" class="form-control" name="prod_sell_p" id="prod_sell_p" value="<?php echo $product[0]->v_product_selling_price ?>">

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-md-2 control-label">Price Save  <span style="color: red">In Rs.</span></label>

                    <div class="col-md-10">

                        <input type="text" class="form-control" name="price_rs" id="price_rs" value="<?php echo $product[0]->v_product_save_in_rs ?>" readonly="">

                    </div>

                </div> 

                <div class="form-group">

                    <label class="col-md-2 control-label">Price Save  <span style="color: red">In %</span></label>

                    <div class="col-md-10">

                        <input type="text" class="form-control" name="price_percent" id="price_percent" value="<?php echo $product[0]->v_product_save_percent ?>" readonly="">

                    </div>

                </div>                                    

                <div class="form-group">
                    <label class="col-md-2 control-label">Product Quantity <br><span style="color: red"> Eg : 500g/1L/1Kg/1piece </span></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="prod_qty" value="<?php echo $product[0]->v_product_quantity ?>" required="">
                        <span style="color: red">Eg : In gram/kg/litre/ml/per piece </span>
                    </div>
                    
                    <label class="col-md-2 control-label">Product Weight</label>
                    <div class="col-md-4">
                        <select name="prod_weight" id="prod_weight" class="form-control" required="">
                        <option <?php if($product[0]->v_product_weight == '') { echo "selected='selected'";}?> value="">Select*</option>     
                        <option <?php if($product[0]->v_product_weight == 'gram') { echo "selected='selected'";}?> value="gram">gram</option>     
                        <option <?php if($product[0]->v_product_weight == 'kg') { echo "selected='selected'";}?> value="kg">kg</option>     
                        <option <?php if($product[0]->v_product_weight == 'liter') { echo "selected='selected'";}?> value="liter">litre</option>     
                        <option <?php if($product[0]->v_product_weight == 'ml') { echo "selected='selected'";}?> value="ml">ml</option>     
                        <option <?php if($product[0]->v_product_weight == 'per piece') { echo "selected='selected'";}?> value="per piece">per piece</option>
                        </select>
                    </div>
                </div>      

                <div class="form-group">
                <label class="col-md-2 control-label">CGST</label>
                <div class="col-md-10">
                        <input type="text" class="form-control" autocomplete="off" value="<?php echo $product[0]->cgst;?>" name="cgst" id="cgst">
                        </div>
                        </div>
                    <div class="form-group">
                    <label class="col-md-2 control-label">SGST</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" autocomplete="off" value="<?php echo $product[0]->sgst;?>" name="sgst" id="sgst" >
                    </div>
                </div>                              

                <div class="form-group">
                    <label class="col-md-2 control-label">Product Description</label>
                    <div class="col-md-10">
                        <textarea class="summernote" class="form-control" rows="5" name="prod_desc"><?php echo $product[0]->v_product_desc ?></textarea>
                    </div>
                </div>

                <!-- <div class="form-group">

                    <label class="col-md-2 control-label">Product Specification</label>

                    <div class="col-md-10">

                        <textarea class="form-control" rows="5" name="prod_speci"><?php echo $product[0]->v_product_specification ?></textarea>

                    </div>

                </div> -->

                <div class="form-group">

                    <label class="col-md-2 control-label">Product Information</label>

                    <div class="col-md-10">

                        <textarea class="form-control summernote" rows="5" name="prod_info"><?php echo $product[0]->v_product_info ?></textarea>

                    </div>

                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Product Disclaimer</label>
                <div class="col-md-10">
                    <textarea class="form-control summernote" rows="5" name="prod_disclaimer"><?php echo $product[0]->v_product_disclaimer; ?></textarea>
                </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label">Select Product Image</label>

                    <div class="col-sm-10">

                        <select class="select2" data-placeholder="Choose Product Image..." name="prod_img_o">

                            <option value="">&nbsp;</option>

                            <?php foreach ($p_img as $key ) { ?>

                            <option value="<?php echo $key->pimg_i_img ?>"><?php echo $key->pimg_pname ?></option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label">Product Image</label>

                    <div class="col-sm-10">

                        <input type="file" class="form-control" name="prod_img1">

                        <input type="hidden" class="form-control" name="prod_img1" value="<?php echo $product[0]->v_product_image ?>">

                        <span style="color: red">Min dimention of images should be 500 * 500 Or in the same ratio</span><br>

                        <?php if ($product[0]->v_product_image) { ?>

                        <img src="../../<?php echo $product[0]->v_product_image;  ?>" style="width:200px">

                    <?php  }else{

                        echo "no image";

                    } ?>

                    </div>
                  </div>  

                <!-- <div class="form-group">

                    <label class="col-sm-2 control-label">Product Image</label>

                    <div class="col-sm-10">

                        <input type="file" class="form-control" name="prod_img2">

                        <input type="hidden" class="form-control" name="prod_img2" value="<?php echo $product[0]->v_product_img2 ?>">

                        <?php if ($product[0]->v_product_img2) { ?>

                        <img src="<?php echo base_url($product[0]->v_product_img2 );  ?>" style="width:200px">

                        <?php  }else{

                        echo "no image";

                    } ?>

                    </div>

                </div>  

                <div class="form-group">

                    <label class="col-sm-2 control-label">Product Image</label>

                    <div class="col-sm-10">

                        <input type="file" class="form-control" name="prod_img3">

                        <input type="hidden" class="form-control" name="prod_img3" value="<?php echo $product[0]->v_product_img3 ?>">

                        <?php if ($product[0]->v_product_img3) { ?>

                        <img src="<?php echo base_url($product[0]->v_product_img3 );  ?>" style="width:200px">

                        <?php  }else{

                        echo "no image";

                        } ?>

                    </div>

                </div> 

                <div class="form-group">

                    <label class="col-sm-2 control-label">Product Image</label>

                    <div class="col-sm-10">

                        <input type="file" class="form-control" name="prod_img4">

                        <input type="hidden" class="form-control" name="prod_img4" value="<?php echo $product[0]->v_product_img4 ?>">

                        <?php if ($product[0]->v_product_img4) { ?>

                        <img src="<?php echo base_url(); ?><?php echo $product[0]->v_product_img4  ?>" style="width: 200px">

                        <?php  }else{

                        echo "no image";

                        } ?>

                    </div>

                </div>   -->

                <div class="form-group">

                <label class="col-sm-2 control-label">Best offers</label>

                <div class="col-sm-4">

                    <select class="form-control" name="best_offers">
                        <option <?php if($product[0]->best_offers == "0") { echo "selected='selected'";}?> value="0">Yes</option>
                        <option <?php if($product[0]->best_offers == "1") { echo "selected='selected'";}?> value="1">No</option>
                    </select>

                </div>
                <label class="col-sm-2 control-label">Top Selling</label>

                <div class="col-sm-4">

                    <select class="form-control" name="top_selling">
                        <option <?php if($product[0]->top_selling == "0") { echo "selected='selected'";}?> value="0">Yes</option>
                        <option <?php if($product[0]->top_selling == "1") { echo "selected='selected'";}?> value="1">No</option>
                    </select>

                </div>


                </div>       
                <div class="col-lg-offset-2 col-lg-10">

                    <button class="btn btn-success" type="submit">Save</button>

                    <button class="btn btn-default" type="button">Cancel</button>

                </div>



            </form>
                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div> <!-- col -->
                </div>                                
                    
                </div>
                <!-- PAGE CONTENT WRAPPER -->                                
            </div>    
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
        
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
<script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150,
            });
        });
</script>      
<script type="text/javascript">
    $(function() 
    {
        $("#prod_act_p, #prod_sell_p").on("keydown keyup", sum);
        function sum() {

            //$("#sum").val(Number($("#prod_act_p").val()) + Number($("#prod_sell_p").val()));

            $("#price_rs").val(Number(($("#prod_act_p").val()) - Number($("#prod_sell_p").val())).toFixed(2));
            //$("#price_percent").val((Number($("#price_rs").val()) / Number($("#prod_act_p").val()))*100).toFixed(2);
            $("#price_percent").val(((Number($("#price_rs").val()) / Number($("#prod_act_p").val()))*100).toFixed(2));
            // var abc = Number($("#price_rs").val());
            // var def = Number($("#prod_act_p").val());
            // var ghi = (abc / def)*100;
            // var jkl = parseFloat(ghi.toFixed(2));
            // $("#price_percent").val(jkl);
        }
    });
</script>

<script type="text/javascript">
    function get_subcat(cat_id)
    {
        //alert(cat)
        $.ajax({
        url: '<?php echo base_url();?>vendor_admin/Home/get_subcat/' + cat_id ,
        success: function(response)
        {
            //alert(response);
            jQuery('#subcat_name').html(response);
        }
        });
    }
</script>
        
        