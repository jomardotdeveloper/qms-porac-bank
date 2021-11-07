var socket  = new WebSocket('ws://127.0.0.1:8090');

socket.onmessage = function(e){
    console.log(e.data);
    // var jsonObject = jQuery.parseJSON(e.data);
    
    // var keys = Object.keys(jsonObject);
    // console.log(e.data);

    // if(keys.length == 1 && $.isNumeric(keys[0])){
    //     var object = jQuery.parseJSON(jsonObject[keys[0]]);
    //     console.log(object);
    //     if(object["message"] == "switchCustomer" && object["id"] == elm_window_id.val()){
    //         Snackbar.show({
    //             text: 'A new customer has been passed to you.',
    //             pos: 'bottom-right'
    //         });
    //         loadData();
    //         updateCurrentVar();
    //         updateNextVar();
    //         updatePrevVar();
            
    //     }
    // }
    
}


$("#nextCustomer").on("click", function (){
    // console.log("JOMAR");
    socket.send("jomar");   
});