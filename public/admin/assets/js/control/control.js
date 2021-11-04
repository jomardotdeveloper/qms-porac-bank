
// CURRENT ELEMENTS
var elm_current_fullname = $("#cur_fullname");
var elm_current_token = $("#cur_token");
var elm_current_account_number = $("#cur_account_number");
var elm_current_customer_type = $("#cur_customer_type");
var elm_current_ref_number = $("#cur_ref_number");
var elm_current_service = $("#cur_service");
var elm_current_amount = $("#cur_amount");

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


//BUTTONS
var elm_start_queue = $("#startQueue");
var elm_next_queue = $("#nextCustomer");
var elm_drop_queue = $("#dropCustomer");
var elm_switch_queue = $("#switch");


//GLOBALS
var current = null;
var next = null;
var prev = null;

function getTransactions(){
    var url = "/api/transactions/get/" + elm_branch_id.val() +  "/" + elm_window_id.val();
    var all_data =  null;
    $.ajax({
        async : false,
        type: "GET",
        url: url,
        success: function (data) {
            all_data =  data;
        },
        error: function() { 
            alert(data);
        }
    });

    return all_data;
}

function update_state(id, _tostate){
    var url = "/api/transactions/update_state";
    var vals = {
        "id" : id,
        "to_state" : _tostate
    };
    $.ajax({
        async : false,
        type: "POST",
        url: url,
        data : vals,
        success: function (data) {
            return data;
        },
        error: function() { 
            alert(data);
        }
    });
}


function elmLoadCustomerList(){
    var all_transactions = getTransactions();
    elm_customer_table.find("tbody").html("");

    for(var i = 0; i < all_transactions.length; i++){
        elm_customer_table.find("tbody").append(getTableRow(all_transactions[i]));
    }
}

function getTableRow(transaction){
    var opening_tr = "<tr>";
    var closing_tr = "</tr>";


    var opening_d = "<td>";
    var closing_d = "</td>";


    var reference_no = opening_tr + opening_d + transaction["id"] + closing_d;
    var token = opening_d + transaction["token"] + closing_d;
    var status = opening_d + getStatus(transaction) + closing_d;
    var account_number = opening_d + transaction["account"]["account_number"] + closing_d;
    var full_name = opening_d + getFullname(transaction["account"]) + closing_d;
    var customer_type = opening_d + transaction["account"]["customer_type"] + closing_d + closing_tr
    return reference_no + token + status + account_number + full_name + customer_type;
}

function getFullname(account){
    return account["last_name"] + ", " + account["first_name"] +  ", " +account["middle_name"];
}

function getStatus(transaction){
    var badge_danger = "<span class='badge badge-danger'>";
    var badge_success = "<span class='badge badge-success'>";
    var badge_secondary ="<span class='badge badge-secondary'>";
    var badge_warning ="<span class='badge badge-warning'>";
    var closing = "</span>";

    if(transaction["state"] == "waiting"){
        return badge_secondary + "Waiting" + closing;
    }else if(transaction["state"] == "out"){
        return badge_success + "Success" + closing;
    }else if(transaction["state"] == "drop"){
        return badge_danger + "Drop" + closing;
    }else{
        return badge_warning + "Serving" + closing;
    }

}

function getFirstWaiting(all_transactions){
    for(var i = 0; i < all_transactions.length; i++){
        if(all_transactions[i]["state"] == "waiting")
            return all_transactions[i];
    }

    return null;
}

function hasWaiting(all_transactions){
    for(var i = 0; i < all_transactions.length; i++){
        if(all_transactions[i]["state"] == "waiting")
            return true;
    }
    return false;
}

function hasPrevious(all_transactions){
    for(var i = 0; i < all_transactions.length; i++){
        if(all_transactions[i]["state"] == "out" || all_transactions[i]["state"] == "drop")
            return true;
    }
    return false;
}

function getPrevious(all_transactions){
    for(var i = 0; i < all_transactions.length; i++){
        if(all_transactions[i]["state"] == "out" || all_transactions[i]["state"] == "drop")
            return all_transactions[i];
    }
    return null;
}

function queueIsEmpty(){
    var all_transactions = getTransactions();
    if(all_transactions.length < 1){
        return true;
    }else{
        if(!hasWaiting(all_transactions)){
            return true;
        }
    }

    return false;
}

function hasServing(){
    var all_transactions = getTransactions(); 
    for(var i = 0; i < all_transactions.length; i++){
        if(all_transactions[i]["state"] == "serving")
            return true;
    }

    return false;
}

function getServing(){
    var all_transactions = getTransactions(); 
    for(var i = 0; i < all_transactions.length; i++){
        if(all_transactions[i]["state"] == "serving")
            return all_transactions[i];
    }

    return null;
}

elm_start_queue.on("click", function(){

    if(queueIsEmpty()){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{
        swal({
            title: "Queue Start",
            text: "Ongoing Queue",
            type: "success",
            padding: "2em"
        }).then(function(){
            var all_transactions = getTransactions();
            var first = getFirstWaiting(all_transactions);
            update_state(first["id"], "serving");   
        });
    }
    
});


elm_switch_queue.on("click", function(){
    
});

elm_next_queue.on("click", function () {
    var all_transactions = getTransactions();
    if(all_transactions.length < 1 || !hasServing()){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{
        update_state(next["id"],"serving");
        update_state(current["id"],"out");
        vars();
        swal({
            title: "Next Customer!",
            text: current["token"],
            type: "success",
            padding: "2em"
        });
        
    }
    // DITO YUNG NEXT
    

    
});

elm_drop_queue.on("click", function () {
    // DITO YUNG DROP
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        padding: "2em"
    }).then(function(result) {
        if (result.value) {
            swal(
                "Drop!",
                "Customer P001 has been dropped.",
                "success"
            )
        }
    })
});


function updateStartButtonOngoing(){
    elm_start_queue.html("<span class='btn-label' style='background:transparent;'><i class='las la-spinner'></i></span>Ongoing");
    elm_start_queue.attr("disabled", true);
}

function updateStartButtonStart(){
    elm_start_queue.html("<span class='btn-label' style='background:transparent;'><i class='las la-play-circle'></i></span>Start");
    elm_start_queue.attr("disabled", false);
}

function updateCurrent(){
    elm_current_fullname.html(getFullname(current["account"]));
    elm_current_token.html(current["token"]);
    elm_current_account_number.html(current["account"]["account_number"]);
    elm_current_customer_type.html(current["account"]["customer_type"]);
    elm_current_ref_number.html(current["id"]);
    elm_current_service.html(current["service"]["name"]);
    elm_current_amount.html(current["amount"]);
}

function updatePrev(){
    elm_prev_status.html(prev["state"]);
    elm_prev_fulltime.html(getFullname(prev["account"]));
    elm_prev_token.html(prev["token"]);
}

function updateNext(){
    elm_next_fullname.html(getFullname(next["account"]));
    elm_next_token.html(next["token"]);
}

function vars(){
    current = getServing();
    var all = getTransactions();

    updateCurrent();

    if(hasWaiting(all)){
        next = getFirstWaiting(all);
        updateNext();
    }

    if(hasPrevious(all)){
        prev = getPrevious(all);
        updatePrev();
    }
}

//////// INITIAL CODE TO EXECUTE
elmLoadCustomerList();

if(hasServing()){
    updateStartButtonOngoing();
    vars();
}else{
    updateStartButtonStart();
}








