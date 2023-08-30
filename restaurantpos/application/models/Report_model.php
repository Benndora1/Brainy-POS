<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Report_model
 *
 * @author user
 */
class Report_model extends CI_Model {

    public function dailySummaryReport($selectedDate) {
        $outlet_id = $this->session->userdata('outlet_id');

        //daily purchases
        $this->db->select('*');
        $this->db->from('tbl_purchase');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $purchases = $this->db->get()->result(); 

        //daily sales
        $this->db->select('*');
        $this->db->from('tbl_sales');
        if ($selectedDate != '') {
            $this->db->where('sale_date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('sale_date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where('order_status', 3);
        $this->db->where("del_status", 'Live');
        $sales = $this->db->get()->result();


        //daily supplier due payments
        $this->db->select('*');
        $this->db->from('tbl_supplier_payments');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id); 
        $this->db->where("del_status", 'Live');
        $supplier_due_payments = $this->db->get()->result();

        //daily customer due receives
        $this->db->select('*');
        $this->db->from('tbl_customer_due_receives');
        if ($selectedDate != '') {
            $this->db->where('only_date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('only_date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id); 
        $this->db->where("del_status", 'Live');
        $customer_due_receives = $this->db->get()->result(); 

        //daily expenses
        $this->db->select('*');
        $this->db->from('tbl_expenses');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id); 
        $this->db->where("del_status", 'Live');
        $expenses = $this->db->get()->result();

        //daily wastes
        $this->db->select('*');
        $this->db->from('tbl_wastes');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id); 
        $this->db->where("del_status", 'Live');
        $wastes = $this->db->get()->result();

        $result = array();
        $result['purchases'] = $purchases;
        $result['sales'] = $sales;
        $result['supplier_due_payments'] = $supplier_due_payments;
        $result['customer_due_receives'] = $customer_due_receives;
        $result['expenses'] = $expenses;
        $result['wastes'] = $wastes; 
         
        return $result;
    }

