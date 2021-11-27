
// CURRENT ELEMENTS
var elm_current_fullname = $("#cur_fullname");
var elm_current_token = $("#cur_token");
var elm_current_account_number = $("#cur_account_number");
var elm_current_customer_type = $("#cur_customer_type");
var elm_current_ref_number = $("#cur_ref_number");
var elm_current_service = $("#cur_service");
var elm_current_amount = $("#cur_amount");
var elm_total_success = $("#total_success");
var elm_total_drop  = $("#total_drop");
var elm_multiple_customers  = $("#transactions");

// NEXT ELEMENTS
var elm_next_fullname = $("#next_fullname");
var elm_next_token = $("#next_token");

// PREVIOS ELEMENTS
var elm_prev_status = $("#prev_status");
var elm_prev_fulltime = $("#prev_fulltime");
var elm_prev_token = $("#prev_token");

//TABLE ELEMENTS
var elm_customer_table = $("#listCustomerTable");

//WINDOW AND BRANCH VLAUES
var elm_window_id = $("#window_id");
var elm_branch_id = $("#branch_id");

// SELECT
var elm_switch_to = $("#window_select");

//BUTTONS
var elm_start_queue = $("#startQueue");
var elm_next_queue = $("#nextCustomer");
var elm_drop_queue = $("#dropCustomer");
var elm_switch_queue = $("#switch");
var elm_ring_queue = $("#ring");

var current = null;
var next = null;
var prev = null;

var waiting = [];
var serving = null;
var doneOrDrop = [];
