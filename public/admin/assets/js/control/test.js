// var socket  = new WebSocket('ws://74.63.204.84:8090');


// socket.onmessage = function(e){
//     alert("NAKANG KATSURA");
// }
// $("#nextCustomer").on("click", function(){
//     socket.send("");
// });


async function getUserAccount()  {
    console.log((await axios.get('/api/transactions/get/5/14')).data);
}

function sample(){
    
}


console.log();
getUserAccount();