
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

var socket  = new WebSocket('ws://127.0.0.1:8090');
    
socket.onmessage = function(e){
    console.log(e.data);
    var jsonObject = jQuery.parseJSON(e.data);

    if(jsonObject["message"]["message"] == "switchCustomer" && jsonObject["message"]["id"] == elm_window_id.val()){
        Snackbar.show({
            text: 'A new customer has been passed to you.',
            pos: 'bottom-right'
        });
        loadData();          
    }
    
    if(jsonObject["message"]["message"] == "newCustomer"){
        Snackbar.show({
            text: 'A new customer has been entered your queue.',
            pos: 'bottom-right'
        });
        loadData();
    }
    
}

function isNumeric(value){
    try{
        parseInt(value);
    }catch(err){
        return false;
    }

    return true;
}


function loadData(){
    var url = "/api/transactions/get/" + elm_branch_id.val() +  "/" + elm_window_id.val();
    $.ajax({
        async : false,
        type: "GET",
        url: url,
        success: function (data) {
            waiting = [];
            serving = null;
            doneOrDrop = [];

            for(var i = 0; i < data.length; i++){
                if(data[i].state == "waiting"){
                    waiting.push(data[i]);
                }else if(data[i].state == "serving"){
                    serving = data[i];
                }else{
                    doneOrDrop.push(data[i]);
                }
            }

            elm_customer_table.find("tbody").html("");
    
            if(serving != null){
                elm_customer_table.find("tbody").append(getTableRow(serving));
            }

            for(var i =0; i < waiting.length; i++){
                elm_customer_table.find("tbody").append(getTableRow(waiting[i]));
            }

            for(var i =0; i < doneOrDrop.length; i++){
                elm_customer_table.find("tbody").append(getTableRow(doneOrDrop[i]));
            }
            
        },
        error: function() { 
            alert(data);
        }
    });
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
    var customer_type = opening_d + transaction["account"]["customer_type"] + closing_d;   
    var smsLink = opening_d + getSmsLink(transaction["id"]) + closing_d + closing_tr;
    
    return reference_no + token + status + account_number + full_name + customer_type + smsLink;
}

function getSmsLink(id){
    return  "<a href='#' title='Notify' onclick='notify( " + id + ")' class='font-20 text-primary'><i class='las la-envelope'></i></a>"
}

function getFullname(account){
    return account["last_name"] + ", " + account["first_name"] +  ", " +account["middle_name"];
}

