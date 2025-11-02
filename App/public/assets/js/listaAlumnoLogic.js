window.addEventListener("load", function(){

    const tbody = document.querySelector("tbody");
    const btnAdd = document.getElementById("add");

    function crearFilaTabla(id, nombre, apellido, email) {
        
        let fila = document.createElement("tr");
        
        let c1 = document.createElement("td");
        let c2 = document.createElement("td");
        let c3 = document.createElement("td");
        let c4 = document.createElement("td");
        let c5 = document.createElement("td");
        
        c1.innerHTML = id;
        c2.innerHTML = nombre;
        c3.innerHTML = apellido;
        c4.innerHTML = email;
        

        let buttonContainer = document.createElement("div");
        buttonContainer.className = "action-buttons";
        

        let editBtn = document.createElement("button");
        editBtn.innerHTML = "EDIT";
        editBtn.className = "action-btn edit-btn";
        

        let deleteBtn = document.createElement("button");
        deleteBtn.innerHTML = "DELETE";
        deleteBtn.className = "action-btn delete-btn";

        deleteBtn.addEventListener("click", (function(rowToDelete) {
            return function() {
                const c1Content = rowToDelete.cells[0].textContent;
                
                fetch('/API/ApiAlumno.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: c1Content })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        tbody.removeChild(rowToDelete);
                    }else{
                        alert("Error de borrado");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        })(fila));

        editBtn.addEventListener("click", (function(rowToEdit) {
            return function() {
                crearModal();
                const modalContent = document.getElementById("modalContent");
                const titulo = document.createElement("h2");
                titulo.textContent = "Editar Usuario";
                
                const form = document.createElement("form");
                form.id = "editForm";
                
                function crearCampo(labelText, inputType, inputId, required = true) {
                    const div = document.createElement("div");
                    div.className = "form-field";
                    
                    const label = document.createElement("label");
                    label.textContent = labelText;
                    label.htmlFor = inputId;
                    
                    const input = document.createElement("input");
                    input.type = inputType;
                    input.id = inputId;
                    input.name = inputId;
                    input.required = required;
                    
                    const errorDiv = document.createElement("div");
                    errorDiv.className = "error-message";
                    errorDiv.id = `error-${inputId}`;
                    
                    div.appendChild(label);
                    div.appendChild(input);
                    div.appendChild(errorDiv);
                    
                    return div;
                }
                
                const formGrid = document.createElement("div");
                formGrid.className = "form-grid";
                
                const divNombre = crearCampo("Nombre:", "text", "nombre");
                const divApellido = crearCampo("Apellido:", "text", "apellido");
                const divEmail = crearCampo("Email:", "email", "email");

                divNombre.children[1].value = rowToEdit.cells[1].textContent;
                divApellido.children[1].value = rowToEdit.cells[2].textContent;
                divEmail.children[1].value = rowToEdit.cells[3].textContent;
                

                formGrid.appendChild(divNombre);
                formGrid.appendChild(divApellido);
                formGrid.appendChild(divEmail);
                

                const buttonContainer = document.createElement("div");
                buttonContainer.className = "button-container";
                
                const btnConfirmar = document.createElement("button");
                btnConfirmar.type = "submit";
                btnConfirmar.textContent = "Confirmar";
                btnConfirmar.id = "btnConfirmar";
                
                
                buttonContainer.appendChild(btnConfirmar);
                

                form.appendChild(formGrid);
                form.appendChild(buttonContainer);
                
                modalContent.appendChild(titulo);
                modalContent.appendChild(form);
                

                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    let nom = document.getElementById("nombre");
                    let ape = document.getElementById("apellido");
                    let email = document.getElementById("email");

                    fetch('/API/ApiAlumno.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: rowToEdit.cells[0].textContent,
                                                nombre: nom.value,
                                                apellido: ape.value,
                                                email: email.value
                                                })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success){
                            rowToEdit.cells[1].textContent = nom.value;
                            rowToEdit.cells[2].textContent = ape.value;
                            rowToEdit.cells[3].textContent = email.value;

                            window.cerrarModal();
                        }else{
                            alert("Error de edicion");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            };
        })(fila));
        

        buttonContainer.appendChild(deleteBtn);
        buttonContainer.appendChild(editBtn);
        
        c5.appendChild(buttonContainer);
        
        fila.appendChild(c1);
        fila.appendChild(c2);
        fila.appendChild(c3);
        fila.appendChild(c4);
        fila.appendChild(c5);

        return fila;
    }


    fetch('/API/ApiAlumno.php',{
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
    })
        .then((x)=>x.json())
        .then((json)=>pintarTabla(json))

    function pintarTabla(json){
            let size = json.length;

            for(let i = 0; i < size; i++){
                let fila = crearFilaTabla(
                    json[i].id, 
                    json[i].nombre, 
                    json[i].apellido, 
                    json[i].email
                );
                tbody.appendChild(fila);
            }
        }



        btnAdd.addEventListener("click", function(){
        crearModal();
        const modalContent = document.getElementById("modalContent");
        const titulo = document.createElement("h2");
        titulo.textContent = "Registro de Usuario";
        
        const form = document.createElement("form");
        form.id = "registroForm";
        
        function crearCampo(labelText, inputType, inputId, required = false) {
            const div = document.createElement("div");
            div.className = "form-field";
            
            const label = document.createElement("label");
            label.textContent = labelText;
            label.htmlFor = inputId;
            
            const input = document.createElement("input");
            input.type = inputType;
            input.id = inputId;
            input.name = inputId;
            input.required = required;
            

            const errorDiv = document.createElement("div");
            errorDiv.className = "error-message";
            errorDiv.id = `error-${inputId}`;
            
            div.appendChild(label);
            div.appendChild(input);
            div.appendChild(errorDiv);
            
            return div;
        }
        

        const formGrid = document.createElement("div");
        formGrid.className = "form-grid";
        
        const divNombre = crearCampo("Nombre:", "text", "nombre", true);
        const divApellido = crearCampo("Apellido:", "text", "apellido", true);
        const divEmail = crearCampo("Email:", "email", "email", true);
        const divTelefono = crearCampo("Teléfono:", "tel", "telefono", true);
        const divProvincia = crearCampo("Provincia:", "text", "provincia");
        const divLocalidad = crearCampo("Localidad:", "text", "localidad");
        const divDireccion = crearCampo("Dirección:", "text", "direccion");
        

        const btnRegistrar = document.createElement("button");
        btnRegistrar.type = "submit";
        btnRegistrar.textContent = "Registrar Usuario";
        btnRegistrar.id = "btnRegistrar";
        

        formGrid.appendChild(divNombre);
        formGrid.appendChild(divApellido);
        formGrid.appendChild(divEmail);
        formGrid.appendChild(divTelefono);
        formGrid.appendChild(divProvincia);
        formGrid.appendChild(divLocalidad);
        formGrid.appendChild(divDireccion);
        

        form.appendChild(formGrid);
        form.appendChild(btnRegistrar);
        

        modalContent.appendChild(titulo);
        modalContent.appendChild(form);
        

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            let nom = document.getElementById("nombre");
            let ape = document.getElementById("apellido");
            let username = document.getElementById("email");
            let tel = document.getElementById("telefono");
            let prov = document.getElementById("provincia");
            let loc = document.getElementById("localidad");
            let direccion = document.getElementById("direccion");

            fetch('/API/ApiAlumno.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre: nom.value,
                                        apellido: ape.value,
                                        username: username.value,
                                        telefono: tel.value,
                                        provincia: prov.value,
                                        localidad: loc.value,
                                        direccion: direccion.value
                                        })
                })
            .then((response)=>response.json())
            .then((data)=>{
                if(data.success){
                    let nuevoAlumno = data.alumno;
                    
                    let nuevaFila = crearFilaTabla(
                        nuevoAlumno.id, 
                        nuevoAlumno.nombre, 
                        nuevoAlumno.apellido, 
                        nuevoAlumno.email
                    );
                    tbody.appendChild(nuevaFila);

                    window.cerrarModal();
                } else {
                    alert("Error al registrar el alumno.");
                }
            })
            .catch(error => console.error('Error en POST:', error));
        });
    });
});