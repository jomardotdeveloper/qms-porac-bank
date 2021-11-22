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