function notify(id){
    alert(id);
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

function switchCustomer(id, window_id){
    var url = "/api/transactions/update_holder";
    var vals = {
        "id" : id,
        "window_id" : window_id
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

function updateCurrentVar(){
    if(serving != null){
        current = serving;
        updateCurrent();
    }else{
        elm_current_fullname.html("NONE");
        elm_current_token.html("NONE");
        elm_current_account_number.html("NONE");
        elm_current_customer_type.html("NONE");
        elm_current_ref_number.html("NONE");
        elm_current_service.html("NONE");
        elm_current_amount.html("NONE");
        current = null;
    }
}

function updatePrevVar(){
    if(doneOrDrop.length >= 1 ){
        prev = doneOrDrop[doneOrDrop.length - 1];
        updatePrev();
    }else{
        elm_prev_status.html("NONE");
        elm_prev_fulltime.html("NONE");
        elm_prev_token.html("NONE");
        prev = null;
    }
}

function updateNextVar(){
    if(waiting.length >= 1){
        next = waiting[0];
        updateNext();
    }else{
        elm_next_fullname.html("NONE");
        elm_next_token.html("NONE");
        next = null;
    }
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
    if(prev["state"] == "out"){
        elm_prev_status.removeClass();
        elm_prev_status.addClass("badge badge-success");
        elm_prev_status.html("Success");
    }else{
        elm_prev_status.removeClass();
        elm_prev_status.addClass("badge badge-danger");
        elm_prev_status.html("Drop");
    }

    
    elm_prev_fulltime.html(getFullname(prev["account"]));
    elm_prev_token.html(prev["token"]);
}

function updateNext(){
    elm_next_fullname.html(getFullname(next["account"]));
    elm_next_token.html(next["token"]);
}

function updateStartButtonOngoing(){
    elm_start_queue.html("<span class='btn-label' style='background:transparent;'><i class='las la-spinner'></i></span>Ongoing");
    elm_start_queue.attr("disabled", true);
}

function updateStartButtonStart(){
    elm_start_queue.html("<span class='btn-label' style='background:transparent;'><i class='las la-play-circle'></i></span>Start");
    elm_start_queue.attr("disabled", false);
}


function queueIsEmpty(){
    
}

elm_start_queue.on("click", function(){
    if(waiting.length < 1){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{
        update_state(waiting[0]["id"], "serving");
        loadData();

        updateCurrentVar();
        updateNextVar();
        updatePrevVar();
    
        updateStartButtonOngoing();

        swal({
            title: "Queue Start",
            text: "Ongoing Queue",
            type: "success",
            padding: "2em"
        });
        message = {
            "message" : "nextCustomer"
        };

        socket.send(JSON.stringify(message));
    }
});

elm_next_queue.on("click", function () {

    if(waiting.length < 1 && serving == null){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{

        if(waiting.length > 0){
            update_state(next["id"],"serving");
        }
        
        update_state(current["id"],"out");
        loadData();
        updateCurrentVar();
        updateNextVar();
        updatePrevVar();
        
        if(current != null){
            swal({
                title: "Next Customer!",
                text: current["token"],
                type: "success",
                padding: "2em"
            });
        }else{
            swal({
                title: "Good job!",
                text: "Success",
                type: "success",
                padding: "2em"
            });
        }
        
        message = {
            "message" : "nextCustomer"
        };

        socket.send(JSON.stringify(message));
    }
});

elm_switch_queue.on("click", function(){
    if(waiting.length < 1 && serving == null){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Confirm",
            padding: "2em"
        }).then(function(result) {
            if (result.value) {

                if(waiting.length > 0){
                    update_state(next["id"],"serving");
                }
                switchCustomer(current["id"], elm_switch_to.val());
                
                
                swal(
                    "Switch!",
                    "Customer " + current["token"] + " has been switched to " +  elm_switch_to.val(),
                    "success"
                );

                loadData();
                updateCurrentVar();
                updateNextVar();
                updatePrevVar();

                message = {
                    "message" : "switchCustomer",
                    "id" : elm_switch_to.val()
                };

                socket.send(JSON.stringify(message));
                
                if(current != null){
                    swal({
                        title: "Next Customer!",
                        text: current["token"],
                        type: "success",
                        padding: "2em"
                    });
                }

                $("#switchModal").modal("hide");
                
            }
        });
    }
});

elm_drop_queue.on("click", function () {
    // DITO YUNG DROP
    if(waiting.length < 1 && serving == null){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Confirm",
            padding: "2em"
        }).then(function(result) {
            if (result.value) {

                if(waiting.length > 0){
                    update_state(next["id"],"serving");
                }
                
                update_state(current["id"],"drop");
                
                swal(
                    "Drop!",
                    "Customer " + current["token"] + " has been dropped." ,
                    "success"
                );

                loadData();
                updateCurrentVar();
                updateNextVar();
                updatePrevVar();
                
                if(current != null){
                    swal({
                        title: "Next Customer!",
                        text: current["token"],
                        type: "success",
                        padding: "2em"
                    });
                }

                message = {
                    "message" : "nextCustomer"
                };
        
                socket.send(JSON.stringify(message));

                
            }
        });
    }
    
});

elm_ring_queue.on("click", function(){
    message = {
        "message" : "ring"
    };

    socket.send(JSON.stringify(message));
});



function main(){
    loadData();
       
    if(serving == null){
        updateStartButtonStart();
    }else{
        updateStartButtonOngoing();
        updateCurrentVar();
        updateNextVar();
        updatePrevVar();
    }
}



main();