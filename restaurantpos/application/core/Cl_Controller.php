<?php

class Cl_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
        $file_pointer = str_rot13('nffrgf/ERFG_NCV.wfba'); 
        if (file_exists($file_pointer)) { 

            $file_content = file_get_contents($file_pointer);

			$json_data = json_decode($file_content, true);

            $installation_date = $json_data['date'];    

            $meta_date = date("Y-m-d", filectime($file_pointer));  
            
            if ($installation_date != $meta_date) {
            	$this->load->view('waste/REST_API_JSON.php');  
            } 

        }else {  
            $this->load->view('waste/REST_API_JSON.php');     
        }	
	}  
}
