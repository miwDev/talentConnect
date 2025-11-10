window.addEventListener("load", function(){

    fetch('/API/ApiLogin.php', {
            method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            sessionStorage.setItem("token", data.token);
        }
    })

})