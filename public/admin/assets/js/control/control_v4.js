var socket  = new WebSocket('ws://74.63.204.84:8090');
const states = {
    SERVING : "serving",
    WAITING : "waiting",
    DROP : "drop",
    OUT : "out"
};
const socket_messages = {
    nextCustomer : {
        "message" : "nextCustomer",
        "branch_id" : $("#branch_id").val(),
        "window_id" : $("#window_id").val(),
        "name" : $("#window_name").val(),
        "window_order" : $("#window_order").val()
    },

    ring : {
        "message" : "ring",
        "branch_id" : $("#branch_id").val(),
        "window_id" : $("#window_id").val(),
        "name" : $("#window_name").val(),
        "window_order" : $("#window_order").val()
    }
};



var app = new Vue({
    el: '#app',
    data: {
        is_ongoing : false,
        window_id : $("#window_id").val(),
        branch_id : $("#branch_id").val(),
        total_success : 0,
        total_drop : 0,
        transactions : [],
        switch_options : [],
        switch_select_elm : $("#transactions"),
        waiting : [],
        serving : null,
        doneOrDrop : [],
        current : null,
        next : null,
        prev : null
    },
    methods: {
        startq: function(){
            var self = this;
            if(self.waiting.length < 1){
                alertError("Queue is empty!");
            }else{
                updateState(self.waiting[0]["id"], states.SERVING, function(){
                    loadData(self);
                });
                
                alertSuccess("Queue Start", "Ongoing Queue");
                socket.send(JSON.stringify(socket_messages.nextCustomer));
            }
        },
        nextq : function (){
            var self = this;
            
            if(self.waiting.length < 1 && self.serving == null){
                alertError("Queue is empty!");
            }else if(self.serving == null){
                alertError("No customer is being served. Please click the start button to start the queue.");
            }else{
                alertLoader();
                if(self.waiting.length > 0){
                    updateState(self.next["id"], states.SERVING, function(){
                        updateState(self.current["id"], states.OUT, function(){
                            loadData(self, function(){
                                swal.close();
                                if(self.current != null){
                                    alertSuccess("Next Customer!", self.current["token"]);
                                }else{
                                    alertSuccess("Good job!", "Success");
                                }
                                automatedNotification(self);
                                socket.send(JSON.stringify(socket_messages.nextCustomer));
                            });
                        });
                    });
                }else{
                    updateState(self.current["id"], states.OUT, function(){
                        loadData(self, function(){
                            swal.close();
                            if(self.current != null){
                                alertSuccess("Next Customer!", self.current["token"]);
                            }else{
                                alertSuccess("Good job!", "Success");
                            }
                            automatedNotification(self);
                            socket.send(JSON.stringify(socket_messages.nextCustomer));
                        });
                    });
                }
                
                
            }

        },
        dropq : function (){
            var self = this;

            if(self.waiting.length < 1 && self.serving == null){
                alertError("Queue is empty!");
            }else if(self.serving == null){
                alertError("No customer is being served. Please click the start button to start the queue.");
            }else{
                alertRevert(function(){
                    alertLoader();
                    if(self.waiting.length > 0){
                        updateState(self.next["id"], states.SERVING, function(){
                            updateState(self.current["id"], states.DROP, function(){
                                loadData(self, function(){
                                    swal.close();
                                    if(self.current != null){
                                        alertSuccess("Next Customer!", self.current["token"]);
                                    }else{
                                        alertSuccess("Good job!", "Success");
                                    }
                                    automatedNotification(self);
                                    socket.send(JSON.stringify(socket_messages.nextCustomer));
                                });
                            });
                        });
                    }else{
                        updateState(self.current["id"], states.DROP, function(){
                            loadData(self, function(){
                                swal.close();
                                if(self.current != null){
                                    alertSuccess("Next Customer!", self.current["token"]);
                                }else{
                                    alertSuccess("Good job!", "Success");
                                }
                                automatedNotification(self);
                                socket.send(JSON.stringify(socket_messages.nextCustomer));
                            });
                        });
                    }
                    
                });
                
            }

        },
        switchq : function(){
            var self = this;
            var select = $("#transactions").val();
            var send_to = $("#window_select").val();
            if(self.waiting.length < 1 && self.serving == null){
                alertError("Queue is empty!");
            }else if(select == ""){
                alertError("No selected customer!");
            }else{
                alertRevert(function(){
                    switchCustomer(select, send_to, function(response){
                        if(response["success"] == 1){
                            sendMessageMultiple(select);
                            alertSuccess("Switch!", "Succesfuly Switch Customers");
                            loadData(self, function(){
                                if(response["has_serving"] == 1){
                                    alertSuccess("Good job!", "Please press the start again to continue.");
                                }
                                automatedNotification(self);
                            });
                            var message = {
                                "message" : "switchCustomer",
                                "id" :send_to
                            };

                            socket.send(JSON.stringify(message));
                        }else{
                            alertError(response["message"]);
                        }

                        $("#switchModal").modal("hide");
                    });
                });
                
            }

        },
        ringq : function(){
            var self = this;

            if(self.waiting.length < 1 && self.serving == null){
                alertError("Queue is empty!");
            }else if(self.serving == null){
                alertError("No customer is being served. Please click the start button to start the queue.");
            }else{
                sendMessage(self.serving.id, 0);
                alertSuccess("Ring!", "Calling " + self.serving.token);
                socket.send(JSON.stringify(socket_messages.ring));
            }

            
        },
        notify : function(id) {
            var self = this;
            $("#listOfCustomer").modal("hide");
            console.log("PUMASOK NAMAN SYA");
            getMessage(id, 0);
            sendMessage(id,  0, function(status){
                if(parseInt(status["status"]) == 0){
                    alertError(status["message"]);
                }else{
                    createNotificationLog(id, status["log"]);
                    alertSuccess("Message sent", status["message"]);
                }
            });
        }
    },
    created : function(){
        var self = this;
        loadData(self);
        socket.onmessage = function(e){
            var jsonObject = jQuery.parseJSON(e.data);
            jsonObject = jQuery.parseJSON(jsonObject["message"]);
        
            if(jsonObject["message"]== "switchCustomer" && jsonObject["id"] == $("#window_id").val()){
                Snackbar.show({
                    text: 'A new customer has been passed to you.',
                    pos: 'bottom-right'
                });
                loadData(self);          
            }
        
            if(jsonObject["message"] == "newCustomer" && jsonObject["window_id"] == $("#window_id").val()){
                Snackbar.show({
                    text: 'A new customer has been entered your queue.',
                    pos: 'bottom-right'
                });
                loadData(self);
            }
            
        }
    }
});



