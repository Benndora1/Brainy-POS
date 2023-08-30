<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    public function index() {

 if ($this->session->userdata('user_id')) {
            //If the user is Super Admin
            if ($this->session->userdata('role') == 'Super Admin') { 
                redirect("Admin/adminProfile");
            } elseif ($this->session->userdata('role') == 'Admin') {        
				redirect("Sale/POS");
            } elseif($this->session->userdata('role') == 'Kitchen User'){               
				redirect("Kitchen/panel");
			} elseif($this->session->userdata('role') == 'POS User'){          
				redirect("Sale/POS");
            } elseif($this->session->userdata('role') == 'Bar User'){ 
				redirect("Bar/panel");
            } elseif($this->session->userdata('role') == 'Waiter User'){ 
                redirect("Waiter/panel");
            } else {
                if (in_array('Sale', $this->session->userdata('menu_access'))) { 
                    redirect("Sale/POS");
                }
                if (in_array('Kitchen', $this->session->userdata('menu_access'))) {
                    redirect("Kitchen/panel");
                }
                if (in_array('Bar', $this->session->userdata('menu_access'))) {
                    redirect("Bar/panel");
                }
                if (in_array('Waiter', $this->session->userdata('menu_access'))) {
                    redirect("Waiter/panel");
                } 		
                redirect("Authentication/userProfile");
            }
        }

        $this->load->view('authentication/login');
    }

    public function loginCheck() {
        if ($this->input->post('submit') != 'submit') {
            redirect("Authentication/index");
        }

        $this->form_validation->set_rules('email_address', lang('email'), 'required|valid_email');
        $this->form_validation->set_rules('password', lang('password'), "required|max_length[25]");
        if ($this->form_validation->run() == TRUE) {
            $email_address = $this->input->post($this->security->xss_clean('email_address'));
            $password = $this->input->post($this->security->xss_clean('password'));
            $user_information = $this->Authentication_model->getUserInformation($email_address, $password);


            //If user exists
            if ($user_information) {

                //If the user is Active
                if ($user_information->active_status == 'Active') {
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


                    $login_session = array();
                    //User Information
                    $login_session['user_id'] = $user_information->id;
                    $login_session['language'] = $user_information->language;
                    $login_session['full_name'] = $user_information->full_name;
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

                    $outlet_details = $this->Common_model->getDataById($company_info->outlet_id, 'tbl_outlets');

                    if ($user_information->role == 'Admin') {
                        // redirect("Outlet/outlets");
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
                        redirect("Sale/POS");
                    } else {
                        if($user_information->role=="Kitchen User"){
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
                            redirect("Kitchen/panel");
                        }elseif($user_information->role=="Bar User"){
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
                            redirect("Bar/panel");
                        }elseif($user_information->role=="POS User")
                        {
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
                            redirect("Sale/POS");
                        }else{
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
                            if (in_array('Sale', $this->session->userdata('menu_access'))) { 
                                redirect("Sale/POS");
                            }
                            if (in_array('Kitchen', $this->session->userdata('menu_access'))) {
                                redirect("Kitchen/panel");
                            }
                            if (in_array('Bar', $this->session->userdata('menu_access'))) {
                                redirect("Bar/panel");
                            }
                            if (in_array('Waiter', $this->session->userdata('menu_access'))) {
                                redirect("Waiter/panel");
                            }
                            redirect("Authentication/userProfile");    
                        }

                        
                    }
                } else {
                    $this->session->set_flashdata('exception_1', lang('user_not_active'));
                    redirect('Authentication/index');
                }
            } else {
                $this->session->set_flashdata('exception_1', lang('incorrect_email_password'));
                redirect('Authentication/index');
            }
        } else {
            $this->load->view('authentication/login');
        }
    }

    public function paymentNotClear() {
        if (!$this->session->has_userdata('customer_id')) {
            redirect('Authentication/index');
        }
        $this->load->view('authentication/paymentNotClear');
    }

    public function userProfile() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if($this->session->userdata('role') == 'Kitchen User'){
            redirect("Kitchen/panel");
        }
        if($this->session->userdata('role') == 'Bar User'){
            redirect("Bar/panel");
        }
        if($this->session->userdata('role') == 'Waiter User'){
            redirect("Waiter/panel");
        }
        if($this->session->userdata('role') == 'POS User'){
            redirect("Sale/POS");
        }
        $data = array();
        $data['main_content'] = $this->load->view('authentication/userProfile', $data, TRUE);
        $this->load->view('userHome', $data);
    }

    public function companyProfile() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $data = array();
        $company_id = $this->session->userdata('company_id');
        $data['company_information'] = $this->Common_model->getDataById($company_id, 'tbl_companies');
        $data['main_content'] = $this->load->view('authentication/updateCompanyProfile', $data, TRUE);
        $this->load->view('outlet/outletHome', $data);
    }

    public function changePassword() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if ($this->input->post('submit') == 'submit') {
            $this->form_validation->set_rules('old_password',lang('old_password'), 'required|max_length[50]');
            $this->form_validation->set_rules('new_password', lang('new_password'), 'required|max_length[50]|min_length[6]');
            if ($this->form_validation->run() == TRUE) {
                $old_password = $this->input->post($this->security->xss_clean('old_password'));
                $user_id = $this->session->userdata('user_id');

                $password_check = $this->Authentication_model->passwordCheck($old_password, $user_id);

                if ($password_check) {
                    $new_password = $this->input->post($this->security->xss_clean('new_password'));

                    $this->Authentication_model->updatePassword($new_password, $user_id);

                    mail($this->session->userdata['email_address'], "Change Password", "Your new password is : " . $new_password);

                    $this->session->set_flashdata('exception',lang('password_changed'));
                    redirect('Authentication/changePassword');
                } else {
                    $this->session->set_flashdata('exception_1',lang('old_password_not_match'));
                    redirect('Authentication/changePassword');
                }
            } else {
                $data = array();
                $data['main_content'] = $this->load->view('authentication/changePassword', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['main_content'] = $this->load->view('authentication/changePassword', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }

    public function passwordChange() {

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if ($this->input->post('submit') == 'submit') {
            $this->form_validation->set_rules('old_password',lang('old_password'), 'required|max_length[50]');
            $this->form_validation->set_rules('new_password', lang('new_password'), 'required|max_length[50]|min_length[6]');
            if ($this->form_validation->run() == TRUE) {
                $old_password = $this->input->post($this->security->xss_clean('old_password'));
                $user_id = $this->session->userdata('user_id');

                $password_check = $this->Authentication_model->passwordCheck($old_password, $user_id);

                if ($password_check) {
                    $new_password = $this->input->post($this->security->xss_clean('new_password'));

                    $this->Authentication_model->updatePassword($new_password, $user_id);

                    $this->session->set_flashdata('exception', lang('password_changed'));
                    redirect('Authentication/passwordChange');
                } else {
                    $this->session->set_flashdata('exception_1', lang('old_password_not_match'));
                    redirect('Authentication/passwordChange');
                }
            } else {
                $data = array();
                $data['main_content'] = $this->load->view('authentication/passwordChange', $data, TRUE);
                $this->load->view('outlet/outletHome', $data);
            }
        } else {
            $data = array();
            $data['main_content'] = $this->load->view('authentication/passwordChange', $data, TRUE);
            $this->load->view('outlet/outletHome', $data);
        }
    }

    public function forgotPassword() {
        $this->load->view('authentication/forgotPassword');
    }

    public function sendAutoPassword() {
        if ($this->input->post('submit') == 'submit') {
            $this->form_validation->set_rules('email_address', lang('email_address'), 'required|valid_email|callback_checkEmailAddressExistance');
            if ($this->form_validation->run() == TRUE) {
                $email_address = $this->input->post($this->security->xss_clean('email_address'));

                $user_details = $this->Authentication_model->getAccountByMobileNo($email_address);

                $user_id = $user_details->id;

                $auto_generated_password = mt_rand(100000, 999999);

                $this->Authentication_model->updatePassword($auto_generated_password, $user_id);

                //Send Password by Email
                $this->load->library('email');

                $config['protocol'] = 'sendmail';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = TRUE;
                $this->email->initialize($config);

                mail($email_address, "Change Password", "Your new password is : " . $auto_generated_password);

                $this->load->view('authentication/forgotPasswordSuccess');
            } else {
                $this->load->view('authentication/forgotPassword');
            }
        } else {
            $this->load->view('authentication/forgotPassword');
        }
    }

    public function checkEmailAddressExistance() {
        $email_address = $this->input->post($this->security->xss_clean('email_address'));

        $checkEmailAddressExistance = $this->Authentication_model->getAccountByMobileNo($email_address);

        if (count($checkEmailAddressExistance) <= 0) {
            $this->form_validation->set_message('checkEmailAddressExistance', 'Email Address does not exist');
            return false;
        } else {
            return true;
        }
    }

    public function logOut() {
        //User Information 
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('full_name');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('email_address');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('company_id');

        //Shop Information
        $this->session->unset_userdata('outlet_id');
        $this->session->unset_userdata('outlet_name');
        $this->session->unset_userdata('address');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('collect_tax');
        $this->session->unset_userdata('tax_registration_no');
        $this->session->unset_userdata('invoice_print');
        $this->session->unset_userdata('print_select');
        $this->session->unset_userdata('kot_print');

        //company Information
        $this->session->unset_userdata('currency');
        $this->session->unset_userdata('time_zone');
        $this->session->unset_userdata('date_format');

        redirect('Authentication/index');
    }

    public function setting($id = '') {
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {

            $this->form_validation->set_rules('date_format', lang('date_format'), "required|max_length[50]");
            $this->form_validation->set_rules('time_zone', lang('country_time_zone'), "required|max_length[50]");
            $this->form_validation->set_rules('currency',lang('currency'), "required|max_length[50]");
            if ($this->form_validation->run() == TRUE) {
                $org_information = array();
                $org_information['date_format'] = $this->input->post($this->security->xss_clean('date_format'));
                $org_information['time_zone'] = $this->input->post($this->security->xss_clean('time_zone'));
                $org_information['currency'] = $this->input->post($this->security->xss_clean('currency'));
                $org_information['company_id'] = $this->session->userdata('company_id');
 
                $this->Common_model->updateInformation($org_information, $id, "tbl_settings");
                $this->session->set_flashdata('exception', lang('update_success'));
                //set session on update
                $this->session->set_userdata('currency', $org_information['currency']);  
                $this->session->set_userdata('time_zone', $org_information['time_zone']);  
                $this->session->set_userdata('date_format', $org_information['date_format']);  
                redirect('Authentication/setting/'.$org_information['company_id']);
            } else { 
                $data = array();
                $data['setting_information'] = $this->Authentication_model->getSettingInformation($company_id);
                $data['time_zones'] = $this->Common_model->getAllForDropdown("tbl_time_zone");
                $data['main_content'] = $this->load->view('authentication/setting', $data, TRUE);
                $this->load->view('userHome', $data); 
            }
        } else { 
            $data = array();
            $data['setting_information'] = $this->Authentication_model->getSettingInformation($company_id);
            $data['time_zones'] = $this->Common_model->getAllForDropdown("tbl_time_zone");
            $data['main_content'] = $this->load->view('authentication/setting', $data, TRUE);
            $this->load->view('userHome', $data); 
        }
    }

    public function SMSSetting($id='') {
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {

            $this->form_validation->set_rules('email_address',lang('email_address'), "required|valid_email|max_length[50]");
            $this->form_validation->set_rules('password',lang('password'), "required|max_length[50]"); 
            if ($this->form_validation->run() == TRUE) {
                $sms_info = array();
                $sms_info['email_address'] = $this->input->post($this->security->xss_clean('email_address'));
                $sms_info['password'] = $this->input->post($this->security->xss_clean('password')); 
                $sms_info['company_id'] = $this->session->userdata('company_id');
 
                $this->Common_model->updateInformation($sms_info, $id, "tbl_sms_settings");
                $this->session->set_flashdata('exception', lang('update_success')); 
                redirect('Authentication/SMSSetting/'.$sms_info['company_id']);
            } else { 
                $data = array();
                $data['sms_information'] = $this->Authentication_model->getSMSInformation($company_id); 
                $data['main_content'] = $this->load->view('authentication/sms_setting', $data, TRUE);
                $this->load->view('userHome', $data); 
            }
        } else { 
            $data = array();
            $data['sms_information'] = $this->Authentication_model->getSMSInformation($company_id); 
            $data['main_content'] = $this->load->view('authentication/sms_setting', $data, TRUE);
            $this->load->view('userHome', $data); 
        }
    }

    public function whiteLabel($id = '') {
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            /*form validation check*/
            $this->form_validation->set_rules('site_name', lang('site_name'), 'required|max_length[300]');
            $this->form_validation->set_rules('footer', lang('footer'), 'required|max_length[300]');
            $this->form_validation->set_rules('system_logo', lang('logo'), 'callback_validate_system_logo');


            if ($this->form_validation->run() == TRUE) {
                $data = array();
                $data['site_name'] = $this->input->post($this->security->xss_clean('site_name'));
                $data['footer'] = $this->input->post($this->security->xss_clean('footer'));

                if ($_FILES['system_logo']['name'] != "") {
                    $data['system_logo'] = $this->session->userdata('system_logo');;
                    $this->session->unset_userdata('system_logo');
                    @unlink("./assets/images/".$this->input->post($this->security->xss_clean('old_system_logo')));
                }else{
                    $data['system_logo'] = $this->input->post($this->security->xss_clean('old_system_logo'));
                }

                $data['company_id'] = $this->session->userdata('company_id');

                if ($id == "") {
                    $this->Common_model->insertInformation($data, "tbl_settings");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($data, $id, "tbl_settings");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Authentication/whiteLabel');
            } else {
                $data = array();
                $data['getWhiteLabel'] = $this->Authentication_model->getSettingInformation($company_id);
                $data['main_content'] = $this->load->view('authentication/whiteLabel', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['getWhiteLabel'] = $this->Authentication_model->getSettingInformation($company_id);
            $data['main_content'] = $this->load->view('authentication/whiteLabel', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }

    public function changeProfile($id = '') {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $company_id = $this->session->userdata('company_id');
        if ($id != '') {
            $user_details = $this->Common_model->getDataById($id, "tbl_users");
        }

        if ($this->input->post('submit')) {

            if ($id != '') {
                $post_email_address = $this->input->post($this->security->xss_clean('email_address'));
                $existing_email_address = $user_details->email_address;
                if ($post_email_address != $existing_email_address) {
                    $this->form_validation->set_rules('email_address', lang('email_address'), "required|valid_email|max_length[50]|is_unique[tbl_users.email_address]");
                } else {
                    $this->form_validation->set_rules('email_address',lang('email_address'), "required|valid_email|max_length[50]");
                }
            } else {
                $this->form_validation->set_rules('email_address', lang('email_address'), "required|valid_email|max_length[50]|is_unique[tbl_users.email_address]");
            }

            if ($this->form_validation->run() == TRUE) {
                $user_info = array();
                $user_info['full_name'] = $this->input->post($this->security->xss_clean('full_name'));
                $user_info['email_address'] = $this->input->post($this->security->xss_clean('email_address'));
                $user_info['phone'] = $this->input->post($this->security->xss_clean('phone'));
                $this->Common_model->updateInformation($user_info, $id, "tbl_users");
                $this->session->set_flashdata('exception', lang('update_success'));
   
                $this->session->set_userdata('full_name', $user_info['full_name']);  
                $this->session->set_userdata('phone', $user_info['phone']);  
                $this->session->set_userdata('email_address', $user_info['email_address']);  

                redirect('Authentication/changeProfile');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                    $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                    $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
    public function validate_system_logo() {

        if ($_FILES['system_logo']['name'] != "") {
            $config['upload_path'] = './assets/images';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '2048';
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("system_logo")) {
                $upload_info = $this->upload->data();
                $system_logo = $upload_info['file_name'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/images/' . $system_logo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 230;
                $config['height'] = 50;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->session->set_userdata('system_logo', $system_logo);
            } else {
                $this->form_validation->set_message('validate_system_logo', $this->upload->display_errors());
                return FALSE;
            }
        }
    }

    public function setlanguage(){
    $id=$this->session->userdata('user_id');
    $language=$this->input->post('language');
    if ($language == "") {
        $language = "english";
    }
    $data['language']=$language;
    $this->session->set_userdata('language', $language);
    $this->db->WHERE('id',$id);
    $this->db->update('tbl_users',$data);
    redirect($_SERVER["HTTP_REFERER"]);
   }

    /*public function REST_API(){
        $file_pointer = 'assets/REST_API_JSON.json';
        echo "version_file_true";exit;
        if (file_exists($file_pointer)) { 

            $file_content = file_get_contents($file_pointer);
            $json_data = json_decode($file_content, true);

            $installation_date = $json_data['date'];    

            $meta_date = date("Y-m-d", filectime($file_pointer));   

            if ($installation_date != $meta_date) {
                echo str_rot13("Zhfg hfr vafgnyyngvba jvmneq gb vafgnyy guvf fpevcg");  
            }else{
                echo "version_file_true";  
            }

        }else {  
            echo str_rot13("Zhfg hfr vafgnyyngvba jvmneq gb vafgnyy guvf fpevcg");    
        }
    }*/

 

}
