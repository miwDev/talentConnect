

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
        check.checked = false;
        check.disabled = true;
    }
};



const table = this.document.getElementById("tablaMain");

HTMLTableElement.prototype.ordenar = function(col) {

    let filas = table.tBodies[0].children;
    let array = Array.from(filas);
    let idColumna = col.id;

    if (idColumna === "ID") {
        array.sort(function(a, b) {
            return ((a.children[0].textContent - 0) - (b.children[0].textContent - 0)) * col.orden;
        });
    } else if (idColumna === "NOMBRE") {
        array.sort(function(a, b) {
            return a.children[1].textContent.localeCompare(b.children[1].textContent) * col.orden;
        });
    } else if (idColumna === "APELLIDO") {
        array.sort(function(a, b) {
            return a.children[2].textContent.localeCompare(b.children[2].textContent) * col.orden;
        });
    } else if (idColumna === "EMAIL") {
        array.sort(function(a, b) {
            return a.children[3].textContent.localeCompare(b.children[3].textContent) * col.orden;
        });
    }

    array.forEach(elem => {
        table.tBodies[0].appendChild(elem);
    });
};


const idSpan = document.getElementById("ID");
idSpan.orden = 1;
const nombreSpan = document.getElementById("NOMBRE");
nombreSpan.orden = 1;
const apellidoSpan = document.getElementById("APELLIDO");
apellidoSpan.orden = 1;
const emailSpan = document.getElementById("EMAIL");
emailSpan.orden = 1;

const allHeaders = [idSpan, nombreSpan, apellidoSpan, emailSpan];

function updateSortIcons(clickedElement) {
    allHeaders.forEach(span => {
        span.classList.remove("arrow-down", "arrow-up");
        span.classList.add("static-arrow");
    });

    clickedElement.classList.remove("static-arrow");

    if (clickedElement.orden === 1) {
        clickedElement.classList.add("arrow-down"); 
    } else {
        clickedElement.classList.add("arrow-up");
    }
}

idSpan.addEventListener("click", function(){
    this.orden *= -1;
    updateSortIcons(this); 
    table.ordenar(this); 
});

nombreSpan.addEventListener("click", function(){
    this.orden *= -1;
    updateSortIcons(this);
    table.ordenar(this);
});

apellidoSpan.addEventListener("click", function(){
    this.orden *= -1;
    updateSortIcons(this);
    table.ordenar(this);
});

emailSpan.addEventListener("click", function(){
    this.orden *= -1;
    updateSortIcons(this);
    table.ordenar(this);
});





