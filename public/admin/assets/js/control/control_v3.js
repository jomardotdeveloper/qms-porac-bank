elm_start_queue.on("click", function(){
    if(waiting.length < 1){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else{
        update_state(waiting[0]["id"], "serving", loadData(function(){
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

            socket.send(JSON.stringify(socket_messages["nextCustomer"]));
        }));
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
    }else if(serving == null){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'No customer is being served. Please click the start button to start the queue.',
            padding: '2em'
        });
    }else{
        if(waiting.length > 0){
            update_state(next["id"],"serving", function(){
                update_state(current["id"],"out", function(){
                    loadData(function(){
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
                        socket.send(JSON.stringify(socket_messages["nextCustomer"]));
                    });
                });
            });
        }else{
            update_state(current["id"],"out", function(){
                loadData(function(){
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
                    updateStartButtonStart();
                    socket.send(JSON.stringify(socket_messages["nextCustomer"]));
                });
            });
        }
    }
});

elm_switch_queue.on("click", function(){
    // alert(elm_multiple_customers.val());
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
                switchCustomer(elm_multiple_customers.val(), elm_switch_to.val(), function(textStatus){
                    var jsonObject = jQuery.parseJSON(textStatus);
                    if(jsonObject["success"] == 0){
                        swal({
                            type: 'error',
                            title: 'Sorry!',
                            text: jsonObject["message"],
                            padding: '2em'
                        });
                    }else{
                        swal(
                            "Switch!",
                            "Succesfuly Switch Customers",
                            "success"
                        );
                        loadData();
                        updateCurrentVar();
                        updateNextVar();
                        updatePrevVar();
                        socket.send(JSON.stringify(socket_messages["switchCustomer"]));
                        if(current != null){
                            swal({
                                title: "Next Customer!",
                                text: current["token"],
                                type: "success",
                                padding: "2em"
                            });
                            socket.send(JSON.stringify(socket_messages["nextCustomer"]));
                        }
                    }
                    $("#switchModal").modal("hide");
                });
                
            }
        });
    }
});

elm_drop_queue.on("click", function () {
    if(waiting.length < 1 && serving == null){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'Queue is empty!',
            padding: '2em'
        });
    }else if(serving == null){
        swal({
            type: 'error',
            title: 'Sorry!',
            text: 'No customer is being served. Please click the start button to start the queue.',
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
                    update_state(next["id"],"serving", function(){
                        update_state(current["id"],"drop", function(){
                            swal(
                                "Drop!",
                                "Customer " + current["token"] + " has been dropped." ,
                                "success"
                            );

                            loadData(function(){
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
                                
                                socket.send(JSON.stringify(socket_messages["nextCustomer"]));
                            });
                        });
                    });
                }else{
                    update_state(current["id"],"drop", function(){
                        swal(
                            "Drop!",
                            "Customer " + current["token"] + " has been dropped." ,
                            "success"
                        );

                        loadData(function(){
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
                            updateStartButtonStart();
                            socket.send(JSON.stringify(socket_messages["nextCustomer"]));
                        });
                    });
                }
            }
        });
    }
    
});

elm_ring_queue.on("click", function(){
    socket.send(JSON.stringify(socket_messages["ring"]));
});

function main(){
    loadData(function(){
        if(serving == null){
            updateStartButtonStart();
        }else{
            updateStartButtonOngoing();
        }

        updateCurrentVar();
        updateNextVar();
        updatePrevVar();
    });
}

main();