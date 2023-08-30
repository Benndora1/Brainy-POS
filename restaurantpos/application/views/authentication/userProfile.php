<style type="text/css">
    .user_detail_container{
        background-color: #fff;
        border-radius: 5px;
        color: #66c8e0;
        padding: 5px 5px 5px 15px; 
        min-height: 362px;
    }

    .user_info_header{
        background-color: #66c8e0;
        border-radius: 5px;
        color: white;
        padding: 5px 5px 5px 15px; 
        margin-top: 0px; 
    }  
    .box.box-primary {
        box-shadow:0 1px 1px rgba(0, 0, 0, 0.1)
    }
</style>

<!-- Main content -->
<section class="content"> 
    <!-- general form elements -->
    <div class="box box-primary">

        <div class="box-body">
            <div class="row"> 
                <div class="col-md-4">
                    <img src="<?php echo base_url(); ?>assets/images/chef.png" alt="" class="img-rounded img-responsive" />
                </div>
                <div class="hidden-lg">&nbsp;</div>
                <div class="col-md-8">
                    <h1 class="user_info_header"><?php echo $this->session->userdata('full_name'); ?></h1> 
                    <div class="user_detail_container">
                        <?php if ($this->session->userdata('role') != 'Admin') { ?>
                            <p class="user_information">
                                <i class="fa fa-cutlery"></i> <?php echo $this->session->userdata('outlet_name'); ?> <br />
                            </p>
                        <?php } ?> 
                        <p class="user_information">
                            <i class="fa fa-users"></i> <?php echo $this->session->userdata('role'); ?><br />
                        </p>
                        <p class="user_information">
                            <i class="fa fa-phone"></i> <?php echo $this->session->userdata('phone'); ?> <br />
                        </p>
                        <?php if ($this->session->userdata('email_address') != '') { ?>
                            <p class="user_information">
                                <i class="fa fa-envelope"></i> <?php echo $this->session->userdata('email_address'); ?><br /> 
                            </p>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section> 