    public function dailyConsumptionReport($selectedDate) {
        $outlet_id = $this->session->userdata('outlet_id');

        //daily sale consumption of menu
        $this->db->select('*');
        $this->db->from('tbl_sale_consumptions_of_menus');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $sale_consumptions_of_menu = $this->db->get()->result(); 

        //daily sale consumption of menu's modifier
        $this->db->select('*');
        $this->db->from('tbl_sales');
        if ($selectedDate != '') {
            $this->db->where('sale_date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('sale_date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where('order_status', 3);
        $this->db->where("del_status", 'Live');
        $sale_consumption_of_menu_modifier = $this->db->get()->result(); 

        $result = array();
        $result['sale_consumptions_of_menu'] = $sale_consumptions_of_menu;
        $result['sale_consumption_of_menu_modifier'] = $sale_consumption_of_menu_modifier; 
         
        return $result;
    }

    public function todaySummaryReport($selectedDate) {
        $outlet_id = $this->session->userdata('outlet_id');

        //purchase report
        $this->db->select('sum(paid) as total_purchase_amount');
        $this->db->from('tbl_purchase');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $purchase = $this->db->get()->result();
        //end purchase report
        //Sales report
        $this->db->select('sum(paid_amount) as total_sales_amount,sum(vat) as total_sales_vat');
        $this->db->from('tbl_sales');
        if ($selectedDate != '') {
            $this->db->where('sale_date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('sale_date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $sales = $this->db->get()->result();
        //end Sales report
        //Waste report
        $this->db->select('sum(total_loss) as total_loss_amount');
        $this->db->from('tbl_wastes');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $waste = $this->db->get()->result();
        //end Waste report
        //Expense report
        $this->db->select('sum(amount) as expense_amount');
        $this->db->from('tbl_expenses');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $expense = $this->db->get()->result();

        //end expense report
        //Supplier payment report
        $this->db->select('sum(amount) as supplier_payment_amount');
        $this->db->from('tbl_supplier_payments');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $supplier_payment = $this->db->get()->result();
        //end expense report
        //Supplier payment report
        $this->db->select('sum(amount) as customer_receive_amount');
        $this->db->from('tbl_customer_due_receives');
        if ($selectedDate != '') {
            $this->db->where('date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $customer_receive = $this->db->get()->result();
        //end Supplier payment report
        $allTotal = 0;
        $allTotal = $purchase[0]->total_purchase_amount + $sales[0]->total_sales_amount + $waste[0]->total_loss_amount + $expense[0]->expense_amount + $supplier_payment[0]->supplier_payment_amount;
        $result['total_purchase_amount'] = isset($purchase[0]->total_purchase_amount) && $purchase[0]->total_purchase_amount ? $purchase[0]->total_purchase_amount : '0.0';
        $result['total_sales_amount'] = isset($sales[0]->total_sales_amount) && $sales[0]->total_sales_amount ? $sales[0]->total_sales_amount : '0.0';
        $result['total_sales_vat'] = isset($sales[0]->total_sales_vat) && $sales[0]->total_sales_vat ? $sales[0]->total_sales_vat : '0.0';
        $result['total_loss_amount'] = isset($waste[0]->total_loss_amount) && $waste[0]->total_loss_amount ? $waste[0]->total_loss_amount : '0.0';
        $result['expense_amount'] = isset($expense[0]->expense_amount) && $expense[0]->expense_amount ? $expense[0]->expense_amount : '0.0';
        $result['supplier_payment_amount'] = isset($supplier_payment[0]->supplier_payment_amount) && $supplier_payment[0]->supplier_payment_amount ? $supplier_payment[0]->supplier_payment_amount : '0.0';
        $result['customer_receive_amount'] = isset($customer_receive[0]->customer_receive_amount) && $customer_receive[0]->customer_receive_amount ? $customer_receive[0]->customer_receive_amount : '0.0';
        $result['allTotal'] = isset($allTotal) && $allTotal ? $allTotal : '0.0';
        $balance = (($result['total_sales_amount'] + $result['customer_receive_amount']) - ($result['total_purchase_amount'] + $result['supplier_payment_amount'] + $result['expense_amount']));
        $result['balance'] = isset($balance) && $balance ? $balance : '0.0';
        return $result;
    }

    public function dailySummaryReportPaymentMethod($selectedDate) {

        $outlet_id = $this->session->userdata('outlet_id');
        //payment method report
        $this->db->select('sum(total_payable) as total_sales_amount,payment_method_id');
        $this->db->from('tbl_sales');
        if ($selectedDate != '') {
            $this->db->where('sale_date =', $selectedDate);
        } else {
            $today = date('Y-m-d');
            $this->db->where('sale_date =', $today);
        }
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("del_status", 'Live');
        $this->db->group_by('payment_method_id', "DESC");
        $paymentMethod = $this->db->get()->result();
        return $paymentMethod;
        //end purchase report
    }

    public function todaySummeryReport() {

        $outlet_id = $this->session->userdata('outlet_id');
        //payment method report
        $this->db->select('sum(tbl_sales.total_payable) as total_sales_amount, tbl_payment_methods.id,tbl_payment_methods.name');
        $this->db->from('tbl_sales');
        $today = date('Y-m-d');
        $this->db->join('tbl_payment_methods', 'tbl_payment_methods.id = tbl_sales.payment_method_id', 'left');
        $this->db->where('sale_date =', $today);
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where("tbl_sales.del_status", 'Live');
        $this->db->group_by('payment_method_id', "DESC");
        $paymentMethod = $this->db->get()->result();
        return $paymentMethod;
        //end purchase report
    }

    //Inventory Report
    public function getInventory($category_id = "", $ingredient_id = "", $food_id = "") {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        if ($food_id != "") {
            $result = $this->db->query("SELECT ing.*, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
(select category_name from tbl_ingredient_categories where id=ing.category_id AND del_status='Live') category_name,
 (select unit_name from tbl_units where id=ing.unit_id AND del_status='Live') unit_name
FROM tbl_food_menus_ingredients i LEFT JOIN tbl_ingredients ing ON ing.id = i.ingredient_id WHERE i.food_menu_id='$food_id' AND i.company_id= '$company_id' AND i.del_status='Live'")->result();
            return $result;
        } else {
            if ($category_id == "" && $ingredient_id == "") {
                $result = $this->db->query("SELECT i.*,(select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
(select category_name from tbl_ingredient_categories where id=i.category_id AND del_status='Live') category_name,
(select unit_name from tbl_units where id=i.unit_id AND del_status='Live') unit_name
FROM tbl_ingredients i WHERE i.del_status='Live' AND i.company_id= '$company_id' ORDER BY i.name ASC")->result();
                return $result;
            } else {
                if ($ingredient_id == "" && $category_id != "") {
                    $result = $this->db->query("SELECT i.*,(select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
(select category_name from tbl_ingredient_categories where id=i.category_id AND del_status='Live') category_name,
(select unit_name from tbl_units where id=i.unit_id AND del_status='Live') unit_name
FROM tbl_ingredients i WHERE i.category_id='$category_id' AND i.del_status='Live' AND i.company_id= '$company_id' ORDER BY i.name ASC")->result();
                    return $result;
                } else {
                    $result = $this->db->query("SELECT i.*, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
(select category_name from tbl_ingredient_categories where id=i.category_id AND del_status='Live') category_name,
(select unit_name from tbl_units where id=i.unit_id AND del_status='Live') unit_name
FROM tbl_ingredients i WHERE i.id='$ingredient_id' AND i.company_id= '$company_id' AND i.del_status='Live'")->result();
                    return $result;
                }
            }
        }
    }

    public function getInventoryAlertList() {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $result = $this->db->query("SELECT i.*,(select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id='$outlet_id' AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id='$outlet_id' AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients where ingredient_id=i.id AND outlet_id='$outlet_id' AND del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
(select category_name from tbl_ingredient_categories where id=i.category_id AND del_status='Live') category_name,
(select unit_name from tbl_units where id=i.unit_id AND del_status='Live') unit_name
FROM tbl_ingredients i WHERE del_status='Live' AND i.company_id= '$company_id'  ORDER BY i.name ASC")->result();
        return $result;
    }

    //end inventory report
    public function saleReportByMonth($startMonth = '', $endMonth = '', $user_id = '') {
        if ($startMonth || $endMonth || $user_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sale_date,sum(total_payable) as total_payable');
            $this->db->from('tbl_sales');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('sale_date>=', $startMonth);
                $this->db->where('sale_date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('sale_date>=', $startMonth);
                $this->db->where('sale_date <=', $endMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('sale_date>=', $startMonth);
                $this->db->where('sale_date <=', $endMonth);
            }

            if ($user_id != '') {
                $this->db->where('user_id', $user_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->group_by('month(sale_date)');
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function vatReport($startDate = '', $endDate = '') {
        if ($startDate || $endDate):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sale_date,sum(total_payable) as total_payable,sum(vat) as total_vat');
            $this->db->from('tbl_sales');

            if ($startDate != '' && $endDate != '') {
                $this->db->where('sale_date>=', $startDate);
                $this->db->where('sale_date <=', $endDate);
            }
            if ($startDate != '' && $endDate == '') {
                $this->db->where('sale_date', $startDate);
            }
            if ($startDate == '' && $endDate != '') {
                $this->db->where('sale_date', $endDate);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->group_by('date(sale_date)');
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function saleReportByDate($startDate = '', $endDate = '', $user_id = '') {
        if ($startDate || $endDate || $user_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sale_date,sum(total_payable) as total_payable');
            $this->db->from('tbl_sales');

            if ($startDate != '' && $endDate != '') {
                $this->db->where('sale_date>=', $startDate);
                $this->db->where('sale_date <=', $endDate);
            }
            if ($startDate != '' && $endDate == '') {
                $this->db->where('sale_date', $startDate);
            }
            if ($startDate == '' && $endDate != '') {
                $this->db->where('sale_date', $endDate);
            }

            if ($user_id != '') {
                $this->db->where('user_id', $user_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->group_by('date(sale_date)');
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function profitLossReport($start_date, $end_date) {
        if ($start_date || $end_date):
            $outlet_id = $this->session->userdata('outlet_id');

            //purchase report
            $this->db->select('sum(paid) as total_purchase_amount');
            $this->db->from('tbl_purchase');
            if ($start_date != '' && $end_date != '') {
                $this->db->where('date>=', $start_date);
                $this->db->where('date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('date', $end_date);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where("del_status", 'Live');
            $purchase = $this->db->get()->result();
            //end purchase report
            //Sales report
            $this->db->select('sum(paid_amount) as total_sales_amount,sum(vat) as total_sales_vat');
            $this->db->from('tbl_sales');
            if ($start_date != '' && $end_date != '') {
                $this->db->where('sale_date>=', $start_date);
                $this->db->where('sale_date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('sale_date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('sale_date', $end_date);
            }

            $this->db->where('outlet_id', $outlet_id);
            $this->db->where("del_status", 'Live');
            $sales = $this->db->get()->result();
            //end Sales report
            //Waste report
            $this->db->select('sum(total_loss) as total_loss_amount');
            $this->db->from('tbl_wastes');

            if ($start_date != '' && $end_date != '') {
                $this->db->where('date>=', $start_date);
                $this->db->where('date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('date', $end_date);
            }

            $this->db->where('outlet_id', $outlet_id);
            $this->db->where("del_status", 'Live');
            $waste = $this->db->get()->result();
            //end Waste report
            //Expense report
            $this->db->select('sum(amount) as expense_amount');
            $this->db->from('tbl_expenses');
            if ($start_date != '' && $end_date != '') {
                $this->db->where('date>=', $start_date);
                $this->db->where('date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('date', $end_date);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where("del_status", 'Live');
            $expense = $this->db->get()->result();
            //end expense report
            //Supplier payment report
            $this->db->select('sum(amount) as supplier_payment_amount');
            $this->db->from('tbl_supplier_payments');
            if ($start_date != '' && $end_date != '') {
                $this->db->where('date>=', $start_date);
                $this->db->where('date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('date', $end_date);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where("del_status", 'Live');
            $supplier_payment = $this->db->get()->result();
            //end expense report
            //Customer payment report
            $this->db->select('sum(amount) as customer_receive_amount');
            $this->db->from('tbl_customer_due_receives');
            if ($start_date != '' && $end_date != '') {
                $this->db->where('only_date>=', $start_date);
                $this->db->where('only_date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('only_date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('only_date', $end_date);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where("del_status", 'Live');
            $customer_receive = $this->db->get()->result();
            //end Supplier payment report
            $allTotal = 0;
            $allTotal = $purchase[0]->total_purchase_amount + $sales[0]->total_sales_amount + $waste[0]->total_loss_amount + $expense[0]->expense_amount + $supplier_payment[0]->supplier_payment_amount;
            
            $gross_profit = (($sales[0]->total_sales_amount + $customer_receive[0]->customer_receive_amount) - ($purchase[0]->total_purchase_amount + $waste[0]->total_loss_amount + $expense[0]->expense_amount + $supplier_payment[0]->supplier_payment_amount));

            $net_profit = (($sales[0]->total_sales_amount + $customer_receive[0]->customer_receive_amount) - ($purchase[0]->total_purchase_amount + $waste[0]->total_loss_amount + $expense[0]->expense_amount + $supplier_payment[0]->supplier_payment_amount) - $sales[0]->total_sales_vat);

            $result['total_purchase_amount'] = isset($purchase[0]->total_purchase_amount) && $purchase[0]->total_purchase_amount ? $purchase[0]->total_purchase_amount : '0.0';
            $result['total_sales_amount'] = isset($sales[0]->total_sales_amount) && $sales[0]->total_sales_amount ? $sales[0]->total_sales_amount : '0.0';
            $result['total_sales_vat'] = isset($sales[0]->total_sales_vat) && $sales[0]->total_sales_vat ? $sales[0]->total_sales_vat : '0.0';
            $result['total_loss_amount'] = isset($waste[0]->total_loss_amount) && $waste[0]->total_loss_amount ? $waste[0]->total_loss_amount : '0.0';
            $result['expense_amount'] = isset($expense[0]->expense_amount) && $expense[0]->expense_amount ? $expense[0]->expense_amount : '0.0';
            $result['supplier_payment_amount'] = isset($supplier_payment[0]->supplier_payment_amount) && $supplier_payment[0]->supplier_payment_amount ? $supplier_payment[0]->supplier_payment_amount : '0.0';
            $result['customer_receive_amount'] = isset($customer_receive[0]->customer_receive_amount) && $customer_receive[0]->customer_receive_amount ? $customer_receive[0]->customer_receive_amount : '0.0';

            $result['net_profit'] = isset($net_profit) && $net_profit ? $net_profit : '0.0';
            $result['gross_profit'] = isset($gross_profit) && $gross_profit ? $gross_profit : '0.0';
            $result['allTotal'] = isset($allTotal) && $allTotal ? $allTotal : '0.0';
            return $result;
        endif;
    }

    public function supplierReport($startMonth = '', $endMonth = '', $supplier_id = '') {
        if ($startMonth || $endMonth || $supplier_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('date,grand_total,paid,due,reference_no');
            $this->db->from('tbl_purchase');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date', $endMonth);
            }

            if ($supplier_id != '') {
                $this->db->where('supplier_id', $supplier_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function customerReport($startMonth = '', $endMonth = '', $customer_id = '') {
        if ($startMonth || $endMonth || $customer_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sale_date,total_payable,paid_amount,due_amount,sale_no');
            $this->db->from('tbl_sales');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('sale_date>=', $startMonth);
                $this->db->where('sale_date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('sale_date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('sale_date', $endMonth);
            }

            if ($customer_id != '') {
                $this->db->where('customer_id', $customer_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function supplierDuePaymentReport($startMonth = '', $endMonth = '', $supplier_id = '') {
        if ($startMonth || $endMonth || $supplier_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('date,amount,note');
            $this->db->from('tbl_supplier_payments');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date', $endMonth);
            }

            if ($supplier_id != '') {
                $this->db->where('supplier_id', $supplier_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function customerDueReceiveReport($startMonth = '', $endMonth = '', $customer_id = '') {
        if ($startMonth || $endMonth || $customer_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('date,amount,note');
            $this->db->from('tbl_customer_due_receives');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date', $endMonth);
            }

            if ($customer_id != '') {
                $this->db->where('customer_id', $customer_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function foodMenuSales($startMonth = '', $endMonth = '') {
        if ($startMonth || $endMonth || $food_menu_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sum(qty) as totalQty,food_menu_id,menu_name,code,sale_date');
            $this->db->from('tbl_sales_details');
            $this->db->join('tbl_sales', 'tbl_sales.id = tbl_sales_details.sales_id', 'left');
            $this->db->join('tbl_food_menus', 'tbl_food_menus.id = tbl_sales_details.food_menu_id', 'left');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('sale_date>=', $startMonth);
                $this->db->where('sale_date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('sale_date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('sale_date', $endMonth);
            } 
            $this->db->where('tbl_sales_details.outlet_id', $outlet_id);
            $this->db->where('tbl_sales_details.del_status', 'Live');
            $this->db->order_by('code', 'ASC');
            $this->db->group_by('tbl_sales_details.food_menu_id');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function consumptionMenus($start_date = '', $end_date = '') {
        if ($start_date || $end_date):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sum(tbl_sale_consumptions_of_menus.consumption) as total_consumption, ingredient_id,tbl_ingredients.name as ingredient_name,tbl_ingredients.code as ingredient_code');
            $this->db->from('tbl_sales');
            $this->db->join('tbl_sale_consumptions_of_menus', 'tbl_sale_consumptions_of_menus.sales_id = tbl_sales.id', 'inner');
            $this->db->join('tbl_ingredients', 'tbl_ingredients.id = tbl_sale_consumptions_of_menus.ingredient_id', 'inner');

            if ($start_date != '' && $end_date != '') {
                $this->db->where('sale_date>=', $start_date);
                $this->db->where('sale_date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('sale_date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('sale_date', $end_date);
            } 
            $this->db->where('tbl_sales.outlet_id', $outlet_id);
            $this->db->where('tbl_sales.del_status', 'Live');
            $this->db->order_by('tbl_ingredients.name', 'ASC');
            $this->db->group_by('tbl_sale_consumptions_of_menus.ingredient_id');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function consumptionReport($start_date = '', $end_date = '') {
        if ($start_date || $end_date):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sum(consumption) as total_consumption, ingredient_id');
            $this->db->from('tbl_sale_consumptions_of_menus');
            $this->db->join('tbl_sale_consumptions', 'tbl_sale_consumptions.id = tbl_sale_consumptions_of_menus.sale_consumption_id', 'left');
            $this->db->join('tbl_sales', 'tbl_sale_consumptions.sale_id = tbl_sales.id', 'left');
            $this->db->join('tbl_ingredients', 'tbl_ingredients.id = tbl_sale_consumptions_of_menus.ingredient_id', 'left');

            if ($start_date != '' && $end_date != '') {
                $this->db->where('sale_date>=', $start_date);
                $this->db->where('sale_date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('sale_date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('sale_date', $end_date);
            } 
            $this->db->where('tbl_sale_consumptions_of_menus.outlet_id', $outlet_id);
            $this->db->where('tbl_sale_consumptions_of_menus.del_status', 'Live');
            $this->db->order_by('tbl_ingredients.name', 'ASC');
            $this->db->group_by('tbl_sale_consumptions_of_menus.ingredient_id');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function consumptionModifiers($start_date = '', $end_date = '') {
        if ($start_date || $end_date):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sum(tbl_sale_consumptions_of_modifiers_of_menus.consumption) as total_consumption, ingredient_id,tbl_ingredients.name as ingredient_name,tbl_ingredients.code as ingredient_code');
            $this->db->from('tbl_sales');
            $this->db->join('tbl_sale_consumptions_of_modifiers_of_menus', 'tbl_sale_consumptions_of_modifiers_of_menus.sales_id = tbl_sales.id', 'inner');
            $this->db->join('tbl_ingredients', 'tbl_ingredients.id = tbl_sale_consumptions_of_modifiers_of_menus.ingredient_id', 'inner');

            if ($start_date != '' && $end_date != '') {
                $this->db->where('sale_date>=', $start_date);
                $this->db->where('sale_date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('sale_date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('sale_date', $end_date);
            } 
            $this->db->where('tbl_sales.outlet_id', $outlet_id);
            $this->db->where('tbl_sales.del_status', 'Live');
            $this->db->order_by('tbl_ingredients.name', 'ASC');
            $this->db->group_by('tbl_sale_consumptions_of_modifiers_of_menus.ingredient_id');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function detailedSaleReport($startMonth = '', $endMonth = '', $user_id = '') {
        if ($startMonth || $endMonth || $user_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('tbl_sales.*,tbl_users.full_name,tbl_payment_methods.name');
            $this->db->from('tbl_sales');
            $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.user_id', 'left');
            $this->db->join('tbl_payment_methods', 'tbl_payment_methods.id = tbl_sales.payment_method_id', 'left');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('sale_date>=', $startMonth);
                $this->db->where('sale_date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('sale_date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('sale_date', $endMonth);
            }

            if ($user_id != '') {
                $this->db->where('tbl_sales.user_id', $user_id);
            }
            $this->db->where('tbl_sales.outlet_id', $outlet_id);
            $this->db->where('tbl_sales.del_status', 'Live');
            $this->db->order_by('sale_date', 'ASC');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    function getLastDayInDateMonth($month) {
        $returnValue = 0;
        if ($month == "02") {
            $returnValue = "28";
        } elseif ($month == "01" || $month == "03" || $month == "05" || $month == "07" || $month == "08" || $month == "10" || $month == "12") {
            $returnValue = "31";
        } else {
            $returnValue = "30";
        }
        return $returnValue;
    }

    public function purchaseReportByMonth($startMonth = '', $endMonth = '', $user_id = '') {
        if ($startMonth || $endMonth || $user_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('date,sum(grand_total) as total_payable');
            $this->db->from('tbl_purchase');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }

            if ($user_id != '') {
                $this->db->where('user_id', $user_id);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->group_by('month(date)');
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function purchaseReportByDate($startDate = '', $endDate = '') {
        if ($startDate || $endDate):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('*');
            $this->db->from('tbl_purchase');

            if ($startDate != '' && $endDate != '') {
                $this->db->where('date>=', $startDate);
                $this->db->where('date <=', $endDate);
            }
            if ($startDate != '' && $endDate == '') {
                $this->db->where('date', $startDate);
            }
            if ($startDate == '' && $endDate != '') {
                $this->db->where('date', $endDate);
            }
            $this->db->where('outlet_id', $outlet_id);
            $this->db->group_by('date(date)');
            $this->db->where('del_status', "Live");
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function purchaseReportByIngredient($startMonth = '', $endMonth = '', $ingredient_id = '') {
        if ($startMonth || $endMonth || $ingredient_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('sum(quantity_amount) as totalQuantity_amount,ingredient_id,tbl_ingredients.name,tbl_ingredients.code,date');
            $this->db->from('tbl_purchase_ingredients');
            $this->db->join('tbl_purchase', 'tbl_purchase.id = tbl_purchase_ingredients.purchase_id', 'left');
            $this->db->join('tbl_ingredients', 'tbl_ingredients.id = tbl_purchase_ingredients.ingredient_id', 'left');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date', $endMonth);
            }

            if ($ingredient_id != '') {
                $this->db->where('ingredient_id', $ingredient_id);
            }
            $this->db->where('tbl_purchase.outlet_id', $outlet_id);
            $this->db->where('tbl_purchase_ingredients.del_status', 'Live');
            $this->db->order_by('date', 'ASC');
            $this->db->group_by('tbl_purchase_ingredients.ingredient_id');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function detailedPurchaseReport($startMonth = '', $endMonth = '', $user_id = '') {
        if ($startMonth || $endMonth || $user_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('tbl_purchase.*,tbl_users.full_name');
            $this->db->from('tbl_purchase');
            $this->db->join('tbl_users', 'tbl_users.id = tbl_purchase.user_id', 'left');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date', $endMonth);
            }

            if ($user_id != '') {
                $this->db->where('user_id', $user_id);
            }
            $this->db->where('tbl_purchase.outlet_id', $outlet_id);
            $this->db->where('tbl_purchase.del_status', 'Live');
            $this->db->order_by('date', 'ASC');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function wasteReport($startMonth = '', $endMonth = '', $user_id = '') {
        if ($startMonth || $endMonth || $user_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('tbl_wastes.*,emp.full_name as EmployeedName');
            $this->db->from('tbl_wastes');
            $this->db->join('tbl_users as emp', 'emp.id = tbl_wastes.employee_id', 'left');

            if ($startMonth != '' && $endMonth != '') {
                $this->db->where('date>=', $startMonth);
                $this->db->where('date <=', $endMonth);
            }
            if ($startMonth != '' && $endMonth == '') {
                $this->db->where('date', $startMonth);
            }
            if ($startMonth == '' && $endMonth != '') {
                $this->db->where('date', $endMonth);
            }

            // if ($user_id != '') {
            //     $this->db->where('tbl_wastes.user_id', $user_id);
            // }
            if ($user_id != '') {
                $this->db->where('tbl_wastes.employee_id', $user_id);
            }
            $this->db->where('tbl_wastes.outlet_id', $outlet_id);
            $this->db->where('tbl_wastes.del_status', 'Live');
            $this->db->order_by('date', 'ASC');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;
        endif;
    }

    public function supplierDueReport() {
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

    public function customerDueReport() {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('sum(due_amount) as totalDue,customer_id,sale_date,name');
        $this->db->from('tbl_sales');
        $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
        $this->db->order_by('totalDue desc');
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live');
        $this->db->group_by('tbl_sales.customer_id');
        return $this->db->get()->result();
    } 

    public function getRegisterInformation($start_date,$end_date,$user_id,$outlet_id){
        $this->db->select("tbl_register.*,tbl_users.full_name as user_name");
        $this->db->from('tbl_register');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_register.user_id', 'left');
        $this->db->where("tbl_register.user_id", $user_id);
        $this->db->where("tbl_register.outlet_id", $outlet_id);
        $this->db->where("DATE(tbl_register.opening_balance_date_time)>=", $start_date);
        $this->db->where("DATE(tbl_register.opening_balance_date_time)<=", $end_date);
        $this->db->order_by('tbl_register.id', 'DESC');
        return $this->db->get()->result();        
    }

    public function getUsers($outlet_id){
        $this->db->select("*");
        $this->db->from('tbl_users');
        $this->db->where("outlet_id", $outlet_id);
        return $this->db->get()->result();
    }

    public  function expenseReport($startMonth='',$endMonth='',$category_id=''){
        if($startMonth || $endMonth || $category_id):
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('tbl_expenses.*,emp.full_name as EmployeedName,tbl_expense_items.name as categoryName');
        $this->db->from('tbl_expenses');
        $this->db->join('tbl_users as emp', 'emp.id = tbl_expenses.employee_id','left');
        $this->db->join('tbl_expense_items', 'tbl_expense_items.id = tbl_expenses.category_id','left');

        if($startMonth!='' && $endMonth!=''){
            $this->db->where('date>=', $startMonth);
            $this->db->where('date <=', $endMonth);
        }
        if($startMonth!='' && $endMonth==''){
            $this->db->where('date', $startMonth);
        }
        if($startMonth=='' && $endMonth!=''){
            $this->db->where('date', $endMonth);
        }

        if($category_id!=''){
            $this->db->where('tbl_expenses.category_id', $category_id);
        }
        $this->db->where('tbl_expenses.outlet_id',$outlet_id);
        $this->db->where('tbl_expenses.del_status','Live');
        $this->db->order_by('date', 'ASC');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
        endif;
    }

    public  function kitchenPerformanceReport($start_date='',$end_date=''){
        if($start_date || $end_date):
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('*');
        $this->db->from('tbl_sales'); 

        if($start_date!='' && $end_date!=''){
            $this->db->where('sale_date>=', $start_date);
            $this->db->where('sale_date <=', $end_date);
        }
        if($start_date!='' && $end_date==''){
            $this->db->where('sale_date', $start_date);
        }
        if($start_date=='' && $end_date!=''){
            $this->db->where('sale_date', $end_date);
        }

        $this->db->where('tbl_sales.outlet_id',$outlet_id);
        $this->db->where('tbl_sales.del_status','Live');
        $this->db->where('tbl_sales.order_status','3');
        $this->db->order_by('id', 'DESC');
        $query_result = $this->db->get();
        $result = $query_result->result(); 
        return $result;
        endif;
    }


    public function attendanceReport($start_date = '', $end_date = '', $employee_id = '') {
        if ($start_date || $end_date || $employee_id):
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select('tbl_attendance.*, emp.full_name as employee_name');
            $this->db->from('tbl_attendance');
            $this->db->join('tbl_users as emp', 'emp.id = tbl_attendance.employee_id', 'left'); 

            if ($start_date != '' && $end_date != '') {
                $this->db->where('date>=', $start_date);
                $this->db->where('date <=', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $this->db->where('date', $start_date);
            }
            if ($start_date == '' && $end_date != '') {
                $this->db->where('date', $end_date);
            }

            if ($employee_id != '') {
                $this->db->where('tbl_attendance.employee_id', $employee_id);
            } 

            $this->db->where('tbl_attendance.del_status', 'Live');
            $this->db->order_by('date', 'ASC');
            $query_result = $this->db->get();
            $result = $query_result->result();
            return $result;

        endif;
    }

}

