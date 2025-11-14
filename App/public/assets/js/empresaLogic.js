const inputArchivo = document.getElementById("hiddenInput");
const botonFoto = document.getElementById("botonFoto");
const vistaPrevia = document.getElementById("vistaPrevia");
const togglePass = document.getElementById("toggle-password");

togglePass.addEventListener("click", function() {
        const password = document.getElementById("password");
        
        if (password.type === "password") {
            password.type = "text";
        } else if (password.type === "text") {
            password.type = "password";
        }
});

let archivoParaSubir = null;
let previewURLTemporal = null;
        
inputFoto = document.createElement("input");
inputFoto.type = "file";
inputFoto.style.visibility = "hidden";

vistaPrevia.src = "/public/assets/images/genericCompany.svg";
hiddenInput.value = "default";

botonFoto.addEventListener("click", ()=>{
    inputFoto.click();
});
        
inputFoto.addEventListener("change", (event) => {
    const archivo = event.target.files[0];
    if (archivo) {
        archivoParaSubir = archivo; 
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const dataURL = e.target.result; 
            
            vistaPrevia.src = dataURL;
            hiddenInput.value = dataURL;
        };
        reader.readAsDataURL(archivoParaSubir);
    } 
});