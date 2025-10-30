window.addEventListener("load", function(){

    const tbody = document.querySelector("tbody");
    const thead = document.querySelector("thead");
    const cabecera = document.querySelector("tr");
    const btnAdd = document.getElementById("add");


    // Rutas actualizadas
    fetch("/mock/alumno.json")
        .then((x)=>x.json())
        .then((json)=>pintarTabla(json))

    function pintarTabla(json){
            console.log(json);

            let size = json.length;

            for(let i = 0; i < size; i++){
            let fila = document.createElement("tr");
            
            // Columnas de datos
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");
            
            c1.innerHTML = json[i].id;
            c2.innerHTML = json[i].nombre;
            c3.innerHTML = json[i].apellidos;
            c4.innerHTML = json[i].email;
            
            // Crear contenedor para los botones
            let buttonContainer = document.createElement("div");
            buttonContainer.className = "action-buttons";
            
            // Botón Editar
            let editBtn = document.createElement("button");
            editBtn.innerHTML = "E";
            editBtn.className = "action-btn edit-btn";
            
            // Botón Eliminar
            let deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "X";
            deleteBtn.className = "action-btn delete-btn";

            deleteBtn.addEventListener("click", (function(rowToDelete) {

                // falta logica de actualizado en la api
                return function() {
                    tbody.removeChild(rowToDelete);
                }
            })(fila));

             editBtn.addEventListener("click", (function(rowToDelete) {
                return function() {
                    crearModal();
                    const modalContent = document.getElementById("modalContent");

                    // Título del modal
                    const titulo = document.createElement("h2");
                    titulo.textContent = "Editar Usuario";

                    // Crear formulario
                    const form = document.createElement("form");
                    form.id = "registroForm";
                    
                    // Función para crear campos
                    function crearCampo(labelText, inputType, inputId, required = true) {
                        const div = document.createElement("div");
                        const label = document.createElement("label");
                        label.textContent = labelText;
                        label.htmlFor = inputId;
                        
                        const input = document.createElement("input");
                        input.type = inputType;
                        input.id = inputId;
                        input.name = inputId;
                        input.required = required;
                        
                        div.appendChild(label);
                        div.appendChild(input);
                        return div;
                    }

                    // Crear campos del formulario
                    const divNombre = crearCampo("Nombre:", "text", "nombre");
                    const divApellido = crearCampo("Apellido:", "text", "apellido");
                    const divEmail = crearCampo("Email:", "email", "email");

                    // Botón Guardar y cancelar
                    const btnConfirmar = document.createElement("button");
                    btnConfirmar.type = "submit";
                    btnConfirmar.textContent = "confirmar";
                    btnConfirmar.id = "btnConfirmar";

                    const btnCancelar = document.createElement("button");
                    btnCancelar.type = "submit";
                    btnCancelar.textContent = "cancelar";
                    btnCancelar.id = "btnCancelar";

                    // Agregar elementos al formulario
                    form.appendChild(divNombre);
                    form.appendChild(divApellido);
                    form.appendChild(divEmail);
                    form.appendChild(btnConfirmar);
                    form.appendChild(btnCancelar);

                    // Agregar al modal
                    modalContent.appendChild(titulo);
                    modalContent.appendChild(form);

                };
            })(fila));
            
            // Añadir botones al contenedor
            buttonContainer.appendChild(deleteBtn);
            buttonContainer.appendChild(editBtn);
            
            // Añadir contenedor a la celda
            c5.appendChild(buttonContainer);
            
            fila.appendChild(c1);
            fila.appendChild(c2);
            fila.appendChild(c3);
            fila.appendChild(c4);
            fila.appendChild(c5);

            tbody.appendChild(fila);
        }

        // Botón añadir alumno (funcionalidad original)
        btnAdd.addEventListener("click", function(){
        crearModal();
        const modalContent = document.getElementById("modalContent");

        // Título del modal
        const titulo = document.createElement("h2");
        titulo.textContent = "Registro de Usuario";

        // Crear formulario
        const form = document.createElement("form");
        form.id = "registroForm";
        
        // Función para crear campos
        function crearCampo(labelText, inputType, inputId, required = true) {
            const div = document.createElement("div");
            const label = document.createElement("label");
            label.textContent = labelText;
            label.htmlFor = inputId;
            
            const input = document.createElement("input");
            input.type = inputType;
            input.id = inputId;
            input.name = inputId;
            input.required = required;
            
            div.appendChild(label);
            div.appendChild(input);
            return div;
        }

        // Crear campos del formulario
        const divNombre = crearCampo("Nombre:", "text", "nombre");
        const divApellido = crearCampo("Apellido:", "text", "apellido");
        const divEmail = crearCampo("Email:", "email", "email");

        // Botón Registrar
        const btnRegistrar = document.createElement("button");
        btnRegistrar.type = "submit";
        btnRegistrar.textContent = "Registrar Usuario";
        btnRegistrar.id = "btnRegistrar";

        // Agregar elementos al formulario
        form.appendChild(divNombre);
        form.appendChild(divApellido);
        form.appendChild(divEmail);
        form.appendChild(btnRegistrar);

        // Agregar al modal
        modalContent.appendChild(titulo);
        modalContent.appendChild(form);

        // Event listener
        form.addEventListener("submit", function(e) {
            
            console.log("Formulario enviado");
        });
    });
    }
});