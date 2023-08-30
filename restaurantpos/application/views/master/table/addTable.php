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
        <?php echo lang('add_table'); ?>
    </h1>  
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary"> 
                <!-- form start -->
                <?php echo form_open(base_url('Master/addEditTable')); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('table_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="name" class="form-control" placeholder="<?php echo lang('table_name'); ?>" value="<?php echo set_value('name'); ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('name'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('seat_capacity'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="sit_capacity" class="form-control integerchk" placeholder="<?php echo lang('seat_capacity'); ?>" value="<?php echo set_value('sit_capacity'); ?>">
                            </div>
                            <?php if (form_error('sit_capacity')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('sit_capacity'); ?></p>
                                </div>
                            <?php } ?>  
                        </div> 

                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('position'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="position" class="form-control" placeholder="<?php echo lang('position'); ?>" value="<?php echo set_value('position'); ?>">
                            </div>
                            <?php if (form_error('position')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('position'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <input tabindex="2" type="text" name="description" class="form-control" placeholder="<?php echo lang('description'); ?>" value="<?php echo set_value('description'); ?>">
                            </div>
                            <?php if (form_error('description')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('description'); ?></p>
                                </div>
                            <?php } ?>  
                        </div> 

                    </div>
                    
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('submit'); ?></button>
                    <a href="<?php echo base_url() ?>Master/tables"><button type="button" class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>