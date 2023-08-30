<?php
if ($value =$this->session->flashdata('exception')) {

    echo '<section class="content-header"><div class="alert alert-success alert-dismissible"> 
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p><i class="icon fa fa-check"></i>';
    echo $value;
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
            <h2 class="top-left-header"><?php echo lang('attendances'); ?> </h2>
        </div>
        <div class="col-md-offset-3 col-md-3">
            <a href="<?php echo base_url() ?>Attendance/addEditAttendance"><button type="button" class="btn btn-block btn-primary pull-right"><?php echo lang('add_attendance'); ?></button></a>
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
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 1%"><?php echo lang('sn'); ?></th>
                                <th style="width: 11%"><?php echo lang('ref_no'); ?></th>
                                <th style="width: 9%"><?php echo lang('date'); ?></th>
                                <th style="width: 18%"><?php echo lang('employee'); ?></th>
                                <th style="width: 10%"><?php echo lang('in_time'); ?></th>
                                <th style="width: 10%"><?php echo lang('out_time'); ?></th>
                                <th style="width: 15%"><?php echo lang('update_time'); ?></th>
                                <th style="width: 14%"><?php echo lang('time_count'); ?></th>
                                <th style="width: 15%"><?php echo lang('note'); ?></th>
                                <th style="width: 29%"><?php echo lang('added_by'); ?></th>
                                <th style="width: 6%"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($attendances && !empty($attendances)) {
                                $i = count($attendances); 
                            foreach ($attendances as $value) {
                                ?>                       
                                <tr> 
                                    <td><?php echo $i--; ?></td>
                                    <td><?php echo $value->reference_no; ?></td>
                                    <td><?php echo date($this->session->userdata('date_format'), strtotime($value->date)); ?></td>
                                    <td><?php echo employeeName($value->employee_id); ?></td>
                                    <td><?php echo $value->in_time; ?></td>
                                    <td>
                                        <?php 
                                        if($value->out_time == '00:00:00'){ 
                                            echo 'N/A<br>';  
                                        }else{ 
                                            echo $value->out_time; 
                                        } 
                                        ?> 
                                    </td>
                                    <td>
                                        <?php 
                                            echo '<a href="'. base_url().'Attendance/addEditAttendance/'. $value->id .'">Update Time</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php  
                                        if($value->out_time == '00:00:00'){ 
                                            echo 'N/A'; 
                                        }else{ 
                                            $to_time = strtotime($value->out_time);
                                            $from_time = strtotime($value->in_time);
                                            $minute = round(abs($to_time - $from_time) / 60,2); 
                                            $hour = round(abs($minute) / 60,2);
                                            echo $hour." Hour";
                                        }

                                        ?> 
                                    </td>
                                    <td><?php if ($value->note != NULL) echo $value->note; ?></td>
                                    <td><?php echo userName($value->user_id); ?></td>
                                    <td style="text-align: center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-gear tiny-icon"></i><span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">  
                                                <li><a class="delete" href="<?php echo base_url() ?>Attendance/deleteAttendance/<?php echo $this->custom->encrypt_decrypt($value->id, 'encrypt'); ?>" ><i class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a></li>
                                            </ul> 
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            } }
                            ?> 
                        </tbody>
                        <tfoot>
                            <tr>
                               <th style="width: 1%"><?php echo lang('sn'); ?></th>
                                <th style="width: 11%"><?php echo lang('ref_no'); ?></th>
                                <th style="width: 9%"><?php echo lang('date'); ?></th>
                                <th style="width: 18%"><?php echo lang('employee'); ?></th>
                                <th style="width: 10%"><?php echo lang('in_time'); ?></th>
                                <th style="width: 10%"><?php echo lang('out_time'); ?></th>
                                <th style="width: 15%"><?php echo lang('update_time'); ?></th>
                                <th style="width: 14%"><?php echo lang('time_count'); ?></th>
                                <th style="width: 15%"><?php echo lang('note'); ?></th>
                                <th style="width: 29%"><?php echo lang('added_by'); ?></th>
                                <th style="width: 6%"><?php echo lang('actions'); ?></th>
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