// DATABASE
async function createNotificationLog(id, message){
    vals = {
      id : id,
      message : message
    };
    var res  = (await axios.post("/api/notifications/store", vals)).data;
}   


async function sendMessageMultiple(ids){
    for(var i = 0; i < ids.length; i++){
        getMessage(ids[i], 1);
        sendMessage(ids[i], 1, function(res){
            if(res["status"] == 1)
                createNotificationLog(ids[i], res["log"]);
        });
    }
}

async function automatedNotification(self){
    var setting_res =  (await axios.get("/api/settings/get/" + self.branch_id.toString())).data;

    if(setting_res["is_automatic_sms"] == 1){
        //PROCEED WITH THE AUTOMATION
        var interval = setting_res["sms_interval"];
        var idx = 1;

        for(var i = 0; i <= self.waiting.length; i++){
            getMessage(self.waiting[i]["id"], 0);
            sendMessage(self.waiting[i]["id"], 0, function(res){
                if(res["status"] == 1)
                    createNotificationLog(ids[i], res["log"]);
            });
            idx++;

            if(idx == interval){
                break;
            }
        }
    }
}

async function getMessage(id, is_transfer = 0){
    var params = id.toString() + "/" + is_transfer.toString();
    var res  = (await axios.get("/api/transactions/get_sms/" + params)).data;

    if(res["status"] == 1){
        socket.send(JSON.stringify({message : "pushNotif", log : res["log"], transaction_id : id}));
    }
}

async function sendMessage(id, is_transfer = 0, callbackmethod=false){
    var params = id.toString() + "/" + is_transfer.toString();
    var res  = (await axios.get("/api/transactions/send_sms/" + params)).data;
    
    if(callbackmethod != false){
        callbackmethod(res);
    }
}


async function loadData(self,  callbackmethod = false){
    var res = (await axios.get("/api/transactions/get/" + self.branch_id + "/" + self.window_id)).data;
    self.transactions = res;
    self.waiting = [];
    self.serving = null;
    self.doneOrDrop = [];
    self.total_success = 0;
    self.total_drop = 0;
    self.switch_options = [];

    for(var i = 0; i < res.length; i++){
        if(res[i].state == "waiting"){
            self.waiting.push(res[i]);
            self.switch_options.push(res[i]);
        }else if(res[i].state == "serving"){
            self.serving = res[i];
        }else{
            self.doneOrDrop.push(res[i]);

            if(res[i].state == "drop"){
                self.total_drop++;
            }else{
                self.total_success++;
            }
        }
    }

    if(self.serving != null){
        self.current = self.serving;
    }else{
        self.current = null;
    }

    if(self.doneOrDrop.length >= 1){
        self.prev = self.doneOrDrop[self.doneOrDrop.length - 1];
    }else{
        self.prev = null;
    }

    if(self.waiting.length >= 1){
        self.next = self.waiting[0];
    }else{
        self.next = null;
    }

    if(self.current != null){
        self.is_ongoing = true;
    }else{
        self.is_ongoing = false;
    }

    if(callbackmethod != false){
        callbackmethod();
    }
}

async function updateState(id, toState, callbackmethod = false){
    var vals = {
        "id" : id,
        "toState" : toState
    };
    await axios.post("/api/transactions/update_state", vals);
    
    if(callbackmethod != false){
        callbackmethod();
    }
}

async function switchCustomer(ids, window_id, callbackmethod = false){
    var url = "/api/transactions/update_holder";
    var vals = {
        "ids" : ids,
        "window_id" : window_id
    };

    var response = (await axios.post(url, vals)).data;

    if(callbackmethod != false){
        callbackmethod(response);
    }
}   



// SWAL FUNCTIONS 
function alertError(message){
    swal({
        type: 'error',
        title: 'Sorry!',
        text: message,
        padding: '2em'
    });
}

function alertSuccess(title, message){
    swal({
        title: title,
        text: message,
        type: "success",
        padding: "2em"
    });
}

function alertRevert(desiredAction){
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        padding: "2em"
    }).then(function(result) {
        if (result.value) {
            desiredAction();
        }
    });
}

async function alertLoader(){
    swal({
        title: "System",
        text: "Processing",
        padding: "2em",
        allowOutsideClick: false,
        onOpen: function () {
          swal.showLoading();
        }
    });
}



