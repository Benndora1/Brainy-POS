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
            <h2 class="top-left-header"><?php echo lang('food_menu_categories'); ?> </h2>
        </div>
        <div class="col-md-offset-3 col-md-3">
            <a href="<?php echo base_url() ?>Master/addEditFoodMenuCategory"><button type="button" class="btn btn-block btn-primary pull-right"><?php echo lang('add_food_menu_category'); ?></button></a>
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
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 1%"><?php echo lang('sn'); ?></th>
                                <th style="width: 28%"><?php echo lang('category_name'); ?></th>
                                <th style="width: 35%"><?php echo lang('description'); ?></th>
                                <th style="width: 17%"><?php echo lang('added_by'); ?></th>
                                <th style="width: 6%;text-align: center%"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($foodMenuCategories && !empty($foodMenuCategories)) {
                                $i = count($foodMenuCategories);
                            }
                            foreach ($foodMenuCategories as $fmc) {
                                ?>
                                <tr>
                                    <td style="text-align: center"><?php echo $i--; ?></td>
                                    <td><?php echo $fmc->category_name; ?></td>
                                    <td><?php echo $fmc->description; ?></td>
                                    <td><?php echo userName($fmc->user_id); ?></td>
                                    <td style="text-align: center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-gear tiny-icon"></i><span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li><a href="<?php echo base_url() ?>Master/addEditFoodMenuCategory/<?php echo $this->custom->encrypt_decrypt($fmc->id, 'encrypt'); ?>" ><i class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a></li>
                                                <li><a class="delete" href="<?php echo base_url() ?>Master/deleteFoodMenuCategory/<?php echo $this->custom->encrypt_decrypt($fmc->id, 'encrypt'); ?>" ><i class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a></li>
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
                                <th><?php echo lang('category_name'); ?></th>
                                <th><?php echo lang('description'); ?></th>
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
