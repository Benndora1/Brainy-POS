<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Desktop_api extends REST_Controller {

    function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        // Construct the parent class
        parent::__construct();

        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Sale_model');
        $this->load->model('Master_model');
        $this->load->model('Desktop_api_model');
        $this->Common_model->setDefaultTimezone();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->post = $_REQUEST;
    }
    public function index_options() {
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }
    
    public function POS_Initial_post(){
        $company_id = $this->post['company_id'];
        $outlet_id = $this->post['outlet_id'];
        $data = array();
        $data['vatamount'] = $this->db->query("SELECT percentage FROM tbl_vats WHERE id=1")->row('percentage');
        $tables = $this->Sale_model->getTablesByOutletId($outlet_id);
        $data['tables'] = $this->getTablesDetails($tables);
        $data['categories'] = $this->Sale_model->getFoodMenuCategories($company_id, 'tbl_food_menu_categories');
        $data['item_menus'] = $this->Desktop_api_model->getAllItemmenus($company_id);
        $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_customers');
        $data['food_menus'] = $this->Sale_model->getAllFoodMenus();
        $data['menu_categories'] = $this->Sale_model->getAllMenuCategories();
        $data['menu_modifiers'] = $this->Sale_model->getAllMenuModifiers();
        $data['waiters'] = $this->Common_model->getAllByOutletId($outlet_id,'tbl_users');
        $data['modifiers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id,'tbl_modifiers');
        // $data['new_orders'] = $this->get_new_orders();
        $data['payment_methods'] = $this->Sale_model->getAllPaymentMethods();
        // $data['notifications'] = $this->get_new_notification();
        $data['user_menu_access'] = $this->db->query('SELECT * FROM tbl_user_menu_access')->result();
        $this->set_response($data,REST_Controller::HTTP_OK);
    }

    public function get_customer_running_sale_post(){
        $company_id = $this->post['company_id'];
        $outlet_id = $this->post['outlet_id'];
        $data = array();
        $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_customers');
       $this->set_response($data,REST_Controller::HTTP_OK); 
    }
    public function waiters_get(){
        $this->db->select("*");
      $this->db->from('tbl_users');
      $this->db->where("designation", 'Waiter');
      $this->db->order_by('name', 'ASC');
      $data['waiters'] = $this->db->get()->result();
        $this->set_response($data,REST_Controller::HTTP_OK); 
    }
    public function login_post(){
        $email_address = $this->post['email_address'];
        $password = $this->post['password']; 
        $this->logOut();
        $data = array();
                
        $data['error'] = '';

        $user_information = $this->Authentication_model->getUserInformation($email_address, $password);

        //If user exists
        if ($user_information) {

            //If the user is Active
            if ($user_information->active_status == 'Active' && ($user_information->role == 'Admin' || $user_information->designation == 'Waiter')) {
                $company_info = $this->Authentication_model->getCompanyInformation($user_information->company_id);
                $setting_info = $this->Common_model->getByCompanyId($user_information->company_id, "tbl_settings");


                $menu_access_information = $this->Authentication_model->getMenuAccessInformation($user_information->id);

                $menu_access_container = array();
                if (isset($menu_access_information)) {
                    foreach ($menu_access_information as $value) {
                        array_push($menu_access_container, $value->controller_name);
                    }
                }

                // echo "string";


                

                $outlet_details = $this->Common_model->getDataById($company_info->outlet_id, 'tbl_outlets');

                if ($user_information->role == 'Admin') {
                    $login_session = array();
                    //User Information
                    $login_session['user_id'] = $user_information->id;
                    $login_session['language'] = $user_information->language;
                    $login_session['full_name'] = $user_information->full_name;
                    $login_session['phone'] = $user_information->phone;
                    $login_session['email_address'] = $user_information->email_address; 
                    $login_session['role'] = $user_information->role;
                    $login_session['company_id'] = $user_information->company_id; 
                    $login_session['designation'] = $user_information->designation; 
                    $login_session['outlet_id'] = $company_info->outlet_id; 

                    //Company Information 

                    $login_session['currency'] = $setting_info->currency;
                    $login_session['time_zone'] = $setting_info->time_zone;
                    $login_session['date_format'] = $setting_info->date_format;

                    //Menu access information
                    $login_session['menu_access'] = $menu_access_container;


                    //Set session
                    $this->session->set_userdata($login_session);


                    $outlet_id = $company_info->outlet_id;
                    $outlet_session = array();
                    $outlet_session['outlet_id'] = $company_info->outlet_id;
                    $outlet_session['tax_is_gst'] = $outlet_details->tax_is_gst;
                    $outlet_session['gst_state_code'] = $outlet_details->state_code;
                    $outlet_session['outlet_name'] = $outlet_details->outlet_name;
                    $outlet_session['address'] = $outlet_details->address;
                    $outlet_session['phone'] = $outlet_details->phone;
                    $outlet_session['collect_tax'] = $outlet_details->collect_tax;
                    $outlet_session['tax_registration_no'] = $outlet_details->tax_registration_no;
                    $outlet_session['invoice_print'] = $outlet_details->invoice_print; 
                    $outlet_session['invoice_footer'] = $outlet_details->invoice_footer; 
                    $outlet_session['pre_or_post_payment'] = $outlet_details->pre_or_post_payment;
                    $this->session->set_userdata($outlet_session);
                } elseif($user_information->designation == 'Waiter') {

                    $login_session = array();
                    //User Information
                    $login_session['user_id'] = $user_information->id;
                    $login_session['language'] = $user_information->language;
                    $login_session['full_name'] = $user_information->full_name;
                    $login_session['designation'] = $user_information->designation;
                    $login_session['phone'] = $user_information->phone;
                    $login_session['email_address'] = $user_information->email_address; 
                    $login_session['role'] = $user_information->role;
                    $login_session['company_id'] = $user_information->company_id; 
                    $login_session['outlet_id'] = $company_info->outlet_id; 

                    //Company Information 

                    $login_session['currency'] = $setting_info->currency;
                    $login_session['time_zone'] = $setting_info->time_zone;
                    $login_session['date_format'] = $setting_info->date_format;

                    //Menu access information
                    $login_session['menu_access'] = $menu_access_container;


                    //Set session
                    $this->session->set_userdata($login_session);

                    $outlet_id = $company_info->outlet_id;
                    $outlet_session = array();
                    $outlet_session['outlet_id'] = $outlet_details->id;
                    $outlet_session['tax_is_gst'] = $outlet_details->tax_is_gst;
                    $outlet_session['gst_state_code'] = $outlet_details->state_code;
                    $outlet_session['outlet_name'] = $outlet_details->outlet_name;
                    $outlet_session['address'] = $outlet_details->address;
                    $outlet_session['outlet_phone'] = $outlet_details->phone;
                    $outlet_session['collect_tax'] = $outlet_details->collect_tax;
                    $outlet_session['tax_registration_no'] = $outlet_details->tax_registration_no;
                    $outlet_session['invoice_print'] = $outlet_details->invoice_print;  
                    $outlet_session['invoice_footer'] = $outlet_details->invoice_footer;  
                    $outlet_session['pre_or_post_payment'] = $outlet_details->pre_or_post_payment;  
                    $this->session->set_userdata($outlet_session);
                }
            } else {
                $data['error'] = ($data['error']=='')?'User is not active':'|User is not active';
            }
        } else {
            $data['error'] = ($data['error']=='')?'Incorrect Email/Password':'|Incorrect Email/Password';
        }
        // $data['users'] = $user_information;        
        $data['user_info'] = $this->session->userdata();        
        $this->set_response($data,REST_Controller::HTTP_OK);           
    }
    public function POS_get() {

        $company_id = $this->session->userdata('company_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['vatamount'] = $this->db->query("SELECT percentage FROM tbl_vats WHERE id=1")->row('percentage');
        $tables = $this->Sale_model->getTablesByOutletId($outlet_id);
        $data['tables'] = $this->getTablesDetails($tables);
        $data['categories'] = $this->Sale_model->getFoodMenuCategories($company_id, 'tbl_food_menu_categories');
        $data['item_menus'] = $this->Sale_model->getAllItemmenus();
        $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_customers');
        $data['food_menus'] = $this->Sale_model->getAllFoodMenus();
        $data['menu_categories'] = $this->Sale_model->getAllMenuCategories();
        $data['menu_modifiers'] = $this->Sale_model->getAllMenuModifiers();
        $data['waiters'] = $this->Common_model->getAllByOutletId($outlet_id,'tbl_users');
        $data['new_orders'] = $this->get_new_orders();
        $data['payment_methods'] = $this->Sale_model->getAllPaymentMethods();
        $data['notifications'] = $this->get_new_notification();
        $this->set_response($data,REST_Controller::HTTP_OK);

    }
    private function getTablesDetails($tables){
        foreach($tables as $table){
            $table->orders_table = $this->Sale_model->getOrdersOfTableByTableId($table->id);
            foreach($table->orders_table as $order_table){
                
                $to_time = strtotime(date('Y-m-d H:i:s'));
                $from_time = strtotime($order_table->booking_time);
                $minutes = floor(abs($to_time - $from_time) / 60);
                $seconds = abs($to_time - $from_time) % 60;

                $order_table->booked_in_minute = $minutes;
            }
        }
        return $tables;
    }
    private function get_new_orders(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $data1 = $this->Sale_model->getNewOrders($outlet_id);
        $i = 0;
        for($i;$i<count($data1);$i++){
            $data1[$i]->total_kitchen_type_items = $this->Sale_model->get_total_kitchen_type_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_done_items = $this->Sale_model->get_total_kitchen_type_done_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_started_cooking_items = $this->Sale_model->get_total_kitchen_type_started_cooking_items($data1[$i]->sale_id);
            $data1[$i]->tables_booked = $this->Sale_model->get_all_tables_of_a_sale_items($data1[$i]->sale_id);
            
            $to_time = strtotime(date('Y-m-d H:i:s'));
            $from_time = strtotime($data1[$i]->date_time);
            $minutes = floor(abs($to_time - $from_time) / 60);
            $seconds = abs($to_time - $from_time) % 60;

            $data1[$i]->minute_difference = str_pad(floor($minutes), 2, "0", STR_PAD_LEFT);
            $data1[$i]->second_difference = str_pad(floor($seconds), 2, "0", STR_PAD_LEFT);
        }
        return $data1;
    }
    private function get_new_notification(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Sale_model->getNotificationByOutletIdAndUserId($outlet_id,$user_id);
        return $notifications;
    }
    public function pos_info_get()
    {
        $data['name'] = 'Mohammad Al-Nur Sarwer (Arif)';
        $data['roll'] = 'CSE 01005';
        $data['session'] = $this->session->userdata();
        $this->set_response($data,REST_Controller::HTTP_OK);
    }
    public function get_all_holds_get(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $holds_information = $this->Sale_model->getHoldsByOutletAndUserId($outlet_id,$user_id);
        $this->set_response($holds_information,REST_Controller::HTTP_OK);
    }

    public function all_running_orders_get(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $running_orders_information = $this->Sale_model->getRunningOrdersByOutletAndWaiterId($outlet_id,$user_id);
        $i = 0;
        for($i;$i<count($running_orders_information);$i++){
            $running_orders_information[$i]->total_kitchen_type_items = $this->Sale_model->get_total_kitchen_type_items($running_orders_information[$i]->sale_id);
            $running_orders_information[$i]->total_kitchen_type_done_items = $this->Sale_model->get_total_kitchen_type_done_items($running_orders_information[$i]->sale_id);
            $running_orders_information[$i]->total_kitchen_type_started_cooking_items = $this->Sale_model->get_total_kitchen_type_started_cooking_items($running_orders_information[$i]->sale_id);
            $running_orders_information[$i]->tables_booked = $this->Sale_model->get_all_tables_of_a_sale_items($running_orders_information[$i]->sale_id);
            
            $to_time = strtotime(date('Y-m-d H:i:s'));
            $from_time = strtotime($running_orders_information[$i]->date_time);
            $minutes = floor(abs($to_time - $from_time) / 60);
            $seconds = abs($to_time - $from_time) % 60;

            $running_orders_information[$i]->minute_difference = str_pad(floor($minutes), 2, "0", STR_PAD_LEFT);
            $running_orders_information[$i]->second_difference = str_pad(floor($seconds), 2, "0", STR_PAD_LEFT);
        }
        $this->set_response($running_orders_information,REST_Controller::HTTP_OK);
    }
    public function add_sale_post(){
        $order_details = json_decode(json_decode($this->post['order']));
        // $orders_table = json_decode(json_decode($this->post['orders_table']));
        //this id will be 0 when there is new order, but will be greater then 0 when there is modification
        //on previous order
        $sale_id = $this->post['sale_id'];
        $close_order = $this->post['close_order'];
        // echo "<pre>"; var_dump($order_details->items); echo"</pre>"; exit;
        $data = array();
        $data['customer_id'] = trim($order_details->customer_id);
        $data['total_items'] = trim($order_details->total_items_in_cart);
        $data['sub_total'] = trim($order_details->sub_total);
        $data['vat'] = trim($order_details->total_vat);
        // if($order_details->selected_table!=0){
        //     $data['table_id'] = trim($order_details->selected_table);
        // }
        

        $data['total_payable'] = trim($order_details->total_payable);
        $data['total_item_discount_amount'] = trim($order_details->total_item_discount_amount);
        $data['sub_total_with_discount'] = trim($order_details->sub_total_with_discount);
        $data['sub_total_discount_amount'] = trim($order_details->sub_total_discount_amount);
        $data['total_discount_amount'] = trim($order_details->total_discount_amount);
        $data['delivery_charge'] = trim($order_details->delivery_charge);
        $data['sub_total_discount_value'] = trim($order_details->sub_total_discount_value);
        $data['sub_total_discount_type'] = trim($order_details->sub_total_discount_type);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['waiter_id'] = ($this->session->userdata('designation')=='Waiter')?$this->session->userdata('user_id'):$order_details->waiter_id;
        $data['outlet_id'] = $this->session->userdata('outlet_id');
        $data['sale_date'] = date('Y-m-d');
        $data['date_time'] = date('Y-m-d H:i:s');
        $data['order_time'] = date("H:i:s"); 
        $data['order_status'] = trim($order_details->order_status);
        $data['sale_vat_objects'] = json_encode($order_details->sale_vat_objects);
        $data['order_type'] = trim($order_details->order_type);
        $outlet_id = $this->session->userdata('outlet_id');
        // $sale_no = $this->db->query("SELECT count(id) as bno
        //        FROM tbl_sales WHERE outlet_id=$outlet_id")->row('bno');
        // $sale_no = str_pad($sale_no + 1, 6, '0', STR_PAD_LEFT);
        // $data['sale_no'] = $sale_no;
        $this->db->trans_begin();
        if($sale_id>0){
            $data['modified'] = 'Yes';
            $this->db->where('id', $sale_id);
            $this->db->update('tbl_sales', $data); 
            
            //this section sends notification to bar/kitchen panel if there is any modification 
            $single_table_information = $this->get_all_information_of_a_sale($sale_id);
            $order_number = '';
            if($single_table_information->order_type==1){
                $order_number = 'A '.$single_table_information->sale_no;
            }else if($single_table_information->order_type==2){
                $order_number = 'B '.$single_table_information->sale_no;
            }else if($single_table_information->order_type==3){
                $order_number = 'C '.$single_table_information->sale_no;
            }
            $notification_message = 'Order:'.$order_number.' has been modified';
            $bar_kitchen_notification_data = array();
            $bar_kitchen_notification_data['notification'] = $notification_message;
            $bar_kitchen_notification_data['outlet_id'] = $this->session->userdata('outlet_id');;
            $query = $this->db->insert('tbl_notification_bar_kitchen_panel', $bar_kitchen_notification_data);
            //end of send notification process

            $this->db->delete('tbl_sales_details', array('sales_id' => $sale_id));    
            $this->db->delete('tbl_sales_details_modifiers', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions', array('sale_id' => $sale_id));    
            $this->db->delete('tbl_sale_consumptions_of_menus', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions_of_modifiers_of_menus', array('sales_id' => $sale_id));

            $sales_id = $sale_id;
            $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
        }else{
            $query = $this->db->insert('tbl_sales', $data);
            $sales_id = $this->db->insert_id();
            $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
            $sale_no_update_array = array('sale_no' => $sale_no);
            $this->db->where('id', $sales_id);
            $this->db->update('tbl_sales', $sale_no_update_array);
        }
        foreach($order_details->orders_table as $single_order_table){
            $order_table_info = array();
            $order_table_info['persons'] = $single_order_table->persons;
            $order_table_info['booking_time'] = date('Y-m-d H:i:s');
            $order_table_info['sale_id'] = $sales_id;
            $order_table_info['sale_no'] = $sale_no;
            $order_table_info['outlet_id'] = $this->session->userdata('outlet_id');
            $order_table_info['table_id'] = $single_order_table->table_id; 
            
            $query = $this->db->insert('tbl_orders_table',$order_table_info);
        }
        $data_sale_consumptions = array();
        $data_sale_consumptions['sale_id'] = $sales_id;
        $data_sale_consumptions['user_id'] = $this->session->userdata('user_id'); 
        $data_sale_consumptions['outlet_id'] = $this->session->userdata('outlet_id');
        $data_sale_consumptions['del_status'] = 'Live'; 
        $query = $this->db->insert('tbl_sale_consumptions',$data_sale_consumptions);
        $sale_consumption_id = $this->db->insert_id();

        if($sales_id>0 && count($order_details->items)>0){
            foreach($order_details->items as $item){
                
                 
                $item_date = array();
                $item_data['food_menu_id'] = $item->item_id;
                $item_data['menu_name'] = $item->item_name;
                $item_data['qty'] = $item->item_quantity;
                $item_data['menu_price_without_discount'] = $item->item_price_without_discount;
                $item_data['menu_price_with_discount'] = $item->item_price_with_discount;
                $item_data['menu_unit_price'] = $item->item_unit_price;
                $item_data['menu_taxes'] = json_encode($item->item_vat);
                $item_data['menu_discount_value'] = $item->item_discount;
                $item_data['discount_type'] = $item->discount_type;
                $item_data['menu_note'] = $item->item_note;
                $item_data['discount_amount'] = $item->item_discount_amount;
                $item_data['item_type'] = ($this->Sale_model->getItemType($item->item_id)->item_type=="Bar No")?"Kitchen Item":"Bar Item";
                $item_data['cooking_status'] = ($item->item_cooking_status=="")?NULL:$item->item_cooking_status;
                $item_data['cooking_start_time'] = ($item->item_cooking_start_time=="" || $item->item_cooking_start_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_start_time));
                $item_data['cooking_done_time'] = ($item->item_cooking_done_time=="" || $item->item_cooking_done_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_done_time));
                $item_data['previous_id'] = ($item->item_previous_id=="")?0:$item->item_previous_id;
                $item_data['sales_id'] = $sales_id;
                $item_data['user_id'] = $this->session->userdata('user_id');
                $item_data['outlet_id'] = $this->session->userdata('outlet_id');
                $item_data['del_status'] = 'Live';
                $query = $this->db->insert('tbl_sales_details', $item_data);
                $sales_details_id = $this->db->insert_id();

                if($item->item_previous_id==""){
                    $previous_id_update_array = array('previous_id' => $sales_details_id);
                    $this->db->where('id', $sales_details_id);
                    $this->db->update('tbl_sales_details', $previous_id_update_array);
                }
                
                
                $food_menu_ingredients = $this->db->query("SELECT * FROM tbl_food_menus_ingredients WHERE food_menu_id=$item->item_id")->result();

                foreach($food_menu_ingredients as $single_ingredient){
                    
                    $data_sale_consumptions_detail = array();
                    $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                    $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption; 
                    $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                    $data_sale_consumptions_detail['sales_id'] = $sales_id;
                    $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                    $data_sale_consumptions_detail['user_id'] = $this->session->userdata('outlet_id');
                    $data_sale_consumptions_detail['outlet_id'] = $this->session->userdata('outlet_id');
                    $data_sale_consumptions_detail['del_status'] = 'Live'; 
                    $query = $this->db->insert('tbl_sale_consumptions_of_menus',$data_sale_consumptions_detail);    
                }

                $modifier_id_array = ($item->modifiers_id!="")?explode(",",$item->modifiers_id):null;
                $modifier_price_array = ($item->modifiers_price!="")?explode(",",$item->modifiers_price):null;

                if(!empty($modifier_id_array)>0){
                    $i = 0;
                    foreach($modifier_id_array as $single_modifier_id){
                        $modifier_data = array();
                        $modifier_data['modifier_id'] =$single_modifier_id; 
                        $modifier_data['modifier_price'] = $modifier_price_array[$i]; 
                        $modifier_data['food_menu_id'] = $item->item_id; 
                        $modifier_data['sales_id'] = $sales_id; 
                        $modifier_data['sales_details_id'] = $sales_details_id; 
                        $modifier_data['user_id'] = $this->session->userdata('user_id');
                        $modifier_data['outlet_id'] = $this->session->userdata('outlet_id'); 
                        $modifier_data['customer_id'] =$order_details->customer_id;
                        $query = $this->db->insert('tbl_sales_details_modifiers', $modifier_data);
                        
                        $modifier_ingredients = $this->db->query("SELECT * FROM tbl_modifier_ingredients WHERE modifier_id=$single_modifier_id")->result();

                        foreach($modifier_ingredients as $single_ingredient){
                            $data_sale_consumptions_detail = array();
                            $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                            $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption; 
                            $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                            $data_sale_consumptions_detail['sales_id'] = $sales_id;
                            $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                            $data_sale_consumptions_detail['user_id'] = $this->session->userdata('outlet_id');
                            $data_sale_consumptions_detail['outlet_id'] = $this->session->userdata('outlet_id');
                            $data_sale_consumptions_detail['del_status'] = 'Live'; 
                            $query = $this->db->insert('tbl_sale_consumptions_of_modifiers_of_menus',$data_sale_consumptions_detail);    
                        }

                        $i++;
                    }    
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->set_response($sales_id,REST_Controller::HTTP_OK);
            
            $this->db->trans_commit();
        }
    }
    public function all_tables_with_new_status_get(){
        $outlet_id = $this->session->userdata('outlet_id');
        $tables = $this->Sale_model->getTablesByOutletId($outlet_id);
        $data1 = new \stdClass();
        $data1->table_details = $this->getTablesDetails($tables);
        $data1->table_availability = $this->Sale_model->getTableAvailability($outlet_id);
        // $data1 = $this->Sale_model->getAllTablesWithNewStatus($outlet_id);        
        $this->set_response($data1,REST_Controller::HTTP_OK);
    }
    public function remove_a_table_booking_post()
    {
        $orders_table_id = $this->post['orders_table_id'];
        $orders_table_single_info = $this->Common_model->getDataById($orders_table_id, "tbl_orders_table");        
        $this->db->delete('tbl_orders_table', array('id' => $orders_table_id));         
        $this->set_response($orders_table_single_info,REST_Controller::HTTP_OK);
    }

    public function get_outlet_id_by_code_get()
    {
        $code = $_GET['code'];
        $row = $this->Desktop_api_model->get_outlet_id_by_code_get($code);
        $output['status'] = false;
        if($row){
            $output['status'] = true;
            $output['id'] = $row->id;
        }
        $this->set_response($output,REST_Controller::HTTP_OK);
    }

    public function all_information_of_a_sale_post(){
        $sales_id = $this->post['sale_id'];
        $sale_object = $this->get_all_information_of_a_sale($sales_id);
        $this->set_response($sale_object,REST_Controller::HTTP_OK);
    }

    public function get_all_information_of_a_sale($sales_id){
        $sales_information = $this->Sale_model->getSingleSaleBySaleId($sales_id);
        $items_by_sales_id = $this->Sale_model->getAllItemsFromSalesDetailBySalesId($sales_id);
        foreach($items_by_sales_id as $single_item_by_sale_id){
            $modifier_information = $this->Sale_model->getModifiersBySaleAndSaleDetailsId($sales_id,$single_item_by_sale_id->sales_details_id);
            $single_item_by_sale_id->modifiers = $modifier_information;
        }
        $sales_details_objects = $items_by_sales_id;
        $sale_object = $sales_information;
        $sale_object->items = $sales_details_objects;
        $sale_object->tables_booked = $this->Sale_model->get_all_tables_of_a_sale_items($sales_id);
        return $sale_object;
    }
    public function cancel_particular_order_post()
    {
        $sale_id = $this->input->post('sale_id');
        $this->delete_specific_order_by_sale_id($sale_id); 
    }
    public function delete_specific_order_by_sale_id($sale_id){
        $this->db->delete('tbl_sales', array('id' => $sale_id));    
        $this->db->delete('tbl_sales_details', array('sales_id' => $sale_id));    
        $this->db->delete('tbl_sales_details_modifiers', array('sales_id' => $sale_id));
        $this->db->delete('tbl_sale_consumptions', array('sale_id' => $sale_id));    
        $this->db->delete('tbl_sale_consumptions_of_menus', array('sales_id' => $sale_id));
        $this->db->delete('tbl_sale_consumptions_of_modifiers_of_menus', array('sales_id' => $sale_id));
        $this->db->delete('tbl_orders_table', array('sale_id' => $sale_id));
        return true;
    }
    public function my_todays_sales_get(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $sales_information = $this->Sale_model->getMyTodaysSalesByOutletAndUserId($outlet_id,$user_id);
        foreach($sales_information as $single_sale_information){
            $single_sale_information->tables_booked = $this->Sale_model->get_all_tables_of_a_last_sale($single_sale_information->id);
        }
        $this->set_response($sales_information,REST_Controller::HTTP_OK);
    }
    public function remove_notication_post()
    {
        $notification_id = $this->post['notification_id'];
        $this->db->delete('tbl_notifications', array('id' => $notification_id));
        $this->set_response($notification_id,REST_Controller::HTTP_OK);        
    }
    public function remove_multiple_notification_post()
    {
        $notifications = $this->post['notifications'];
        $notifications_array = explode(",",$notifications);
        foreach($notifications_array as $single_notification){
            $this->db->delete('tbl_notifications', array('id' => $single_notification));
        } 
    }
    public function new_notifications_post()
    {
        $this->set_response($this->get_new_notification(),REST_Controller::HTTP_OK);
    }
    function new_hold_number_get(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $number_of_holds_of_this_user_and_outlet = $this->get_current_hold();
        $number_of_holds_of_this_user_and_outlet++;
        echo $number_of_holds_of_this_user_and_outlet;
    }
    public function all_users_get()
    {
        $outlet_id = $_GET['outlet_id'];
        $this->set_response($this->Desktop_api_model->get_all_users_by_outlet($outlet_id),REST_Controller::HTTP_OK);
    }
    public function all_outlets_get()
    {
        $this->set_response($this->Common_model->getAllByTable('tbl_outlets'),REST_Controller::HTTP_OK);      
    }
    public function all_settings_get()
    {
        $this->set_response($this->Common_model->getAllByTable('tbl_settings'),REST_Controller::HTTP_OK);      
    }
    function get_current_hold(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $number_of_holds = $this->Sale_model->getNumberOfHoldsByUserAndOutletId($outlet_id,$user_id);
        return $number_of_holds;
    }
    public function add_hold_post()
    {
        $order_details = json_decode(json_decode($this->post['order']));
        $hold_number = trim($this->post['hold_number']);
        // echo "<pre>"; var_dump($order_details->items); echo"</pre>"; exit;
        $data = array();
        $data['customer_id'] = trim($order_details->customer_id);
        $data['total_items'] = trim($order_details->total_items_in_cart);
        $data['sub_total'] = trim($order_details->sub_total);
        $data['vat'] = trim($order_details->total_vat);
        $data['table_id'] = trim($order_details->selected_table);

        $data['total_payable'] = trim($order_details->total_payable);
        $data['total_item_discount_amount'] = trim($order_details->total_item_discount_amount);
        $data['sub_total_with_discount'] = trim($order_details->sub_total_with_discount);
        $data['sub_total_discount_amount'] = trim($order_details->sub_total_discount_amount);
        $data['total_discount_amount'] = trim($order_details->total_discount_amount);
        $data['delivery_charge'] = trim($order_details->delivery_charge);
        $data['sub_total_discount_value'] = trim($order_details->sub_total_discount_value);
        $data['sub_total_discount_type'] = trim($order_details->sub_total_discount_type);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['waiter_id'] = trim($order_details->waiter_id);
        $data['outlet_id'] = $this->session->userdata('outlet_id');
        $data['sale_date'] = date('Y-m-d');
        $data['sale_time'] = date('Y-m-d h:i A');
        $data['order_status'] = trim($order_details->order_status);
        $data['sale_vat_objects'] = json_encode($order_details->sale_vat_objects);
        $data['order_type'] = trim($order_details->order_type);
        $outlet_id = $this->session->userdata('outlet_id');
        if($hold_number===0 || $hold_number===""){
            $current_hold_order = $this->get_current_hold();
            echo "current hold".$current_hold_order."<br/>";
            $hold_number = $current_hold_order+1;
        }
        $data['hold_no'] = $hold_number;
        $query = $this->db->insert('tbl_holds', $data);
        $holds_id = $this->db->insert_id();
        
        if($holds_id>0 && count($order_details->items)>0){
            foreach($order_details->items as $item){
                $item_date = array();
                $item_data['food_menu_id'] = $item->item_id;
                $item_data['menu_name'] = $item->item_name;
                $item_data['qty'] = $item->item_quantity;
                $item_data['menu_price_without_discount'] = $item->item_price_without_discount;
                $item_data['menu_price_with_discount'] = $item->item_price_with_discount;
                $item_data['menu_unit_price'] = $item->item_unit_price;
                // $item_data['menu_vat_percentage'] = $item->item_vat;
                $item_data['menu_taxes'] = json_encode($item->item_vat);
                $item_data['menu_discount_value'] = $item->item_discount;
                $item_data['discount_type'] = $item->discount_type;
                $item_data['menu_note'] = $item->item_note;
                $item_data['discount_amount'] = $item->item_discount_amount;
                $item_data['holds_id'] = $holds_id;
                $item_data['user_id'] = $this->session->userdata('user_id');
                $item_data['outlet_id'] = $this->session->userdata('outlet_id');
                $item_data['del_status'] = 'Live';
                $query = $this->db->insert('tbl_holds_details', $item_data);
                $holds_details_id = $this->db->insert_id();
                
                $modifier_id_array = ($item->modifiers_id!="")?explode(",",$item->modifiers_id):null;
                $modifier_price_array = ($item->modifiers_price!="")?explode(",",$item->modifiers_price):null;

                if(!empty($modifier_id_array)>0){
                    $i = 0;
                    foreach($modifier_id_array as $single_modifier_id){
                        $modifier_data = array();
                        $modifier_data['modifier_id'] =$single_modifier_id; 
                        $modifier_data['modifier_price'] = $modifier_price_array[$i]; 
                        $modifier_data['food_menu_id'] = $item->item_id; 
                        $modifier_data['holds_id'] = $holds_id; 
                        $modifier_data['holds_details_id'] = $holds_details_id; 
                        $modifier_data['user_id'] = $this->session->userdata('user_id');
                        $modifier_data['outlet_id'] = $this->session->userdata('outlet_id'); 
                        $modifier_data['customer_id'] =$order_details->customer_id;
                        $query = $this->db->insert('tbl_holds_details_modifiers', $modifier_data);
                        
                        $i++;
                    }    
                }
            }
        }
        $this->set_response($holds_id,REST_Controller::HTTP_OK);        
    }
    public function single_hold_info_post()
    {
        $hold_id = $this->post['hold_id'];
        $hold_information = $this->Sale_model->get_hold_info_by_hold_id($hold_id);
        $items_by_holds_id = $this->Sale_model->getAllItemsFromHoldsDetailByHoldsId($hold_id);
        foreach($items_by_holds_id as $single_item_by_hold_id){
            $modifier_information = $this->Sale_model->getModifiersByHoldAndHoldsDetailsId($hold_id,$single_item_by_hold_id->holds_details_id);
            $single_item_by_hold_id->modifiers = $modifier_information;
        }
        $holds_details_objects = $items_by_holds_id;
        $hold_object = $hold_information[0];
        $hold_object->items = $holds_details_objects;
        $this->set_response($hold_object,REST_Controller::HTTP_OK);

    }
    function add_customer_post(){
        $dob = explode("-",$this->post['customer_dob']);
        $doa = explode("-",$this->post['customer_doa']);

        $dob2 = explode(" - ",$this->post['customer_dob']);
        $doa2 = explode(" - ",$this->post['customer_doa']);

        
        $full_dob = null;
        $full_doa = null;
        if(count($dob)==3){
            $full_dob = trim($dob[0]).'-'.trim($dob[1]).'-'.trim($dob[2]);     
        }elseif(count($dob2)==3){
            $full_dob = trim($dob2[0]).'-'.trim($dob2[1]).'-'.trim($dob2[2]);
        }
        if(count($doa)==3){
            $full_doa = trim($doa[0]).'-'.trim($doa[1]).'-'.trim($doa[2]);
        }if(count($doa2)==3){
            $full_doa = trim($doa2[0]).'-'.trim($doa2[1]).'-'.trim($doa2[2]);
        }

        $customer_id = $this->post['customer_id'];
        $data['name'] = trim(htmlspecialchars($this->post['customer_name']));
        $data['phone'] = trim(htmlspecialchars($this->post['customer_phone']));
        $data['email'] = trim($this->post['customer_email']);
        $data['date_of_birth'] = date('Y-m-d',strtotime($full_dob));
        $data['date_of_anniversary'] = date('Y-m-d',strtotime($full_doa));
        $data['address'] = trim(preg_replace('/\s+/', ' ', $this->post['customer_delivery_address']));
        $data['gst_number'] = trim($this->post['customer_gst_number']);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['company_id'] = $this->session->userdata('company_id');
        if($customer_id>0 && $customer_id!=""){
            $this->db->where('id', $customer_id);
            $this->db->update('tbl_customers', $data); 
        }else{
            $this->db->insert('tbl_customers', $data);
        }
        $this->set_response(1,REST_Controller::HTTP_OK);
    }
    function all_customers_for_this_user_get(){
        $company_id = $this->session->userdata('company_id');
        $data1 = $this->db->query("SELECT * FROM tbl_customers 
              WHERE company_id=$company_id")->result();  
        $this->set_response($data1,REST_Controller::HTTP_OK);
    }
    public function get_customer_post()
    {
        $customer_id = $this->post['customer_id'];
        $customer_info = $this->Sale_model->getCustomerInfoById($customer_id);
        $this->set_response($customer_info,REST_Controller::HTTP_OK);
    }
    private function logOut() {
        //User Information 
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('full_name');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('email_address');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('designation');
        $this->session->unset_userdata('company_id');

        //Shop Information
        $this->session->unset_userdata('outlet_id');
        $this->session->unset_userdata('outlet_name');
        $this->session->unset_userdata('company_time_zone');
        $this->session->unset_userdata('address');
        $this->session->unset_userdata('first_name');
        $this->session->unset_userdata('invoice_footer');
        $this->session->unset_userdata('language');
        $this->session->unset_userdata('last_name');
        $this->session->unset_userdata('menu_access');
        $this->session->unset_userdata('merchant_id');
        $this->session->unset_userdata('next_expiry');
        $this->session->unset_userdata('outlet_phone');
        $this->session->unset_userdata('pre_or_post_payment');
        $this->session->unset_userdata('vat_reg_no');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('collect_tax');
        $this->session->unset_userdata('collect_vat');
        $this->session->unset_userdata('tax_registration_no');
        $this->session->unset_userdata('invoice_print');
        $this->session->unset_userdata('print_select');
        $this->session->unset_userdata('kot_print');

        //company Information
        $this->session->unset_userdata('currency');
        $this->session->unset_userdata('time_zone');
        $this->session->unset_userdata('date_format');

    }
    public function upload_sales_post(){
                

        $sales_information = json_decode($this->post['sales']);
        $customers_information = json_decode($this->post['customers']);

        $data['uploaded_sales_id'] = $this->add_sales_to_database($sales_information);
        $data['uploaded_customers_id'] = $this->add_customers_to_database($customers_information);

        $this->set_response($data,REST_Controller::HTTP_OK);
        
    }
    private function add_customers_to_database($customers){
        $uploaded_customers = array();
        if(count($customers)>0){
            foreach($customers as $single_customer){

                if(isset($single_customer->new)){
                    $dob = explode("-",$single_customer->date_of_birth);
                    $doa = explode("-",$single_customer->date_of_anniversary);

                    $dob2 = explode(" - ",$single_customer->date_of_birth);
                    $doa2 = explode(" - ",$single_customer->date_of_anniversary);
                    $full_dob = null;
                    $full_doa = null;
                    if(count($dob)==3){
                        $full_dob = trim($dob[0]).'-'.trim($dob[1]).'-'.trim($dob[2]);     
                    }elseif(count($dob2)==3){
                        $full_dob = trim($dob2[0]).'-'.trim($dob2[1]).'-'.trim($dob2[2]);
                    }
                    if(count($doa)==3){
                        $full_doa = trim($doa[0]).'-'.trim($doa[1]).'-'.trim($doa[2]);
                    }if(count($doa2)==3){
                        $full_doa = trim($doa2[0]).'-'.trim($doa2[1]).'-'.trim($doa2[2]);
                    }
                    $data['id'] = trim(htmlspecialchars($single_customer->id));
                    $data['name'] = trim(htmlspecialchars($single_customer->name));
                    $data['phone'] = trim(htmlspecialchars($single_customer->phone));
                    $data['email'] = trim($single_customer->email);
                    $data['date_of_birth'] = date('Y-m-d',strtotime($full_dob));
                    $data['date_of_anniversary'] = date('Y-m-d',strtotime($full_doa));
                    $data['address'] = trim(preg_replace('/\s+/', ' ', $single_customer->address));
                    $data['gst_number'] = trim($single_customer->gst_number);
                    $data['user_id'] = $single_customer->user_id;
                    $data['company_id'] = $single_customer->company_id;
                    if($single_customer->new=="Yes"){
                    $this->db->insert('tbl_customers', $data);
                }else{
                    $this->db->update('tbl_customers', $data,array('id'=>$single_customer->id));
                }
                    
                    array_push($uploaded_customers,$single_customer->id);
                }
            }            
        }
        return $uploaded_customers;
    }
    private function add_sales_to_database($sales){
        $uploaded_sales = array();
        if(count($sales)>0){

            foreach($sales as $single_sale){
                $user_info = $single_sale->userInfo;
                $data = array();
                $data['customer_id'] = trim($single_sale->customer_id);
                $data['sale_no'] = trim($single_sale->ref_no);
                $data['total_items'] = trim($single_sale->total_items_in_cart);
                $data['sub_total'] = trim($single_sale->sub_total);
                $data['vat'] = trim($single_sale->total_vat);
                $data['total_payable'] = trim($single_sale->total_payable);
                $data['total_item_discount_amount'] = trim($single_sale->total_item_discount_amount);
                $data['sub_total_with_discount'] = trim($single_sale->sub_total_with_discount);
                $data['sub_total_discount_amount'] = trim($single_sale->sub_total_discount_amount);
                $data['total_discount_amount'] = trim($single_sale->total_discount_amount);
                $data['delivery_charge'] = trim($single_sale->delivery_charge);
                $data['sub_total_discount_value'] = trim($single_sale->sub_total_discount_value);
                $data['sub_total_discount_type'] = trim($single_sale->sub_total_discount_type);
                $data['user_id'] = $user_info->user_id;
                $data['waiter_id'] = trim($single_sale->waiter_id);
                $data['outlet_id'] = $user_info->outlet_id;
                $data['sale_date'] = date('Y-m-d',strtotime($single_sale->order_time));
                $data['date_time'] = date('Y-m-d H:i:s',strtotime($single_sale->order_time));
                $data['order_time'] = date("H:i:s",strtotime($single_sale->order_time)); 
                $data['order_status'] = trim($single_sale->order_status);
                $data['sale_vat_objects'] = json_encode($single_sale->sale_vat_objects);
                $data['order_type'] = trim($single_sale->order_type);
                $outlet_id = $user_info->outlet_id;

                $this->db->trans_begin();
                
                $query = $this->db->insert('tbl_sales', $data);
                $sales_id = $this->db->insert_id();
                $sale_no = $single_sale->ref_no;

                foreach($single_sale->orders_table as $single_order_table){
                    $order_table_info = array();
                    $order_table_info['persons'] = $single_order_table->persons;
                    $order_table_info['booking_time'] = date('Y-m-d H:i:s',strtotime($single_sale->order_time));
                    $order_table_info['sale_id'] = $sales_id;
                    $order_table_info['sale_no'] = $sale_no;
                    $order_table_info['outlet_id'] = $outlet_id;
                    $order_table_info['table_id'] = $single_order_table->table_id; 
                    
                    $query = $this->db->insert('tbl_orders_table',$order_table_info);
                }
                $data_sale_consumptions = array();
                $data_sale_consumptions['sale_id'] = $sales_id;
                $data_sale_consumptions['user_id'] = $user_info->user_id; 
                $data_sale_consumptions['outlet_id'] = $outlet_id;
                $data_sale_consumptions['del_status'] = 'Live'; 
                $query = $this->db->insert('tbl_sale_consumptions',$data_sale_consumptions);
                $sale_consumption_id = $this->db->insert_id();

                if($sales_id>0 && count($single_sale->items)>0){
                    foreach($single_sale->items as $item){
                        
                         
                        $item_date = array();
                        $item_data['food_menu_id'] = $item->item_id;
                        $item_data['menu_name'] = $item->item_name;
                        $item_data['qty'] = $item->item_quantity;
                        $item_data['menu_price_without_discount'] = $item->item_price_without_discount;
                        $item_data['menu_price_with_discount'] = $item->item_price_with_discount;
                        $item_data['menu_unit_price'] = $item->item_unit_price;
                        $item_data['menu_taxes'] = json_encode($item->item_vat);
                        $item_data['menu_discount_value'] = $item->item_discount;
                        $item_data['discount_type'] = $item->discount_type;
                        $item_data['menu_note'] = $item->item_note;
                        $item_data['discount_amount'] = $item->item_discount_amount;
                        $item_data['item_type'] = ($this->Sale_model->getItemType($item->item_id)->item_type=="Bar No")?"Kitchen Item":"Bar Item";
                        $item_data['cooking_status'] = ($item->item_cooking_status=="")?NULL:$item->item_cooking_status;
                        $item_data['cooking_start_time'] = ($item->item_cooking_start_time=="" || $item->item_cooking_start_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_start_time));
                        $item_data['cooking_done_time'] = ($item->item_cooking_done_time=="" || $item->item_cooking_done_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_done_time));
                        $item_data['previous_id'] = 0;
                        $item_data['sales_id'] = $sales_id;
                        $item_data['user_id'] = $user_info->user_id;
                        $item_data['outlet_id'] = $outlet_id;
                        $item_data['del_status'] = 'Live';
                        $query = $this->db->insert('tbl_sales_details', $item_data);
                        $sales_details_id = $this->db->insert_id();

                        $previous_id_update_array = array('previous_id' => $sales_details_id);
                        $this->db->where('id', $sales_details_id);
                        $this->db->update('tbl_sales_details', $previous_id_update_array);
                        
                        
                        $food_menu_ingredients = $this->db->query("SELECT * FROM tbl_food_menus_ingredients WHERE food_menu_id=$item->item_id")->result();

                        foreach($food_menu_ingredients as $single_ingredient){
                            
                            $data_sale_consumptions_detail = array();
                            $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                            $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption; 
                            $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                            $data_sale_consumptions_detail['sales_id'] = $sales_id;
                            $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                            $data_sale_consumptions_detail['user_id'] = $user_info->user_id;
                            $data_sale_consumptions_detail['outlet_id'] = $outlet_id;
                            $data_sale_consumptions_detail['del_status'] = 'Live'; 
                            $query = $this->db->insert('tbl_sale_consumptions_of_menus',$data_sale_consumptions_detail);    
                        }

                        $modifier_id_array = ($item->modifiers_id!="")?explode(",",$item->modifiers_id):null;
                        $modifier_price_array = ($item->modifiers_price!="")?explode(",",$item->modifiers_price):null;

                        if(!empty($modifier_id_array)>0){
                            $i = 0;
                            foreach($modifier_id_array as $single_modifier_id){
                                $modifier_data = array();
                                $modifier_data['modifier_id'] =$single_modifier_id; 
                                $modifier_data['modifier_price'] = $modifier_price_array[$i]; 
                                $modifier_data['food_menu_id'] = $item->item_id; 
                                $modifier_data['sales_id'] = $sales_id; 
                                $modifier_data['sales_details_id'] = $sales_details_id; 
                                $modifier_data['user_id'] = $user_info->user_id;
                                $modifier_data['outlet_id'] = $outlet_id; 
                                $modifier_data['customer_id'] =$single_sale->customer_id;
                                $query = $this->db->insert('tbl_sales_details_modifiers', $modifier_data);
                                
                                $modifier_ingredients = $this->db->query("SELECT * FROM tbl_modifier_ingredients WHERE modifier_id=$single_modifier_id")->result();

                                foreach($modifier_ingredients as $single_ingredient){
                                    $data_sale_consumptions_detail = array();
                                    $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                                    $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption; 
                                    $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                                    $data_sale_consumptions_detail['sales_id'] = $sales_id;
                                    $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                                    $data_sale_consumptions_detail['user_id'] = $user_info->user_id;
                                    $data_sale_consumptions_detail['outlet_id'] = $outlet_id;
                                    $data_sale_consumptions_detail['del_status'] = 'Live'; 
                                    $query = $this->db->insert('tbl_sale_consumptions_of_modifiers_of_menus',$data_sale_consumptions_detail);    
                                }

                                $i++;
                            }    
                        }
                    }
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    // echo $sales_id;
                    array_push($uploaded_sales,$single_sale->id);
                    $this->db->trans_commit();
                }
            }
        }
        return $uploaded_sales;
        
    }

}
