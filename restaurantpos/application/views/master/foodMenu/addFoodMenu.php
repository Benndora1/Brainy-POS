<style type="text/css">
    .aligning{
        width: auto; float:left
    } 
    .label_aligning{
        float: left; padding: 5px 0px 0px 3px;
    } 
</style>
<script>
    var ingredient_id_container = [];

    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();

        $(document).on('keydown', '.integerchk', function(e){
            /*$('.integerchk').keydown(function(e) {*/
            var keys = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
            keys == 8 || 
                keys == 9 ||
                keys == 13 ||
                keys == 46 ||
                keys == 110 ||
                keys == 86 ||
                keys == 190 ||
                (keys >= 35 && keys <= 40) ||
                (keys >= 48 && keys <= 57) ||
                (keys >= 96 && keys <= 105));
        });

        var suffix = 
<?php
if (isset($food_menu_ingredients)) {
    echo count($food_menu_ingredients);
} else {
    echo 0;
}
?>;  
     
        var tab_index = 6;

        $('#ingredient_id').change(function(){
            var ingredient_details=$('#ingredient_id').val();
            if (ingredient_details != '') {
                var ingredient_details_array = ingredient_details.split('|');

                /*for(var i=1; i <= ingredient_id_container.length; i++){ 
                   if(ingredient_details_array[0] == ingredient_id_container[i]){
                    swal('Ingredient already remains in cart, you can change the consumption value')
                    return false;
                   }
                } */
                var index = ingredient_id_container.indexOf(ingredient_details_array[0]);  

                if (index > -1) {
                    swal({
                        title: "<?php echo lang('alert'); ?>!",
                        text: "<?php echo lang('ingredient_already_remain'); ?>",
                        confirmButtonText:'<?php echo lang('ok'); ?>',
                        confirmButtonColor: '#3c8dbc'
                    });
                    $('#ingredient_id').val('').change();
                    return false;
                } 

                suffix++;
                tab_index++; 

                var cart_row = '<tr class="rowCount" id="row_' + suffix + '">'+
                    '<td style="width: 12%; padding-left: 10px;"><p>'+suffix+'</p></td>'+
                    '<td style="width: 23%"><span style="padding-bottom: 5px;">'+ingredient_details_array[1]+'</span></td>'+
                    '<input type="hidden" id="ingredient_id_' + suffix + '" name="ingredient_id[]" value="' + ingredient_details_array[0] + '"/>'+
                    '<td style="width: 30%"><input type="text" tabindex="'+ tab_index +'" id="consumption_' + suffix + '" name="consumption[]" onfocus="this.select();"  class="form-control integerchk aligning" style="width: 85%;" placeholder="<?php echo lang('consumption'); ?>"/><span class="label_aligning">' + ingredient_details_array[2] + '</span></td>'+
                    '<td style="width: 17%"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="return deleter(' + suffix + ',' + ingredient_details_array[0] +');" ><i class="fa fa-trash"></i> </a></td>'+
                    '</tr>';  

                $('#ingredient_consumption_table tbody').append(cart_row);    

                ingredient_id_container.push(ingredient_details_array[0]);
                /*updateRowNo();*/
                $('#ingredient_id').val('').change();
                updateRowNo();
            }
        }); 
        
         
        // Validate form
        $("#food_menu_form").submit(function(){
            var name = $("#name").val();
            var category_id = $("#category_id").val();
            var veg_item =$("#veg_item").val();
            var beverage_item = $("#beverage_item").val();
            var bar_item = $("#bar_item").val();
            var description = $("#description").val();
            var sale_price = $("#sale_price").val(); 
            var ingredientCount = $("#form-table tbody tr").length;
            var error = false;  


            if(name==""){ 
                $("#name_err_msg").text("<?php echo lang('name_field_required'); ?>");
                $(".name_err_msg_contnr").show(200);
                error = true;
            } 

            /*            if(name.length>18){ 
                $("#name_err_msg").text("The Name field cannot exceed 18 characters in length.");
                $(".name_err_msg_contnr").show(200);
                error = true;
            }*/

            if(category_id==""){ 
                $("#category_id_err_msg").text("<?php echo lang('category_field_required'); ?>");
                $(".category_err_msg_contnr").show(200);
                error = true;
            } 
            
            
            if(veg_item==""){ 
                $("#veg_item_err_msg").text("<?php echo lang('veg_item_field_required'); ?>");
                $(".veg_item_err_msg_contnr").show(200);
                error = true;
            } 
            
            if(beverage_item==""){ 
                $("#beverage_item_err_msg").text("<?php echo lang('beverage_item_field_required'); ?>");
                $(".beverage_item_err_msg_contnr").show(200);
                error = true;
            } 
            
            if(bar_item==""){ 
                $("#bar_item_err_msg").text("<?php echo lang('bar_item_field_required'); ?>");
                $(".bar_item_err_msg_contnr").show(200);
                error = true;
            }  

            if(description.length>200){ 
                $("#description_err_msg").text("<?php echo lang('description_field_can_not_exceed'); ?>");
                $(".description_err_msg_contnr").show(200);
                error = true;
            }

            if(sale_price==""){ 
                $("#sale_price_err_msg").text("<?php echo lang('sale_price_field_required'); ?>");
                $(".sale_price_err_msg_contnr").show(200);
                error = true;
            }  

            /*if(ingredient_id_container.length == 0){ 
                $("#ingredient_id_err_msg").text("<?php echo lang('at_least_ingredient'); ?>");
                $(".ingredient_err_msg_contnr").show(200);
                error = true;
            } */
  
            for(var n = 1; n <= ingredient_id_container.length+1; n++){  
                var ingredient_id = $.trim($("#ingredient_id_"+n).val()); 
                var consumption = $.trim($("#consumption_"+n).val());   
                
                if(ingredient_id.length > 0){
                    if(consumption == '' || isNaN(consumption)){ 
                        $("#consumption_"+n).css({"border-color":"red"}).show(200);
                        error = true;
                    }  
                }
            }  

             
            /*if(description.length>200){
                $("#description").css({"border-color":"red"});
                $("#description").next("span").show(200).delay(5000).hide(200,function(){
                    $("#description").css({"border-color":"#ccc"});
                });
                error = true;
            } 
            for(var n=0;n<=suffix-1;n++){
                if(deletedRow.indexOf(n)<0){
                    var consumption= $.trim($("#consumption_"+n).val());
                    if(consumption==''||isNaN(consumption)){
                        $("#consumption_"+n).css({"border-color":"red"});
                        $("#consumption_"+n).next("span").show(200).delay(5000).hide(200,function(){
                            $("#consumption_"+n).css({"border-color":"#ccc"});
                        });
                        error = true;
                    } 
                }
            } */ 

            if(error == true){
                return false;
            } 
        });



    }) 

    function deleter(suffix, ingredient_id){
        swal({
            title: "<?php echo lang('alert'); ?>",
            text: "<?php echo lang('are_you_sure'); ?>?",
            confirmButtonColor: '#3c8dbc',
            cancelButtonText:'<?php echo lang('cancel'); ?>',
            confirmButtonText:'<?php echo lang('ok'); ?>',
            showCancelButton: true
        }, function() {
            $("#row_"+suffix).remove();
            var ingredient_id_container_new = [];

            for(var i = 0; i < ingredient_id_container.length; i++){
                if(ingredient_id_container[i] != ingredient_id){
                    ingredient_id_container_new.push(ingredient_id_container[i]);
                }
            }

            ingredient_id_container = ingredient_id_container_new;

            updateRowNo();
        });

    } 

    function updateRowNo(){ 
        var numRows=$("#ingredient_consumption_table tbody tr").length; 
        for(var r=0;r<numRows;r++){
            $("#ingredient_consumption_table tbody tr").eq(r).find("td:first p").text(r+1);
        }
    }
 
