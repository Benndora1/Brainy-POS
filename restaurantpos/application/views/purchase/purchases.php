 <script type="text/javascript">  
    var ingredient_id_container = [];


    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    })
</script>

<?php
if ($this->session->flashdata('exception')) {

    echo '<section class="content-header"><div class="alert alert-success alert-dismissible"> 
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p><i class="icon fa fa-check"></i>';
    echo $this->session->flashdata('exception');
    echo '</p></div></section>';
}
?> 


<style type="text/css">
    .top-left-header{
        margin-top: 0px !important;
    }
</style>

<section class="content-header">
    <div class="row">
        <div class="col-md-6">
            <h2 class="top-left-header"><?php echo lang('purchases'); ?> </h2>
        </div>
        <div class="col-md-offset-4 col-md-2">
            <a href="<?php echo base_url() ?>Purchase/addEditPurchase"><button type="button" class="btn btn-block btn-primary pull-right"><?php echo lang('add_purchase'); ?></button></a>
        </div>
    </div> 
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary"> 
                <!-- /.box-header -->
                <div class="box-body table-responsive"> 
                    <table id="datatable" class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%;"><?php echo lang('sn'); ?></th>
                                <th style="width: 11%;" ><?php echo lang('ref_no'); ?></th>
                                <th style="width: 8%;" ><?php echo lang('date'); ?></th>
                                <th style="width: 18%;" ><?php echo lang('supplier'); ?></th>
                                <th style="width: 9%;" ><?php echo lang('g_total'); ?></th>
                                <th style="width: 9%;" ><?php echo lang('due'); ?></th>
                                <th style="width: 12%;" ><?php echo lang('added_by'); ?></th>
                                <th style="width: 5%;text-align: center" ><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($purchases && !empty($purchases)) {
                                $i = count($purchases);
                            }
                            foreach ($purchases as $prchs) {
                                ?>                       
                                <tr> 
                                    <td><?php echo $i--; ?></td> 
                                    <td><?php echo $prchs->reference_no; ?></td> 
                                    <td><?php echo date($this->session->userdata('date_format'), strtotime($prchs->date)); ?></td> 
                                    <td><?php echo getSupplierNameById($prchs->supplier_id); ?></td>
                                    <td><?php echo $this->session->userdata('currency') . " " . $prchs->grand_total ?></td>
                                    <td><?php echo $this->session->userdata('currency') . " " . $prchs->due ?></td>
                                    <td><?php echo userName($prchs->user_id); ?></td>  
                                    <td style="text-align: center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-gear tiny-icon"></i><span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu"> 
                                                <li><a href="<?php echo base_url() ?>Purchase/purchaseDetails/<?php echo $this->custom->encrypt_decrypt($prchs->id, 'encrypt'); ?>" ><i class="fa fa-eye tiny-icon"></i><?php echo lang('view_details'); ?></a></li>
                                                <li><a href="<?php echo base_url() ?>Purchase/addEditPurchase/<?php echo $this->custom->encrypt_decrypt($prchs->id, 'encrypt'); ?>" ><i class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a></li>
                                                <li><a class="delete" href="<?php echo base_url() ?>Purchase/deletePurchase/<?php echo $this->custom->encrypt_decrypt($prchs->id, 'encrypt'); ?>" ><i class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a></li> 
                                            </ul> 
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?> 
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('ref_no'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('supplier'); ?></th>
                                <th><?php echo lang('g_total'); ?></th>  
                                <th><?php echo lang('due'); ?></th>  
                                <th><?php echo lang('added_by'); ?></th>  
                                <th style="text-align: center"><?php echo lang('actions'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div> 
        </div> 
    </div> 
</section>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<script>
    $(function () { 
        $('#datatable').DataTable({ 
            'autoWidth'   : false,
            'ordering'    : false
        })
    })
</script>
