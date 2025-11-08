//defer


// table methods

HTMLTableRowElement.prototype.validateInputRow = function() {
    let inputs = this.querySelectorAll("input");
    let check = document.getElementById('check' + this.id);

    let size = inputs.length;
    let validatedRow = true;

    for(let i = 1; i < size; i++){
        let valor = inputs[i].value.trim();
        if(inputs[i].classList.contains("text")){
            if(valor === "" || !valor.esNombreValido()) validatedRow = false;
        }
        else if(inputs[i].classList.contains("email")){
            if(valor === "" || !valor.esEmailValido()) validatedRow = false;
        }
    }

    if(validatedRow){
        this.className = "valida"
        check.disabled = false;
    }else{
        this.className = "error";
        check.disabled = true;
    }
};




// Validacion de cadenas

const nombreRegexp = /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+([ '\-][A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+)*$/;

String.prototype.esNombreValido = function() {
    return nombreRegexp.test(this);
};

const emailRegexp = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

String.prototype.esEmailValido = function() {
    return emailRegexp.test(this);
};


