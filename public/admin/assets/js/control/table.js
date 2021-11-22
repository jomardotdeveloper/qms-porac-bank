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
    // var customer_type = opening_d + transaction["account"]["customer_type"] + closing_d;   
    var smsLink = opening_d + getSmsLink(transaction["id"]) + closing_d + closing_tr;
    
    return reference_no + token + status + account_number + full_name + smsLink;
}

function getSmsLink(id){
    return  "<a href='#' title='Notify' onclick='notify( " + id + ")' class='font-20 text-primary'><i class='las la-envelope'></i></a>"
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