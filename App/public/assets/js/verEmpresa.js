window.addEventListener("load", function(){
    const btnActive = this.document.getElementById("active");
    const btnFinalized = this.document.getElementById("finalized");

    btnActive.addEventListener("click", function(){
        btnActive.classList.add('active');
        btnFinalized.classList.remove('active');
    });

    btnFinalized.addEventListener("click", function(){
        btnActive.classList.remove('active');
        btnFinalized.classList.add('active');
    });
})