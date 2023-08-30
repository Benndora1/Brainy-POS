<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of common_model
 *
 * @author user
 */
class Common_model extends CI_Model {
    public function isOpenRegister($user_id, $outlet_id){
        $this->db->select('id');
        $this->db->from('tbl_register');
        $this->db->where("DATE(opening_balance_date_time)", date('Y-m-d'));
        $this->db->where("user_id", $user_id);
        $this->db->where("outlet_id", $outlet_id);
        $this->db->where("register_status", 1);
        return $this->db->get()->num_rows();
    }
    public function getPurchaseAmountByUserAndOutletId($user_id, $outlet_id){
        $this->db->select('SUM(paid) as total_purchase_amount');
        $this->db->from('tbl_purchase');
        $this->db->where("DATE(date)", date('Y-m-d'));
        $this->db->where("user_id", $user_id);
        $this->db->where("outlet_id", $outlet_id);
        return $this->db->get()->row();
    }
    public function getAllByTable($table_name) {
        $this->db->select("*");
        $this->db->from($table_name);
        if ($table_name == 'tbl_units') {
            $this->db->order_by('unit_name', 'ASC');
        }
        $this->db->order_by(2, 'ASC');
        $this->db->where("del_status", 'Live');
        return $this->db->get()->result();
    }

    public function getAllByCompanyId($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
        return $result;
    }

    public function getByCompanyId($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY id DESC")->row();
        return $result;
    }

    public function getAllByCompanyIdForDropdown($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY 2")->result();
        return $result;
    }

