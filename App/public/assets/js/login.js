window.addEventListener("load", function(){

    const togglePass = this.document.getElementById("toggle-password");

    togglePass.addEventListener("click", function() {
        const password = document.getElementById("password");
        
        if (password.type === "password") {
            password.type = "text";
        } else if (password.type === "text") {
            password.type = "password";
        }
    });

})