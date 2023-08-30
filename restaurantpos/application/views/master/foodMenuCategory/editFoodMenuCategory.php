<style type="text/css">
    .required_star{
        color: #dd4b39;
    }

    .radio_button_problem{
        margin-bottom: 19px;
    }
</style> 

<section class="content-header">
    <h1>
        <?php echo lang('edit_food_menu_category'); ?>
    </h1>  
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">  
                <!-- form start -->
                <?php echo form_open(base_url('Master/addEditFoodMenuCategory/' . $encrypted_id)); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('category_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="category_name" class="form-control" placeholder="<?php echo lang('category_name'); ?>" value="<?php echo $category_information->category_name; ?>">
                            </div>
                            <?php if (form_error('category_name')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('category_name'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <input tabindex="2" type="text" name="description" class="form-control" placeholder="<?php echo lang('description'); ?>" value="<?php echo $category_information->description; ?>">
                            </div>
                        </div> 

                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('submit'); ?></button>
                    <a href="<?php echo base_url() ?>Master/foodMenuCategories"><button type="button" class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>