</script>

<style type="text/css">
    .required_star{
        color: #dd4b39;
    }

    .radio_button_problem{
        margin-bottom: 19px;
    }
    .foodMenuCartInfo{
        border: 2px solid #3c8dbc;
        padding: 15px;
        border-radius: 5px;
        color: #3c8dbc;
        font-size: 14px;
        margin-top: 0px;
        text-align: justify;
    }
    .foodMenuCartNotice{
        border: 2px solid red;
        padding: 15px;
        border-radius: 5px;
        color: red;
        font-size: 14px;
        margin-top: 0px;
        text-align: justify;
    }
    .cart_container{
        /* border: 1px solid black;*/
    }
    .cart_header{ 
        background-color: #ecf0f5;  
        padding: 5px 0px;
        margin-bottom: 5px;
    }
    .ch_content{
        font-weight: bold;
    }
    .custom_form_control{
        border-radius: 0;
        box-shadow: none;
        border-color: #d2d6de;  
        width: 80%;
        height: 26px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        margin: 0px 3px 7px 0px;
    }
    .center_positition{
        text-align: center !important;
    }
    .error-msg{
        display:none;
    }
</style> 

<section class="content-header">
    <h1>
        <?php echo lang('add_food_menu'); ?>
    </h1>  
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <?php echo form_open(base_url() . 'Master/addEditFoodMenu', $arrayName = array('id' => 'food_menu_form', 'enctype' => 'multipart/form-data')) ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('name'); ?>" value="<?php echo set_value('name'); ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('name'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg name_err_msg_contnr" style="padding: 5px !important;">
                                <p id="name_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('code'); ?></label>
                                <input tabindex="6" type="text" name="code" class="form-control" placeholder="<?php echo lang('code'); ?>" value="<?= $autoCode ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('category'); ?> <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" id="category_id" name="category_id" style="width: 100%;">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($categories as $ctry) { ?>
                                        <option value="<?php echo $ctry->id ?>" <?php echo set_select('category_id', $ctry->id); ?>><?php echo $ctry->category_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('category_id')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('category_id'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg category_err_msg_contnr" style="padding: 5px !important;">
                                <p id="category_id_err_msg"></p>
                            </div>

                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"> 
                                <label><?php echo lang('ingredient_consumptions'); ?> <span class="required_star">*</span></label>
                                <select tabindex="5" class="form-control select2 select2-hidden-accessible" name="ingredient_id" id="ingredient_id" style="width: 100%;">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($ingredients as $ingnts) { ?>
                                        <option value="<?php echo $ingnts->id . "|" . $ingnts->name . "|" . $ingnts->unit_name ?>" <?php echo set_select('unit_id', $ingnts->id); ?>><?php echo $ingnts->name . "(" . $ingnts->code . ")"; ?></option>
                                    <?php } ?>
                                </select>
                            </div>  
                            <?php if (form_error('ingredient_id')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('ingredient_id'); ?></p>
                                </div>
                            <?php } ?> 
                            <div class="alert alert-error error-msg ingredient_err_msg_contnr" style="padding: 5px !important;">
                                <p id="ingredient_id_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="hidden-xs hidden-sm">&nbsp;</div>
                            <a class="btn btn-danger" style="background-color: red;margin-top: 5px;" data-toggle="modal" data-target="#noticeModal"><?php echo lang('read_me_first'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="hidden-lg hidden-sm">&nbsp;</div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="ingredient_consumption_table">          
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('sn'); ?></th>
                                            <th><?php echo lang('ingredient'); ?></th>
                                            <th><?php echo lang('consumption'); ?></th>
                                            <th><?php echo lang('actions'); ?></th> 
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('sale_price'); ?> <span class="required_star">*</span></label>
                                <table style="width: 100%">
                                    <tr>
                                        <td><input tabindex="4" type="text"  onfocus="this.select();" id="sale_price" name="sale_price" class="form-control integerchk" onkeyup="return replacePonto();" placeholder="<?php echo lang('sale_price'); ?>" value="<?php echo set_value('sale_price'); ?>"></td>
                                        <td style="width: 11%;text-align: right"> <span class="label_aligning_total_loss">
                                                <?php echo $this->session->userdata('currency'); ?>
                                                <!-- <a class="top" title="" data-placement="top" data-toggle="tooltip" style="cursor: pointer"><i class="fa fa-question fa-lg form_question"></i></a> -->
                                            </span></td>
                                    </tr>
                                </table>
                            </div>
                            <?php if (form_error('sale_price')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('sale_price'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg sale_price_err_msg_contnr" style="padding: 5px !important;">
                                <p id="sale_price_err_msg"></p>
                            </div>

                        </div>
                        <!-- <label><?php echo lang('description'); ?></label> 
                            <div class="form-group"> 
                                <label>VAT</label>
                                <select tabindex="8" class="form-control select2" name="vat_id" style="width: 100%;">
                                    <option value="">Select</option>
                                    <?php foreach ($vats as $vat) { ?>
                                        <option value="<?php echo $vat->id ?>" <?php echo set_select('vat_id', $vat->id); ?>><?php echo $vat->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label> 
                                <input tabindex="1" type="text" id="description" name="description" class="form-control" placeholder="<?php echo lang('description'); ?>" value="<?php echo set_value('description'); ?>">
                            </div>
                            <?php if (form_error('description')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('description'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg description_err_msg_contnr" style="padding: 5px !important;">
                                <p id="description_err_msg"></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('photo'); ?> </label>
                                <input tabindex="10" type="file"  name="photo" id="photo">
                            </div>
                            <?php if (form_error('photo')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('photo'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('is_it_veg'); ?> ? <span class="required_star"> *</span></label>
                                <select tabindex="2" class="form-control select2" id="veg_item" name="veg_item" style="width: 100%;">
                                    <option value="Veg No"><?php echo lang('no'); ?></option>
                                    <option value="Veg Yes"><?php echo lang('yes'); ?></option> 
                                </select>
                            </div>
                            <?php if (form_error('veg_item')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('veg_item'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg veg_item_err_msg_contnr" style="padding: 5px !important;">
                                <p id="veg_item_err_msg"></p>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('is_it_beverage'); ?> ? <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" id="beverage_item" name="beverage_item" style="width: 100%;">
                                    <option value="Beverage No"><?php echo lang('no'); ?></option>
                                    <option value="Beverage Yes"><?php echo lang('yes'); ?></option> 
                                </select>
                            </div>
                            <?php if (form_error('beverage_item')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('beverage_item'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg beverage_item_err_msg_contnr" style="padding: 5px !important;">
                                <p id="beverage_item_err_msg"></p>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('is_it_bar'); ?> ? <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" id="bar_item" name="bar_item" style="width: 100%;">
                                    <option value="Bar No"><?php echo lang('no'); ?></option>
                                    <option value="Bar Yes"><?php echo lang('yes'); ?></option> 
                                </select>
                            </div>
                            <?php if (form_error('bar_item')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('bar_item'); ?></p>
                                </div>
                            <?php } ?>
                            <div class="alert alert-error error-msg bar_item_err_msg_contnr" style="padding: 5px !important;">
                                <p id="bar_item_err_msg"></p>
                            </div>

                        </div>    

                    </div>

                    <div class="row">
                        <?php foreach($tax_fields as $tax_field){ ?>
                                
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $tax_field->tax; ?></label> 
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                <input tabindex="1" type="hidden" name="tax_field_id[]" value="<?php echo $tax_field->id; ?>">
                                                <input tabindex="1" type="hidden" name="tax_field_outlet_id[]" value="<?php echo $tax_field->outlet_id; ?>">
                                                <input tabindex="1" type="hidden" name="tax_field_company_id[]" value="<?php echo $tax_field->company_id; ?>">
                                                <input tabindex="1" type="hidden" name="tax_field_name[]" value="<?php echo $tax_field->tax; ?>">
                                                <input tabindex="1" type="text" name="tax_field_percentage[]" class="form-control integerchk" placeholder="<?php echo $tax_field->tax; ?>" value="0">
                                            </td>
                                            <td style="width: 11%;text-align: right"> %</td>
                                        </tr>
                                    </table>
                                    
                                    
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>

                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('submit'); ?></button>
                        <a href="<?php echo base_url() ?>Master/foodMenus"><button type="button" class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="noticeModal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-2x">×</i></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 hidden-lg hidden-sm">
                            <p class="foodMenuCartNotice">
                                <strong style="margin-left: 39%"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12 hidden-xs hidden-sm">
                            <p class="foodMenuCartNotice">
                                <strong style="margin-left: 43%"><?php echo lang('notice'); ?></strong><br>
                               <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12 hidden-xs hidden-lg">
                            <p class="foodMenuCartNotice">
                                <strong style="margin-left: 43%"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p class="foodMenuCartInfo">
                                <!-- Please mention the consumption in the unit shown at right. <br>
                                 eg: 5Kg, 0.20Kg, 3L, 0.50L, 1Pcs etc
                                 <br>
                                 <br>-->
                                <a style="font-weight: bold;" href="https://www.convert-me.com/en/convert/" target="_blank"><?php echo lang('click_here'); ?></a>  <?php echo lang('notice_text_2'); ?>
                                <br>
                                <br>
                                <?php echo lang('notice_text_3'); ?>
                                <br>
                                <span style="font-weight: bold; font-style: italic;"><?php echo lang('notice_text_4'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>