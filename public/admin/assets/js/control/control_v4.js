var socket  = new WebSocket('ws://127.0.0.1:8090');
var cacheTime = 0;
// 74.63.204.84
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
            if(self.waiting.length < 1 && self.serving == null){
                alertError("Queue is empty!");
            }else{
                // updateState(self.waiting[0]["id"], states.SERVING, function(){
                //     loadData(self);
                // });

                if(self.is_ongoing){
                    alertRevert(function(){
                        stopQueue(self);
                        stopTimer();
                        socket.send(JSON.stringify(socket_messages.nextCustomer));
                    });
                }else{
                    startQueue(self, function(){
                        loadData(self, function(){
                            socket.send(JSON.stringify(socket_messages.nextCustomer));
                            if(window.navigator.onLine){
                                getMessagev2(self.current.id, 0);
                                sendMessageV2(self.current.id, 0, function(res){
                                    if(parseInt(res["status"]) == 1)
                                        createNotificationLog(self.current.id, res["log"]);
                                });
                                getSettingData(self, function(setting_res){
                                    var starting_point = setting_res["starting_point"];
                                    var ending_point = setting_res["ending_point"];
                                    var idx = 1;
                                    for(var i = starting_point - 1; i < self.waiting.length; i++){
                                        console.log("HAHAHA");
                                        getMessagev2(self.waiting[i].id, 0);
                                        sendMessageV2(self.waiting[i].id, 0, function(res){
                                            if(parseInt(res["status"]) == 1)
                                                createNotificationLog(self.waiting[i].id, res["log"]);
                                        });

                                        idx++;

                                        if(idx == ending_point){
                                            break;
                                        }
                                    }
                                });
                                
                            }
                        });
                        
                    });

                    startTimer();
                    alertSuccess("Queue Start", "Ongoing Queue");
                }

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
                stopTimer();
                if(self.waiting.length > 0){
                    nextQueue(self, function(){
                        startQueue(self, function(){
                            loadData(self, function(){
                                swal.close();
                                if(self.current != null){
                                    alertSuccess("Next Customer!", self.current["token"]);
                                }else{
                                    alertSuccess("Good job!", "Success");
                                }
                                if(window.navigator.onLine){
                                    getMessagev2(self.serving.id, 0);
                                    sendMessageV2(self.serving.id, 0, function(res){
                                        if(parseInt(res["status"]) == 1)
                                            createNotificationLog(self.serving.id, res["log"]);
                                    });
                                }
                                startTimer();
                                if(window.navigator.onLine)
                                    autoMessage(self);
                                socket.send(JSON.stringify(socket_messages.nextCustomer));
                            });
                        });
                    });
                }else{
                    nextQueue(self, function(){
                        loadData(self, function(){
                            swal.close();
                            if(self.current != null){
                                alertSuccess("Next Customer!", self.current["token"]);
                            }else{
                                alertSuccess("Good job!", "Success");
                            }
                            if(window.navigator.onLine){
                                getMessagev2(self.serving.id, 0);
                                sendMessageV2(self.serving.id, 0, function(res){
                                    if(parseInt(res["status"]) == 1)
                                        createNotificationLog(self.serving.id, res["log"]);
                                });
                            }
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
                    var currentId = self.current.id;
                    if(window.navigator.onLine){
                        sendMessageDrop(currentId);
                        sendMessagePushDrop(currentId);
                    }
                    if(self.waiting.length > 0){
                        dropQueue(self, function(){
                            startQueue(self, function(){
                                loadData(self, function(){
                                    swal.close();
                                    if(self.current != null){
                                        alertSuccess("Next Customer!", self.current["token"]);
                                    }else{
                                        alertSuccess("Good job!", "Success");
                                    }
                                    if(window.navigator.onLine){
                                        getMessagev2(self.serving.id, 0);
                                        sendMessageV2(self.serving.id, 0, function(res){
                                            if(parseInt(res["status"]) == 1)
                                                createNotificationLog(self.serving.id, res["log"]);
                                        });
                                    }
                                    if(window.navigator.onLine)
                                        autoMessage(self);
                                    socket.send(JSON.stringify(socket_messages.nextCustomer));
                                });
                            });
                        });
                    }else{
                        dropQueue(self, function(){
                            loadData(self, function(){
                                swal.close();
                                if(self.current != null){
                                    alertSuccess("Next Customer!", self.current["token"]);
                                }else{
                                    alertSuccess("Good job!", "Success");
                                }
                                if(window.navigator.onLine){
                                    getMessagev2(self.serving.id, 0);
                                    sendMessageV2(self.serving.id, 0, function(res){
                                        if(parseInt(res["status"]) == 1)
                                            createNotificationLog(self.serving.id, res["log"]);
                                    });
                                }
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
                if(window.navigator.onLine){
                    getMessagev2(self.serving.id, 0);
                    sendMessageV2(self.serving.id, 0, function(res){
                        if(parseInt(res["status"]) == 1)
                            createNotificationLog(self.serving.id, res["log"]);
                    });
                }
                
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
        },
        sample : function(){
            console.log("JOMAR");
        }
    },
    created : function(){
        var self = this;
        loadData(self, function(){
            if(self.is_ongoing){
                startTimer();
            }
        });

        

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
            
            if(jsonObject["message"] == "newCustomer" && jsonObject["branch_id"] == $("#branch_id").val()){
                loadData(self);
                Snackbar.show({
                    text: 'A new customer has been entered your queue.',
                    pos: 'bottom-right'
                });
            }
            

            if(jsonObject["message"] == "nextCustomer" && jsonObject["branch_id"] == self.branch_id){
                loadData(self);
            }
        }

        

    }
});

// NEW SMS NOTIFICATION METHOD
async function sendMessageV2(id, isPrio = 0, callbackmethod=false){
    var params = id + "/" + isPrio.toString();
    var res  = (await axios.get("/api/messaging/send_message/" + params)).data;
    if(callbackmethod != false){
        callbackmethod(res);
    }
}


async function sendMessageDrop(id){
    var res  = (await axios.get("/api/messaging/drop/sms/" + id.toString())).data;
    console.log(res);
    console.log(res);
    if(parseInt(res["status"]) == 1)
        createNotificationLog(id, res["log"]);
}

async function sendMessagePushDrop(id){
    var res  = (await axios.get("/api/messaging/drop/push/" + id.toString())).data;
    if(res["status"] == 1){
        socket.send(JSON.stringify({message : "pushNotif", log : res["log"], transaction_id : id, token : res["token"], datetime : res["datetime"], branch_id: res["branch_id"], service: res["service"]}));
    }
}

async function autoMessage(self){
    var setting_res =  (await axios.get("/api/settings/get/" + self.branch_id.toString())).data;
    var starting_point = setting_res["starting_point"];
    var ending_point = setting_res["ending_point"];
    var idx = 1;
    
    for(var i = starting_point - 1; i < self.waiting.length; i++){
        getMessagev2(self.waiting[i].id, 0);
        sendMessageV2(self.waiting[i].id, 0, function(res){
            if(parseInt(res["status"]) == 1)
                createNotificationLog(self.waiting[i].id, res["log"]);
        });

        idx++;

        if(idx == ending_point){
            break;
        }
    }

}
async function getMessagev2(id, is_prio = 0){
    var params = id.toString() + "/" + is_prio.toString();
    var res  = (await axios.get("/api/messaging/get_notification/" + params)).data;
    // console.log(res);
    if(res["status"] == 1){
        socket.send(JSON.stringify({message : "pushNotif", log : res["log"], transaction_id : id, token : res["token"], datetime : res["datetime"], branch_id: res["branch_id"], service: res["service"]}));
        // createNotificationLogIsPush(id,res["log"]);
    }
}



// DATABASE
async function startQueue(self, callbackmethod = false){
    var params = self.branch_id + "/" + self.window_id;
    var res  = (await axios.get("/api/transactions/start_queue/" + params)).data;

    

    if(callbackmethod != false){
        callbackmethod();
    }
}

async function stopQueue(self){
    var params =  self.window_id;
    var res  = (await axios.get("/api/transactions/stop_queue/" + params)).data;
    console.log(res);
    loadData(self);
}


async function nextQueue(self, callbackmethod = false){
    var params =  self.window_id + "/" + cacheTime.toString();
    var res  = (await axios.get("/api/transactions/next_queue/" + params)).data;
    
    if(callbackmethod != false){
        callbackmethod();
    }
}


async function dropQueue(self, callbackmethod = false){
    var params =  self.window_id;
    var res  = (await axios.get("/api/transactions/drop_queue/" + params)).data;
    
    if(callbackmethod != false){
        callbackmethod();
    }
}


async function createNotificationLog(id, message){
    vals = {
      id : id,
      message : message
    };
    var res  = (await axios.post("/api/notifications/store", vals)).data;
}   

async function createNotificationLogIsPush(id, message){
    vals = {
      id : id,
      message : message,
      is_push : true
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
    var starting_point = setting_res["starting_point"];
    var ending_point = setting_res["ending_point"];
    
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



async function sendMessage(id, is_transfer = 0, callbackmethod=false){
    var params = id.toString() + "/" + is_transfer.toString();
    var res  = (await axios.get("/api/transactions/send_sms/" + params)).data;
    
    if(callbackmethod != false){
        callbackmethod(res);
    }
    
}


async function getSettingData(self, callback){
    var setting_res =  (await axios.get("/api/settings/get/" + self.branch_id.toString())).data;

    callback(setting_res);
}

async function loadData(self,  callbackmethod = false){
    var res = (await axios.get("/api/transactions/get/" + self.branch_id + "/" + self.window_id)).data;
    console.log(res);
    self.transactions = res;
    self.waiting = [];
    self.serving = null;
    self.doneOrDrop = [];
    self.total_success = 0;
    self.total_drop = 0;
    self.switch_options = [];
    
    var waitingCache = [];

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


    for(var i =0; i < self.waiting.length; i++){
        if(self.waiting[i].account != null){
            if(self.waiting[i].account.customer_type == "priority"){
                waitingCache.push(self.waiting[i]);
            }
        }
    }

    for(var i =0; i < self.waiting.length; i++){
        if(self.waiting[i].account != null){
            if(self.waiting[i].account.customer_type == "regular"){
                waitingCache.push(self.waiting[i]);
            }
        }else{
            waitingCache.push(self.waiting[i]);
        }
    }

    self.waiting = waitingCache;
    self.transactions = [];
    
    if(self.serving != null){
        self.current = self.serving;
        self.transactions.push(self.serving);
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
    
    self.transactions =  self.transactions.concat(self.waiting, self.doneOrDrop);
   

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



// TIMER

var time = 0;
var invrl = null;

function startTimer(){
    invrl = setInterval(function(){
        time++;
    }, 1000);
}

function stopTimer(){
    cacheTime = time;
    time = 0;
    clearInterval(invrl);
}

//hotkeys
$(document).keydown(function (event) {
    if (event.altKey && event.keyCode == 49) {
       app.nextq();
    }

    if (event.altKey && event.keyCode == 50) {
        app.dropq();
     }

    if (event.altKey && event.keyCode == 51) {
        app.ringq();
    }

    if (event.altKey && event.keyCode == 52) {
        tourstart();
    }

    if (event.altKey && event.keyCode == 53) {
        $("#listOfCustomer").modal("show");
    }

    if(event.keyCode == 32){
        if($("#listOfCustomer").data('bs.modal')?._isShown){
            $("#listOfCustomer").modal("hide");
        }
    }

    if (event.altKey && event.keyCode == 54) {
        app.startq();
    }
});


var tour = {
id: "app",
steps: [
    {
    title: "Start Control",
    content: "This is the control to start the Queue. Hotkey is alt + 6. ",
    target: document.getElementById("startQueue"),
    placement: "left"
    },
    {
    title: "List Control",
    content: "To see all the customer in your queue click the list or use the hotkey alt + 5. ",
    target: document.getElementById("lst"),
    placement: "left"
    },
    {
    title: "Help Control",
    content: "If you are still not familiar with the hotkeys you can click help button or use the hotkey alt + 4.",
    target: document.getElementById("help"),
    placement: "left"
    },
    {
    title: "Ring Control",
    content: "If customer still not in your service area, you can click the link to trigger the voice of the digital signage tv. Hotkey is alt + 3.",
    target: document.getElementById("ring"),
    placement: "left"
    },
    {
    title: "Drop Control",
    content: "If customer did not appear in the service area for a while, you can drop the customer. Hotkey is alt + 2.",
    target: document.getElementById("dropCustomer"),
    placement: "left"
    },
    {
    title: "Next Control",
    content: "For a successful transaction and to serve the next customer, you can click the next button or use the hotkey alt + 1.",
    target: document.getElementById("nextCustomer"),
    placement: "top"
    }
],
onEnd : function(){
    if($("#is_done_tour").val().toString() == "0"){
        updateTour();
        
    }
}
};


async function updateTour(){
    var params =  $("#window_id").val();
    var res  = (await axios.get("/api/windows/update_tour/" + params)).data;
}

function tourstart(){
    hopscotch.startTour(tour);
}

if($("#is_done_tour").val().toString() == "0"){
    tourstart();    
}


window.addEventListener('offline', function(e) {
    Snackbar.show({
        text: 'SMS & Push notifications cannot be use due to network problem.',
        pos: 'bottom-right'
    });
});
window.addEventListener('online', function(e) {
    Snackbar.show({
        text: 'SMS & Push notifications are now ready.',
        pos: 'bottom-right'
    });
});