function loadData(donecallback = false){
    var url = "/api/transactions/get/" + elm_branch_id.val() +  "/" + elm_window_id.val();
    $.ajax({
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
            // loadSwitchCustomers();
        },
        error: function() { 
            alert(data);
        }
    }).done(function(done){
        if(donecallback != false){
            donecallback();
        }
    });
}

function loadSwitchCustomers(){
    elm_multiple_customers.empty();

    if(serving != null){
        var opening_opt = "<option " + "value='" +  serving["id"]  + "' > " + serving["token"];
        var closing_opt = "</option>"

        elm_multiple_customers.append(opening_opt + closing_opt);
    }


    for(var i =0; i < waiting.length; i++){
        var opening_opt = "<option " + "value='" +  waiting[i]["id"]  + "' > " + waiting[i]["token"];
        var closing_opt = "</option>"

        elm_multiple_customers.append(opening_opt + closing_opt);
    }
}

function update_state(id, _tostate, donecallback = false){
    var url = "/api/transactions/update_state";
    var vals = {
        "id" : id,
        "to_state" : _tostate
    };
    $.ajax({
        type: "POST",
        url: url,
        data : vals,
        success: function (data) {
            return data;
        },
        error: function() { 
            alert(data);
        }
    }).done(function(done){
        if(donecallback != false){
            donecallback();
        }
    });
}

function switchCustomer(id, window_id, donecallback = false){
    var url = "/api/transactions/update_holder";
    var vals = {
        "id" : id,
        "window_id" : window_id
    };
    $.ajax({
        type: "POST",
        url: url,
        data : vals,
        success: function (data) {
            return data;
        },
        error: function() { 
            alert(data);
        }
    }).done(function(done){
        if(donecallback != false){
            donecallback();
        }
    });
}

function notify(id){
    alert(id);
}