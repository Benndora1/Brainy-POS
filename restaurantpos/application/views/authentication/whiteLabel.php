<style type="text/css">
    .required_star{
        color: #dd4b39;
    }

    .radio_button_problem{
        margin-bottom: 19px;
    }
</style>

<?php
if ($this->session->flashdata('exception')) {

    echo '<section class="content-header"><div class="alert alert-success alert-dismissible"> 
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p><i class="icon fa fa-check"></i>';
    echo $this->session->flashdata('exception');
    echo '</p></div></section>';
}
?>
<?php
if ($this->session->flashdata('exception_1')) {

    echo '<section class="content-header"><div class="alert alert-danger alert-dismissible"> 
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p><i class="icon fa fa-check"></i>';
    echo $this->session->flashdata('exception_1');
    echo '</p></div></section>';
}
?>
<section class="content-header">
    <h1>
        <?php echo lang('White Label'); ?>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'Authentication/whiteLabel/'.(isset($getWhiteLabel) && $getWhiteLabel->id?$getWhiteLabel->id:''), $arrayName = array('id' => 'add_whitelabel','enctype'=>'multipart/form-data')) ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> <?php echo lang('site_name'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="site_name" class="form-control" id="site_name" placeholder="<?php echo lang('site_name'); ?>" value="<?php echo isset($getWhiteLabel) &&  $getWhiteLabel->site_name?$getWhiteLabel->site_name:set_value('site_name'); ?>" />

                            </div>
                            <?php if (form_error('site_name')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('site_name'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> <?php echo lang('footer'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="footer" class="form-control" id="footer" placeholder="<?php echo lang('footer'); ?>" value="<?php echo isset($getWhiteLabel) &&  $getWhiteLabel->footer?$getWhiteLabel->footer:set_value('footer'); ?>" />
                            </div>
                            <?php if (form_error('footer')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('footer'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> <?php echo lang('logo'); ?> (w: 128px & h: 49px) <span class="required_star">*</span></label>
                                <input type="hidden" value="<?php echo isset($getWhiteLabel) &&  $getWhiteLabel->system_logo?$getWhiteLabel->system_logo:set_value('old_system_logo'); ?>" name="old_system_logo" >
                                <input type="file" name="system_logo" class="form-control" value="" id="system_logo" placeholder="<?php echo lang('system_logo'); ?>">
                            </div>
                            <?php if (form_error('system_logo')) { ?>
                                <div class="alert alert-error" style="padding: 5px !important;">
                                    <p><?php echo form_error('system_logo'); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if(isset($getWhiteLabel) &&  $getWhiteLabel->system_logo):?>
                        <div class="col-md-3">
                            <img src="<?php echo base_url(); ?>assets/images/<?=$getWhiteLabel->system_logo?>" class="" alt="">
                        </div>
                            <div class="clearfix"></div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>

<script>
    var _URL = window.URL || window.webkitURL;
    $(document).on('change','#login_logo' , function(){
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                var img_width = this.width;
                var height = this.height;
                if(img_width!="128" && height!="49" ){
                    $("#login_logo").val('');
                    swal({
                        title: '<?php echo lang('alert'); ?>',
                        text: '<?php echo lang('image_msg'); ?>!!',
                        confirmButtonText:'<?php echo lang('ok'); ?>',
                        confirmButtonColor: '#3c8dbc'
                    });
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $(document).on('change','#admin_panel_logo' , function(){
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                var img_width = this.width;
                var height = this.height;
                if(img_width!="128" && height!="49" ){
                    $("#admin_panel_logo").val('');
                    swal({
                        title: '<?php echo lang('alert'); ?>',
                        text: '<?php echo lang('image_msg'); ?>!!',
                        confirmButtonText:'<?php echo lang('ok'); ?>',
                        confirmButtonColor: '#3c8dbc'
                    });
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
</script>