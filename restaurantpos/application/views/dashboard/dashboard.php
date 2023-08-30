<!-- bootstrap datepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
  .btn-glyphicon { padding:8px; background:#ffffff; margin-right:4px; }
  .icon-btn { padding: 1px 15px 3px 2px; border-radius:5px;}
  .text-info{ color: white; padding: 10px; }
  #operation_comparision_range_fields{
    width: 300px;
    height: 100px;
    position: absolute;
    background-color: #ffffff;
    border: 1px solid #d8dce0;
    top: 0;
    right: 0;
    box-shadow: 0px 0px 12px #8e8e8e;
    border-radius: 5px;
    display: none;
    z-index:1;
  }
  #operation_comparision_input{
      border: 1px solid #cfcfcf;
      height: 30px;
      width: 90%;
      padding: 0px 0px 0px 5px;
      margin: 5%;
  }

  .btn-info{ 
    margin-bottom: 15px;
    margin-right: 30px;
    width: 170px;
    font-size: 13px;
    border:1px solid #868e96 !important;
    background-color:transparent;
    color:#868e96;
  }
  .btn-info .fa{
    color:#868e96;
  }
  .btn-info:hover{
    background-color: #868e96;
    color: #ffffff;
  }
  .btn-info:hover .fa{
    color: #ffffff;
  }
  /* end 1 */
  .btn-info:nth-child(2){
    border-color: #4caf50 !important;
    color:#4caf50;
  }
  .btn-info:nth-child(2) .fa{
    color:#4caf50;
  }
  .btn-info:nth-child(2):hover{
    background-color: #4caf50;
    color:#ffffff;
  }
  .btn-info:nth-child(2):hover .fa{
    color:#ffffff;
  }
  /* end 2 */
  .btn-info:nth-child(3){
    border-color: #f44336 !important;
    color:#f44336;
  }
  .btn-info:nth-child(3) .fa{
    color:#f44336;
  }
  .btn-info:nth-child(3):hover{
    background-color: #f44336;
    color:#ffffff;
  }
  .btn-info:nth-child(3):hover .fa{
    color:#ffffff;
  }
  /* end 3 */
  .btn-info:nth-child(4){
    border-color: #00bcd4 !important;
    color: #00bcd4;
  }
  .btn-info:nth-child(4) .fa{
    color: #00bcd4;
  }
  .btn-info:nth-child(4):hover{
    background-color: #00bcd4;
    color:#ffffff;
  }
  .btn-info:nth-child(4):hover .fa{
    color:#ffffff;
  }
  /* end 4 */
  .btn-info:nth-child(5){
    border-color: #4834d4 !important;
    color: #4834d4;
  }
  .btn-info:nth-child(5) .fa{
    color: #4834d4;
  }
  .btn-info:nth-child(5):hover{
    background-color: #4834d4;
    color:#ffffff;
  }
  .btn-info:nth-child(5):hover .fa{
    color:#ffffff;
  }
  /* end 5 */
