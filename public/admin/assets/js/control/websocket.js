var socket  = new WebSocket('ws://74.63.204.84:8090');
const socket_messages = {
    nextCustomer : {
        "message" : "nextCustomer",
        "branch_id" : elm_branch_id.val()
    },
    switchCustomer : {
        "message" : "switchCustomer",
        "id" : elm_window_id.val()
    },
    ring : {
        "message" : "ring",
        "id" : elm_window_id.val()
    }
}


socket.onmessage = function(e){
    var jsonObject = jQuery.parseJSON(e.data);
    jsonObject = jQuery.parseJSON(jsonObject["message"]);

    if(jsonObject["message"]== "switchCustomer" && jsonObject["id"] == elm_window_id.val()){
        Snackbar.show({
            text: 'A new customer has been passed to you.',
            pos: 'bottom-right'
        });
        loadData();          
    }

    if(jsonObject["message"] == "newCustomer" && jsonObject["id"] == elm_window_id.val()){
        Snackbar.show({
            text: 'A new customer has been entered your queue.',
            pos: 'bottom-right'
        });
        loadData();
    }
    
}
