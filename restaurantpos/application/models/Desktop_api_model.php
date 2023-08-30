<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user_model
 *
 * @author user
 */
class Desktop_api_model extends CI_Model {

    public function getAllItemmenus($company_id) {
        $result = $this->db->query("SELECT tbl_food_menus.id, tbl_food_menus.code, tbl_food_menus.name, tbl_food_menus.sale_price, tbl_food_menus.photo, tbl_food_menu_categories.category_name, tbl_vats.percentage
          FROM tbl_food_menus 
          LEFT JOIN tbl_food_menu_categories ON tbl_food_menus.category_id = tbl_food_menu_categories.id
          LEFT JOIN tbl_vats ON tbl_food_menus.vat_id = tbl_vats.id
          WHERE tbl_food_menus.company_id=$company_id AND tbl_food_menus.del_status = 'Live'  
          ORDER BY tbl_food_menus.name ASC")->result();
        return $result;
    }

    public function get_outlet_id_by_code_get($code) {
        $result = $this->db->query("SELECT *
          FROM tbl_outlets
          WHERE outlet_code=$code AND del_status = 'Live'")->row();
        return $result;
    }
    public function get_all_users_by_outlet($outlet_id) {
        $result = $this->db->query("SELECT *
          FROM tbl_users
          WHERE outlet_id=$outlet_id OR id = 1 AND del_status = 'Live'")->result();
        return $result;
    }

}

?>