</style>
  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo lang('dashboard'); ?>
        <small><?php echo lang('business_intelligence'); ?></small>
      </h1> 
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box box4column">
            <div class="inner">
              <h3><?php echo $food_menu_count->data_count; ?></h3>

              <p><?php echo lang('food_items'); ?></p>
            </div>
            <div class="icon">
              <i class="ion ion-pizza"></i>
            </div>
            <a href="<?php echo base_url(); ?>Master/foodMenus" class="small-box-footer">Manage <i data-feather="arrow-right-circle"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box box4column">
            <div class="inner">
              <h3><?php echo $ingredient_count->data_count; ?></h3>

              <p><?php echo lang('ingredients'); ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-leaf"></i>
            </div>
            <a href="<?php echo base_url(); ?>Master/ingredients" class="small-box-footer"><?php echo lang('manage'); ?> <i data-feather="arrow-right-circle"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box box4column">
            <div class="inner">
              <h3><?php echo $customer_count->data_count; ?></h3>

              <p><?php echo lang('customers'); ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="<?php echo base_url(); ?>Master/customers" class="small-box-footer"><?php echo lang('manage'); ?> <i data-feather="arrow-right-circle"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box box4column">
            <div class="inner">
              <h3><?php echo $employee_count->data_count; ?></h3>

              <p><?php echo lang('users'); ?></p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <a href="<?php echo base_url(); ?>User/users" class="small-box-footer"><?php echo lang('manage'); ?> <i data-feather="arrow-right-circle"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <!-- quick email widget -->
      <div class="row">
        <div class="col-lg-7">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="link"></i> 
            <h3 class="box-title"><?php echo lang('quick_links'); ?></h3> 
          </div>
          <div class="box-body">  
            <div style="width: 33%; float: left; height: 260px;">
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Master/addEditFoodMenu"><span class="fa fa-book text-info"></span>+ <?php echo lang('food_menu'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>SupplierPayment/addSupplierPayment"><span class="fa fa-user text-info"></span>+ <?php echo lang('supplier_payment'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Sale/POS"><span class="fa fa-television text-info"></span><?php echo lang('pos'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Expense/addEditExpense"><span class="fa fa-money text-info"></span>+ <?php echo lang('expense'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Purchase/addEditPurchase"><span class="fa fa-truck text-info"></span>+ <?php echo lang('purchase'); ?></a>
            </div>
            <div style="width: 33%; float: left; height: 260px;">
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Report/dailySummaryReport"><span class="fa fa-list text-info"></span><?php echo lang('daily_summary_report'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Report/registerReport"><span class="fa fa-list text-info"></span><?php echo lang('register_report'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Report/profitLossReport"><span class="fa fa-list text-info"></span><?php echo lang('profit_loss_report'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Report/saleReportByDate"><span class="fa fa-list text-info"></span><?php echo lang('sales_report'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Report/foodMenuSales"><span class="fa fa-list text-info"></span><?php echo lang('food_sales_report'); ?></a>              
            </div>
            <div style="width: 33%; float: left; height: 260px;">
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Short_message_service/smsService"><span class="fa fa-envelope text-info"></span><?php echo lang('send_sms'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Inventory/index"><span class="fa fa-cube text-info"></span><?php echo lang('inventory'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Inventory_adjustment/inventoryAdjustments"><span class="fa fa-adjust text-info"></span><?php echo lang('inventory_adjustment'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Customer_due_receive/customerDueReceives"><span class="fa fa-users text-info"></span>+ <?php echo lang('customer_receive'); ?></a>
              <a class="btn icon-btn btn-info" style="border:none;" href="<?php echo base_url(); ?>Attendance/addEditAttendance"><span class="fa fa-clock-o text-info"></span>+ <?php echo lang('attendance'); ?></a>
            </div>             
          </div> 
        </div>
        </div> 

        <div class="col-lg-5">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="briefcase"></i> 
            <h3 class="box-title"><?php echo lang('dine'); ?>/<?php echo lang('take_away'); ?>/<?php echo lang('delivery'); ?>(<?php echo lang('this_month'); ?>)</h3> 
            <!-- <span id="date_range_dtd" style="padding-left: 15px;"><?php echo date('Y-m-01'); ?> to <?php echo date('Y-m-t'); ?> </span><span style="float: right;"><a href=""><i class="fa fa-calendar"></i></a></span> -->
            
          </div>
          <div class="box-body">  
            <div class="chart-responsive" style="height: 260px;">
                <canvas id="pieChart" style="height:280px"></canvas>
            </div>
          </div> 
        </div>
        </div>             
      </div>


      <div class="row">
        <div class="col-lg-7">
<!--           <div id="operation_comparision_range_fields">
            <input class="form-group" type="text" name="operation_comparision_input" id="operation_comparision_input"/>
            <div style="padding:0% 5%">
              <button class="btn btn-primary" name="operation_comparision_submit" id="operation_comparision_submit">Submit</button>
              <button class="btn btn-primary" name="operation_comparision_cancel" id="operation_comparision_cancel">Cancel</button>
            </div>
            
          </div> -->
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="truck"></i>
            <h3 class="box-title"><?php echo lang('operational_comparision'); ?>(<?php echo lang('this_month'); ?>)</h3> 
   <!--          <span id="date_range_dtd" style="padding-left: 220px;"><?php echo date('Y-m-01'); ?> to <?php echo date('Y-m-t'); ?> </span><span style="float: right;"><a id="operational_coparision_range" style="cursor:pointer;"><i class="fa fa-calendar"></i></a></span> -->
          </div>
          <div class="box-body" style="height: 280px;">  
              <div class="chart"> 
                <div class="chart" id="operational_comparision" style="height: 250px;"></div>
              </div>            
          </div> 
        </div>
        </div> 

        <div class="col-lg-5">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="alert-triangle"></i> 
            <h3 class="box-title"><?php echo lang('ingredients_alert'); ?>/<?php echo lang('low_stock'); ?> <span style="color: red;">(<?php echo count($low_stock_ingredients); ?>)</span> </h3> 
          </div>
          <div class="box-body" style="height: 280px;">  
            <ul class="todo-list">
              <li class="todo-title"> 
                <span class="text" style="font-weight: bold;"><?php echo lang('ingredient_name'); ?></span> 
                <div style="float: right; padding-right: 5px; font-weight: bold;">
                  <span><?php echo lang('current_stock'); ?></span>
                </div>
              </li>
            </ul>
            <ul class="todo-list" id="low_stock_ingredients" style="overflow: hidden;">  
            <?php
                $totalStock = 0;
                if ($low_stock_ingredients && !empty($low_stock_ingredients)) {
                    $i = count($low_stock_ingredients);
                }
                foreach ($low_stock_ingredients as $value) {
                  $totalStock = $value->total_purchase - $value->total_consumption - $value->total_modifiers_consumption - $value->total_waste + $value->total_consumption_plus - $value->total_consumption_minus;
                    ?>   
                    <li> 
                      <span class="text"><?= $value->name . "(" . $value->code . ")" ?></span> 
                      <div style="float: right; color: #dd4b39; padding-right: 5px;">
                        <span><?= ($totalStock) ? $totalStock : '0.0' ?></span>
                      </div>
                    </li> 
              <?php } ?>      
              </ul>
          </div>
        </div>             
      </div> 
      </div>

      <div class="row">
        <div class="col-lg-7">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="coffee"></i> 
            <h3 class="box-title"><?php echo lang('top_ten_food_this_month'); ?></h3> 
          </div>
          <div class="box-body" style="height: 280px;">  
            <ul class="todo-list">
              <li class="todo-title">
                <div style="float: left; padding-left: 5px; font-weight: bold;">
                    <span><?php echo lang('sn'); ?></span>
                </div> 
                <span class="text" style="font-weight: bold;"><?php echo lang('food_name'); ?></span> 
                <div style="float: right; padding-right: 5px; font-weight: bold;">
                  <span><?php echo lang('count'); ?></span>
                </div>
              </li>
            </ul>
            <ul class="todo-list" id="top_ten_food_menu" style="overflow: hidden;">  
            <?php 
                if ($top_ten_food_menu && !empty($top_ten_food_menu)) { 
                foreach ($top_ten_food_menu as $key => $value) { 
                  $key++;
                    ?>   
                    <li> 
                      <div style="float: left; padding-left: 5px;">
                        <span><?= $key ?></span>
                      </div>
                      <span class="text"><?= $value->menu_name ?></span> 
                      <div style="float: right; color: green; padding-right: 5px;">
                        <span><?= $value->totalQty ?></span>
                      </div>
                    </li> 
              <?php } } ?>      
              </ul>      
          </div> 
        </div>
        </div> 

        <div class="col-lg-5">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="users"></i> 
            <h3 class="box-title"><?php echo lang('top_ten_customers'); ?></h3> 
          </div>
          <div class="box-body" style="height: 280px;">  
            <ul class="todo-list">
              <li class="todo-title">
                <div style="float: left; padding-left: 5px; font-weight: bold;">
                    <span><?php echo lang('sn'); ?></span>
                </div> 
                <span class="text" style="font-weight: bold;"><?php echo lang('customer_name'); ?></span> 
                <div style="float: right; padding-right: 5px; font-weight: bold;">
                  <span><?php echo lang('sale_amount'); ?></span>
                </div>
              </li>
            </ul>
            <ul class="todo-list" id="top_ten_customer">
            <?php 
                if ($top_ten_customer && !empty($top_ten_customer)) { 
                foreach ($top_ten_customer as $key => $value) { 
                  $key++;
                    ?>   
                    <li> 
                      <div style="float: left; padding-left: 5px;">
                        <span><?= $key ?></span>
                      </div>
                      <span class="text"><?= $value->name ?></span> 
                      <div style="float: right; color: green; padding-right: 5px;">
                        <span><?php echo $this->session->userdata('currency')." ". $value->total_payable ?></span>
                      </div>
                    </li> 
              <?php } } ?>      
              </ul> 
          </div>
        </div>             
      </div> 
      </div> 

      <div class="row">
        <div class="col-lg-7">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="dollar-sign"></i> 
            <h3 class="box-title"><?php echo lang('customer_receiveable'); ?></h3> 
          </div>
          <div class="box-body">  
              <ul class="todo-list">
              <li class="todo-title">
                <div style="float: left; padding-left: 5px; font-weight: bold;">
                    <span><?php echo lang('sn'); ?></span>
                </div> 
                <span class="text" style="font-weight: bold;"><?php echo lang('customer_name'); ?></span> 
                <div style="float: right; padding-right: 5px; font-weight: bold;">
                  <span><?php echo lang('due_amount'); ?></span>
                </div>
              </li>
            </ul>
            <ul class="todo-list" id="customer_receivable" style="overflow: hidden;">  
            <?php 
                if ($customer_receivable && !empty($customer_receivable)) { 
                foreach ($customer_receivable as $key => $value) { 
                  $key++;
                  if($value->due_amount != '0.00' && $value->due_amount != ''){
                    ?>   
                    <li> 
                      <div style="float: left; padding-left: 5px;">
                        <span><?= $key ?></span>
                      </div>
                      <span class="text"><?= $value->name ?></span> 
                      <div style="float: right; color: green; padding-right: 5px;">
                        <?php $current_due = $value->due_amount - getCustomerDueReceive($value->customer_id); ?>
                        <span><?php echo $this->session->userdata('currency')." ". $current_due ?></span>
                      </div>
                    </li> 
              <?php } }  } ?>      
              </ul>          
          </div> 
        </div>
        </div> 

        <div class="col-lg-5">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="dollar-sign"></i> 
            <h3 class="box-title"><?php echo lang('supplier_payable'); ?></h3> 
          </div>
          <div class="box-body">  
            <ul class="todo-list">
              <li class="todo-title">
                <div style="float: left; padding-left: 5px; font-weight: bold;">
                    <span><?php echo lang('sn'); ?></span>
                </div> 
                <span class="text" style="font-weight: bold;"><?php echo lang('supplier_name'); ?></span> 
                <div style="float: right; padding-right: 5px; font-weight: bold;">
                  <span><?php echo lang('due_amount'); ?></span>
                </div>
              </li>
            </ul>
            <ul class="todo-list" id="supplier_payable" style="overflow: hidden;">  
            <?php 
                if ($supplier_payable && !empty($supplier_payable)) { 
                foreach ($supplier_payable as $key => $value) { 
                  $key++;
                  if($value->due != '0.00' && $value->due != ''){
                    ?>   
                    <li> 
                      <div style="float: left; padding-left: 5px;">
                        <span><?= $key ?></span>
                      </div>
                      <span class="text"><?= $value->name ?></span> 
                      <div style="float: right; color: green; padding-right: 5px;">
                        <?php $current_due = $value->due - getSupplierDuePayment($value->supplier_id); ?>
                        <span><?php echo $this->session->userdata('currency')." ". $current_due ?></span>
                      </div>
                    </li> 
              <?php } }  } ?>      
              </ul>  
          </div>
        </div>             
      </div> 
      </div> 

      <div class="row">
        <div class="col-lg-12">
          <div class="box box-info">
          <div class="box-header">
            <i data-feather="briefcase"></i> 
            <h3 class="box-title"><?php echo lang('monthly_sales_comparision'); ?></h3> 
          </div>
          <div class="box-body">  
              <div class="chart">
                <!-- <canvas id="msc" style="height:260px"></canvas> -->
                <div id="chart_div" style="width: 100%; height: 280px;"></div>
              </div>            
          </div> 
        </div>
        </div> 
      </div> 
</section>

          <!-- /.box --> 
      <!-- /.row (main row) --> 
    <!-- /.content --> 
<!-- /.content -->
<!-- bootstrap datepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"> 
<!-- ChartJS -->
<script src="<?php echo base_url(); ?>assets/bower_components/chart.js/Chart.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="<?php echo base_url(); ?>assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css">
<script> 

  $(function () {
    feather.replace()
    //BAR CHART
    var bar = new Morris.Bar({
      element: 'operational_comparision',
      resize: true,
      data: [
        {y: '<?php echo lang('purchase'); ?>', a: <?php echo $purchase_sum->purchase_sum; ?>},
        {y: '<?php echo lang('sale'); ?>', a: <?php echo $sale_sum->sale_sum; ?>},
        {y: '<?php echo lang('waste'); ?>', a: <?php echo $waste_sum->waste_sum; ?>},
        {y: '<?php echo lang('expense'); ?>', a: <?php echo $expense_sum->expense_sum; ?>},
        {y: '<?php echo lang('cust_rcv'); ?>', a: <?php echo $customer_due_receive_sum->customer_due_receive_sum; ?>},
        {y: '<?php echo lang('supp_pay'); ?>', a: <?php echo $supplier_due_payment_sum->supplier_due_payment_sum; ?>} 
      ],
      barColors: ['#4CAE50', '#F34336'],
      xkey: 'y',
      ykeys: ['a'],
      labels: ['Amount'],
      hideHover: 'auto'
    });

  $('#low_stock_ingredients, #top_ten_food_menu, #top_ten_customer, #customer_receivable, #supplier_payable').slimscroll({
    height: '220px'
  });

  // -------------
  // - PIE CHART -
  // -------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);
  var PieData        = [
    {
      value    : <?php echo $dinein_count->dinein_count; ?>,
      color    : '#DD4B39',
      highlight: '#DD4B39',
      label    : 'Dine In'
    },
    {
      value    : <?php echo $take_away_count->take_away_count; ?>,
      color    : '#4c1c00',
      highlight: '#4c1c00',
      label    : 'Take Away'
    },
    {
      value    : <?php echo $delivery_count->delivery_count; ?>,
      color    : '#6F61AA',
      highlight: '#6F61AA',
      label    : 'Delivery'
    } 
  ];
  var pieOptions     = {
    // Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    // String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    // Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    // Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    // Number - Amount of animation steps
    animationSteps       : 100,
    // String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    // Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    // Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    // Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : false,
    // String - A legend template
    legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    // String - A tooltip template
    tooltipTemplate      : '<%=value %> <%=label%> Orders'
  };
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions);
  // -----------------
  // - END PIE CHART -
  // -----------------  
  })

</script>


<script type="text/javascript">
  var currency = "<?php echo $this->session->userdata('currency') ?>";
        var base_url = "<?php echo base_url();?>";
  $(document).ready(function(){
      selectMonth(12);
      $('#operational_coparision_range').on('click',function(){
        $('#operation_comparision_range_fields').show();
      });
      $('#operation_comparision_cancel').on('click',function(){
        $('#operation_comparision_range_fields').hide();
      })
      $('#operation_comparision_input').daterangepicker({
        opens: 'left',
        locale: {
          format: 'YYYY-MM-DD'
        }
      });
      $('#operation_comparision_submit').on('click',function(){
        
        var date_range = $('#operation_comparision_input').val();
        var date_range_array = date_range.split(" - ");
        var from_this_day = date_range_array[0];
        var to_this_day = date_range_array[1];
        $.ajax({
            url: '<?php echo base_url("Dashboard/operation_comparision_by_date_ajax") ?>',
            method:"POST",
            data:{
                from_this_day : from_this_day,
                to_this_day : to_this_day,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success:function(response) {
              response = JSON.parse(response);
              //BAR CHART
              var bar = new Morris.Bar({
                element: 'operational_comparision',
                resize: true,
                data: [
                  {y: 'Purchase', a: response.purchase_sum.purchase_sum},
                  {y: 'Sale', a: response.sale_sum.sale_sum},
                  {y: 'Waste', a: response.waste_sum.waste_sum},
                  {y: 'Expense', a: response.expense_sum.expense_sum},
                  {y: 'Cust Rcv', a: response.customer_due_receive_sum.customer_due_receive_sum},
                  {y: 'Supp Pay', a: response.supplier_due_payment_sum.supplier_due_payment_sum} 
                ],
                barColors: ['#8BC34A', '#f56954'],
                xkey: 'y',
                ykeys: ['a'],
                labels: ['Amount'],
                hideHover: 'auto'
              });
              
            },
            error:function(){
                alert("error");
            }
        });
        $('#operation_comparision_range_fields').hide();
      });
  });

function  selectMonth(value) {
        $.ajax({
            url: '<?= base_url() ?>Dashboard/comparison_sale_report_ajax_get',
            type: 'get',
            datatype: 'json',
            data: {
              months: value,
              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function (response) {
                var json = $.parseJSON(response);
                google.charts.load('current', {'packages':['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawStuff);
                function drawStuff() {
                    var chartDiv = document.getElementById('chart_div');

                    var data = '';
                    var dataArray = [];
                    var dataArrayValue = [];
                    dataArrayValue = [];
                    dataArrayValue.push('');
                    dataArrayValue.push('');
                    dataArray.push(dataArrayValue);

                    $.each(json, function (i, v) {
                        window['monthName'+i]=v.month;
                        window['collection'+i]=v.saleAmount;
                        dataArrayValue = [];
                        dataArrayValue.push(v.month);
                        dataArrayValue.push(v.saleAmount);
                        dataArray.push(dataArrayValue);
                    });
                    data = google.visualization.arrayToDataTable(dataArray);
                    var options = {
                        legend: { position: "none" },
                        colors: ['#8BC34A', '#8BC34A', '#8BC34A'],
                        axes: {
                            y: {
                                all: {
                                    format: {
                                        pattern: 'decimal'
                                    }
                                }
                            }
                        },
                        series: {
                            0: { axis: '0' }
                        }
                    };

                    function drawMaterialChart() {
                        var materialChart = new google.charts.Bar(chartDiv);
                        materialChart.draw(data,options);
                    }
                    function drawClassicChart() {
                        var classicChart = new google.visualization.ColumnChart(chartDiv);
                        classicChart.draw(data, classicOptions);

                    }
                    drawMaterialChart();
                }
            }
        });

    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.cookie.js"></script>