    public function getAllForDropdown($table_name) {
        $result = $this->db->query("SELECT * 
              FROM $table_name 
              WHERE del_status = 'Live'  
              ORDER BY 2")->result();
        return $result;
    }

    public function getAllByOutletId($outlet_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE outlet_id=$outlet_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
        return $result;
    }

    public function getAllByOutletIdForDropdown($outlet_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE outlet_id=$outlet_id AND del_status = 'Live'  
          ORDER BY 2")->result();
        return $result;
    }

    public function getAllFoodMenusByCategory($category_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE category_id=$category_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
        return $result;
    }

    public function getAllModifierByCompanyId($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY name ASC")->result();
        return $result;
    }

    public function deleteStatusChange($id, $table_name) {
        $this->db->set('del_status', "Deleted");
        $this->db->where('id', $id);
        $this->db->update($table_name);
    }

    public function deleteStatusChangeWithChild($id, $id1, $table_name, $table_name2, $filed_name, $filed_name1) {
        $this->db->set('del_status', "Deleted");
        $this->db->where($filed_name, $id);
        $this->db->update($table_name);

        $this->db->set('del_status', "Deleted");
        $this->db->where($filed_name1, $id1);
        $this->db->update($table_name2);
    }

    public function insertInformation($data, $table_name) {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function getDataById($id, $table_name) {
        $this->db->select("*");
        $this->db->from($table_name);
        $this->db->where("id", $id);
        return $this->db->get()->row();
    }

    public function updateInformation($data, $id, $table_name) {
        $this->db->where('id', $id);
        $this->db->update($table_name, $data);
    }

    public function updateInformationByCompanyId($data, $company_id, $table_name) {
        $this->db->where('company_id', $company_id);
        $this->db->update($table_name, $data);
    }

    public function deletingMultipleFormData($field_name, $primary_table_id, $table_name) {
        $this->db->delete($table_name, array($field_name => $primary_table_id));
    }

    public function getAllCustomers() {
        return $this->db->get("tbl_customers")->result();
    }

    /* public function getAllSaleSuspends() {
      return $this->db->get("tbl_sale_suspends")->result();
      } */

    public function getPurchasePaidAmount($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $ppaid = $this->db->query("SELECT IFNULL(SUM(p.paid),0) as ppaid
        FROM tbl_purchase p  
        WHERE p.outlet_id=$outlet_id AND p.del_status = 'Live'
        AND p.date LIKE '$month%' ")->row('ppaid');
        return $ppaid;
    }

    public function getPurchaseAmount($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalPurchase = $this->db->query("SELECT IFNULL(SUM(p.grand_total),0) as totalPurchase
        FROM tbl_purchase p  
        WHERE p.outlet_id=$outlet_id AND p.del_status = 'Live'
        AND p.date LIKE '$month%' ")->row('totalPurchase');
        return $totalPurchase;
    }

    public function getSupplierPaidAmount($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $partypaid = $this->db->query("SELECT IFNULL(SUM(p.amount),0) as partypaid
        FROM tbl_supplier_payments p  
        WHERE p.outlet_id=$outlet_id AND p.del_status = 'Live'
        AND p.date LIKE '$month%' ")->row('partypaid');
        return $partypaid;
    }

    public function getSalePaidAmount($month, $payment_method_id = FALSE) {
        $outlet_id = $this->session->userdata('outlet_id');
        $condition = " ";
        if ($payment_method_id != FALSE) {
            $condition = " AND s.payment_method_id=$payment_method_id";
        }
        $totalSale = $this->db->query("SELECT IFNULL(SUM(s.total_payable),0) as totalSale
        FROM tbl_sales s  
        WHERE s.outlet_id=$outlet_id AND s.del_status = 'Live'
        AND s.sale_date LIKE '$month%' $condition")->row('totalSale');
        return $totalSale;
    }
    public function getMenuByMenuName($menu_name){
      $this->db->select("*");
      $this->db->from('tbl_food_menus');
      $this->db->where("tbl_food_menus.name", $menu_name);
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->row();      
    }

    public function getIngredientByIngredientName($menu_name){
      $this->db->select("*");
      $this->db->from('tbl_ingredients');
      $this->db->where("tbl_ingredients.name", $menu_name);
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->row();      
    }

    public function getSaleVat($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalSaleVat = $this->db->query("SELECT IFNULL(SUM(s.vat),0) as totalSaleVat
        FROM tbl_sales s  
        WHERE s.outlet_id=$outlet_id AND s.del_status = 'Live'
        AND s.sale_date LIKE '$month%'")->row('totalSaleVat');
        return $totalSaleVat;
    }

    public function getWaste($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalWaste = $this->db->query("SELECT IFNULL(SUM(w.total_loss),0) as totalWaste
        FROM tbl_wastes w  
        WHERE w.outlet_id=$outlet_id AND w.del_status = 'Live'
        AND w.date LIKE '$month%'")->row('totalWaste');
        return $totalWaste;
    }

    public function getExpense($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalExpense = $this->db->query("SELECT IFNULL(SUM(w.amount),0) as totalExpense
        FROM tbl_expenses w  
        WHERE w.outlet_id=$outlet_id AND w.del_status = 'Live'
        AND w.date LIKE '$month%'")->row('totalExpense');
        return $totalExpense;
    }

    public function currentInventory() {
        /* print('<Pre>');
          print_r($this->session->userdata());exit; */
        $company_id = $this->session->userdata('company_id');
        $outlet_id = $this->session->userdata('outlet_id');

        $result = $this->db->query("SELECT i.*,(select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
                (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
                (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
                (select category_name from tbl_ingredient_categories where id=i.category_id  AND del_status='Live') category_name,
                (select unit_name from tbl_units where id=i.unit_id AND del_status='Live') unit_name
                FROM tbl_ingredients i WHERE i.del_status='Live' AND i.company_id= '$company_id' ORDER BY i.name ASC")->result();
        $grandTotal = 0;
        foreach ($result as $value) {
            $totalStock = $value->total_purchase - $value->total_consumption - $value->total_waste;
            if ($totalStock >= 0) {
                $grandTotal = $grandTotal + $totalStock * getLastPurchaseAmount($value->id);
            }
        }
        return $grandTotal;
    }

    public function top_ten_food_menu($start_date, $end_date) {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('sum(qty) as totalQty,food_menu_id,menu_name,sale_date');
        $this->db->from('tbl_sales_details');
        $this->db->join('tbl_sales', 'tbl_sales.id = tbl_sales_details.sales_id', 'left');
        $this->db->where('sale_date>=', $start_date);
        $this->db->where('sale_date <=', $end_date);
        $this->db->order_by('totalQty desc');
        $this->db->where('tbl_sales_details.outlet_id', $outlet_id);
        $this->db->where('tbl_sales_details.del_status', 'Live');
        $this->db->group_by('food_menu_id');
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    public function top_ten_supplier_payable() {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('sum(due) as totalDue,supplier_id,date,name');
        $this->db->from('tbl_purchase');
        $this->db->join('tbl_suppliers', 'tbl_suppliers.id = tbl_purchase.supplier_id', 'left');
        $this->db->order_by('totalDue desc');
        $this->db->where('tbl_purchase.outlet_id', $outlet_id);
        $this->db->where('tbl_purchase.del_status', 'Live');
        $this->db->group_by('tbl_purchase.supplier_id');
        return $this->db->get()->result();
    }

    public function getPayableAmountBySupplierId($id) {
        $this->load->model('Report_model', 'Report_model');
        $month = date('Y-m');
        $monthOnly = date('m', strtotime($month));
        $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($monthOnly);
        $temp = $month . '-' . $finalDayByMonth;
        $start_date = $month . '-' . '01';
        $end_date = $temp;
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('sum(amount) as totalPayment,supplier_id,date');
        $this->db->from('tbl_supplier_payments');
        $this->db->where('date>=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where('supplier_id', $id);
        $this->db->where('del_status', 'Live');
        $this->db->group_by('supplier_id');
        $result = $this->db->get()->row();
        if (!empty($result)) {
            return $result->totalPayment;
        } else {
            return 0.0;
        }
    }

    public function comparison_sale_report($start_date, $end_date) {
        $outlet_id = $this->session->userdata('outlet_id');
        $query = $this->db->query("select year(sale_date) as year, month(sale_date) as month, sum(total_payable) as total_amount from tbl_sales WHERE `sale_date` BETWEEN '$start_date' AND '$end_date' AND outlet_id='$outlet_id' group by year(sale_date), month(sale_date)");
        return $query->row();
    }

    public function setDefaultTimezone() {
        $this->db->select("time_zone");
        $this->db->from('tbl_settings'); 
        $this->db->where('company_id', $this->session->userdata('company_id'));
        $zoneName = $this->db->get()->row();
        if ($zoneName)
            date_default_timezone_set($zoneName->time_zone);
    }

    function get_row($table_name, $where_param, $select_param, $group = "", $limit = "") {
        if (!empty($select_param))
            $this->db->select($select_param);
        if (!empty($where_param))
            $this->db->where($where_param);
        $this->db->group_by($group);
        if (!empty($limit))
            $this->db->limit($limit);
        $result = $this->db->get($table_name);
        return $result->result();
    }

    function get_row_array($table_name, $where_param, $select_param, $group = "", $limit = "", $order_by = false, $order_value = false) {
        if (!empty($select_param))
            $this->db->select($select_param);
        if (!empty($where_param))
            $this->db->where($where_param);
        if (!empty($group))
            $this->db->group_by($group);
        if (!empty($order_by))
            $this->db->order_by($order_by, $order_value);
        if (!empty($limit))
            $this->db->limit($limit);
        $result = $this->db->get($table_name);
        return $result->result_array();
    }

    function customeQuery($sql) {
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    public function qcode_function($code,$level='S',$size=2){       
            $this->load->library('ci_qr_code');
            $this->config->load('qr_code');
            $qr_code_config = array(); 
            $qr_code_config['cacheable']    = $this->config->item('cacheable');
            $qr_code_config['cachedir']     = $this->config->item('cachedir');
            $qr_code_config['imagedir']     = $this->config->item('imagedir');
            $qr_code_config['errorlog']     = $this->config->item('errorlog');
            $qr_code_config['ciqrcodelib']  = $this->config->item('ciqrcodelib');
            $qr_code_config['quality']      = $this->config->item('quality');
            $qr_code_config['size']         = $this->config->item('size');
            $qr_code_config['black']        = $this->config->item('black');
            $qr_code_config['white']        = $this->config->item('white');
            $this->ci_qr_code->initialize($qr_code_config);
            $image_name =$code.'.png';
            $params['data'] = $code;
            $params['level'] = 'S';
            $params['size'] =3;
            $params['savename'] = FCPATH.$qr_code_config['imagedir'].$image_name;
            $this->ci_qr_code->generate($params); 
            $qr_code_image_url = base_url().$qr_code_config['imagedir'].$image_name;
            return $qr_code_image_url;
    }

}

?>
