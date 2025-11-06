window.addEventListener("load", function(){

    const tbody = document.querySelector("tbody");
    const btnAdd = document.getElementById("add");
    const btnCarga = document.getElementById("cargaMasiva");
    const barraBusqueda = document.getElementById("buscar");


    function crearEmptyTable(cabecera){
        const divTableC = document.createElement("div");
        divTableC.id = "divTablaC";
        divTableC.className = "form-field"

        size = cabecera.length;

        const tableC = document.createElement("table");

        const theadC = document.createElement("thead");
        theadC.id = "cargaThead";

        for(let i = 0; i<size; i++){
            th = document.createElement("th");
            th.id = cabecera[i] + "Id";
            th.textContent = cabecera[i]
            theadC.appendChild(th);
        }

        const tbodyC = document.createElement("tbody");
        tbodyC.id = "cargaTbody";

        tableC.appendChild(theadC);
        tableC.appendChild(tbodyC);

        divTableC.appendChild(tableC);

        return divTableC;
        
    }

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

                    fetch('/API/ApiAlumno.php?process=edit', {
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

    function populateSelect(selectElement, dataArray) {
            dataArray.forEach(item => {
                const option = document.createElement("option");
                
                option.value = item.id;
            
                option.textContent = item.nombre;

                selectElement.appendChild(option);
            });
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

        btnCarga.addEventListener("click", function(){
            crearModal();

            const modalContent = document.getElementById("modalContent");
            const titulo = document.createElement("h2");
            titulo.textContent = "Carga Masiva de Alumnos";

            const form = document.createElement("form");
            form.id = "cargaMasivaForm";    

            const formGrid = document.createElement("div");
            formGrid.className = "form-gridCarga";
            
            // 1. Campo de Archivo (Input)
            const divFile = crearCampo("Cargar .csv:", "file", "filecsv", true);

            // 2. Select de Familias (envuelto en form-field)
            const divFamilias = document.createElement("div");
            divFamilias.className = "form-field";
            
            const labelFamilias = document.createElement("label");
            labelFamilias.textContent = "Familia:";
            labelFamilias.htmlFor = "selFamilias";
            
            const selFamilias = document.createElement("select");
            selFamilias.id = "selFamilias";
            selFamilias.className = "form-select";

            const defaultOption = document.createElement("option");
            defaultOption.value = "-1";
            defaultOption.textContent = "--- Seleccione Familia ---";
            defaultOption.selected = true;

            selFamilias.appendChild(defaultOption);

            // Span de error para Familia
            const errorFamilia = document.createElement("div");
            errorFamilia.className = "error-message";
            errorFamilia.id = "error-familia";

            fetch('/API/ApiFamilia.php', {
                    method: 'GET'
                })
                .then((x)=>x.json())
                .then((json)=>populateSelect(selFamilias, json));
            
            divFamilias.appendChild(labelFamilias);
            divFamilias.appendChild(selFamilias);
            divFamilias.appendChild(errorFamilia);

            // 3. Botón de Ejemplo
            const btnEjemplo = document.createElement("button");
            btnEjemplo.type = "button";
            btnEjemplo.textContent = "Ejemplo de entrada";
            btnEjemplo.id = "btnEjemplo";

            // 4. Select de Ciclos (Inicialmente oculto, envuelto en form-field)
            const divCiclos = document.createElement("div");
            divCiclos.className = "form-field";
            
            const labelCiclos = document.createElement("label");
            labelCiclos.textContent = "Ciclo:";
            labelCiclos.htmlFor = "selCiclos";
            labelCiclos.style.visibility = 'hidden';
            
            const selCiclos = document.createElement("select");
            selCiclos.id = "selCiclos";
            selCiclos.className = "form-select";
            selCiclos.style.visibility = 'hidden'; 

            const defaultOption1 = document.createElement("option");
            defaultOption1.value = "-1";
            defaultOption1.textContent = "--- Seleccione Ciclo ---";
            defaultOption1.selected = true;

            selCiclos.appendChild(defaultOption1);

            // Span de error para Ciclo
            const errorCiclo = document.createElement("div");
            errorCiclo.className = "error-message";
            errorCiclo.id = "error-ciclo";
            errorCiclo.style.visibility = 'hidden';
            

            selFamilias.addEventListener("change", function(){
                // Limpiar error de familia al cambiar
                errorFamilia.textContent = "";

                fetch('/API/ApiCiclo.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({"id": this.value})
                })
                .then((x)=>x.json())
                .then((json)=>{
                    
                    const primeraOpcion = selCiclos.options[0]; 
                    
                    for (let i = selCiclos.options.length - 1; i > 0; i--) {
                        selCiclos.remove(i); 
                    }

                    selCiclos.innerHTML = '';
                    selCiclos.appendChild(primeraOpcion);
                    
                    populateSelect(selCiclos, json);

                    labelCiclos.style.visibility = "visible";
                    selCiclos.style.visibility = "visible";
                    errorCiclo.style.visibility = "visible";
                })
                .catch(error => {
                    console.error('Error al cargar ciclos:', error);
                    selCiclos.innerHTML = '<option value="-1">Error al cargar</option>';
                    errorCiclo.textContent = "Error al cargar los ciclos. Intente nuevamente.";
                });
            });
            
            divCiclos.appendChild(labelCiclos);
            divCiclos.appendChild(selCiclos);
            divCiclos.appendChild(errorCiclo);

            // Añadir al grid en orden de cuadrícula (2x2)
            formGrid.appendChild(divFile);      
            formGrid.appendChild(divFamilias);  
            formGrid.appendChild(btnEjemplo);   
            formGrid.appendChild(divCiclos);    
            form.appendChild(formGrid);

            // Span de error para validación general (debajo del grid)
            const errorValidacion = document.createElement("div");
            errorValidacion.className = "error-message error-validacion";
            errorValidacion.id = "error-validacion";
            form.appendChild(errorValidacion);

            // --- Contenido de Ejemplo CSV ---

            const ejemploCsvContent = document.createElement('div');
            ejemploCsvContent.id = 'ejemploCsvContent';
            ejemploCsvContent.className = 'csv-example-container';
            ejemploCsvContent.style.display = 'none'; 

            const pTitle = document.createElement('p');
            pTitle.textContent = 'Formato requerido:';
            pTitle.className = 'csv-example-title';
            
            const preContent = document.createElement('pre');
            preContent.textContent = 
        `       Juan,Perez,juan.perez@ejemplo.com
        Maria,Gomez,maria.gomez@ejemplo.com
        Carlos,Rodriguez,carlos.rodriguez@ejemplo.com
        Laura,Fernandez,laura.fernandez@ejemplo.com
        Pedro,Lopez,pedro.lopez@ejemplo.com`;
            
            preContent.className = 'csv-example-code';

            ejemploCsvContent.appendChild(pTitle);
            ejemploCsvContent.appendChild(preContent);

            modalContent.appendChild(titulo);
            modalContent.appendChild(form);
            modalContent.appendChild(ejemploCsvContent); 

    // --- Event Listeners ---

        const fileCSV = document.getElementById("filecsv");
        
        fileCSV.addEventListener("change", function(){
            console.log("Archivo CSV seleccionado.");
            
            const existingTableDiv = document.getElementById("divTablaC");
            if (existingTableDiv) {
                existingTableDiv.remove();
            }
            
            // Limpiar errores previos
            errorValidacion.textContent = "";
            
            const divTablaCarga = crearEmptyTable(["Tick", "Nombre", "Apellido", "Email", "OK"]);
            modalContent.appendChild(divTablaCarga);
            const tbodyC = document.getElementById("cargaTbody");
            const theadC = document.getElementById("cargaThead");
            const thElements = theadC.querySelectorAll("th"); 

            if(this.files.length === 0 || this.files[0].type !== "text/csv"){
                errorValidacion.textContent = "Por favor, introduce un fichero CSV válido.";
                return;
            }
            
            const lector = new FileReader();
            lector.onload = function(){
            let dataCSV = this.result.split("\n").filter(line => line.trim() !== '');
            let csvSize = dataCSV.length;
                
                for(let i = 0; i < csvSize; i++){
                    
                    let fila = document.createElement("tr");
                    fila.id = i;
                    let celdaContent = dataCSV[i].split(",");

                    const nombre = celdaContent[0] ? celdaContent[0].trim() : '';
                    const apellido = celdaContent[1] ? celdaContent[1].trim() : '';
                    const email = celdaContent[2] ? celdaContent[2].trim() : '';

                    for(let j = 0; j < 5; j++){
                        let celda = document.createElement("td");
                        celda.id = i;
                        
                        switch (j) {
                            case 0: 
                                let check = document.createElement("input");
                                check.id = "check" + i;
                                check.type = "checkbox";
                                check.className = "check-table"
                                celda.appendChild(check);
                                break;
                                
                            case 1: // Nombre
                                let inputNombre = document.createElement("input");
                                inputNombre.type = "text";
                                inputNombre.id = "nombre" + i;
                                inputNombre.name = "nombre" + i;
                                inputNombre.value = nombre;
                                inputNombre.className = "tableInput-style text"
                                celda.appendChild(inputNombre);

                                inputNombre.addEventListener("blur", function() {
                                    fila.validateInputRow();
                                });

                                break;
                                
                            case 2: // Apellido
                                let inputApellido = document.createElement("input");
                                inputApellido.type = "text";
                                inputApellido.id = "apellido" + i;
                                inputApellido.name = "apellido" + i;
                                inputApellido.value = apellido;
                                inputApellido.className = "tableInput-style text"
                                celda.appendChild(inputApellido);

                                inputApellido.addEventListener("blur", function() {
                                    fila.validateInputRow();
                                });

                                break;
                                
                            case 3: // Email
                                let inputEmail = document.createElement("input");
                                inputEmail.type = "email";
                                inputEmail.id = "email" + i;
                                inputEmail.name = "email" + i;
                                inputEmail.value = email;
                                inputEmail.className = "tableInput-style email"
                                celda.appendChild(inputEmail);

                                inputEmail.addEventListener("blur", function() {
                                    fila.validateInputRow();
                                });

                                break;
                                
                            case 4: // OK (Span de estado)
                                let span = document.createElement("span");
                                span.id = "span" + i;
                                span.className = "icon";
                                celda.appendChild(span);
                                break;
                        }

                        fila.appendChild(celda);
                    }   
                    tbodyC.appendChild(fila);
                    fila.validateInputRow();
                }

                cargarDatos = document.createElement("button");
                cargarDatos.id = "btnCargaDatos";
                cargarDatos.textContent = "CARGAR";
                modalContent.appendChild(cargarDatos);

                // Span de error para resultados de carga
                const errorCarga = document.createElement("div");
                errorCarga.className = "error-message error-carga-resultado";
                errorCarga.id = "error-carga-resultado";
                modalContent.appendChild(errorCarga);

                btnCargaDatos.addEventListener("click", function(){
                                const alumnosCargar = [];
                                const filasCheck = Array.from(tbodyC.rows);
                                const cicloId = document.getElementById("selCiclos").value;
                                
                                // Limpiar errores previos
                                errorFamilia.textContent = "";
                                errorCiclo.textContent = "";
                                errorValidacion.textContent = "";
                                errorCarga.textContent = "";
                                
                                let alumnosMarcados = 0;
                                for (let i = 0; i < filasCheck.length; i++) {
                                    if (document.getElementById('check' + i).checked) {
                                        let obj = {};
                                        let claves = ["nombre", "apellido", "email", "cicloId"];
                                        let valores = [
                                            document.getElementById('nombre' + i).value,
                                            document.getElementById('apellido' + i).value,
                                            document.getElementById('email' + i).value,
                                            cicloId
                                        ];

                                        alumnosMarcados++;

                                        for (let j = 0; j < claves.length; j++) {
                                            obj[claves[j]] = valores[j];
                                        }
                                        alumnosCargar.push(obj);
                                    }
                                }
                                
                                // Validaciones con mensajes en spans
                                if (!selFamilias.value || selFamilias.value === "-1") {
                                    errorFamilia.textContent = "Debe seleccionar una familia.";
                                    return;
                                }
                                
                                if (!selCiclos.value || selCiclos.value === "-1") {
                                    errorCiclo.textContent = "Debe seleccionar un ciclo.";
                                    return;
                                }
                                
                                if (alumnosMarcados === 0) {
                                    errorValidacion.textContent = "Debe seleccionar al menos un alumno para cargar.";
                                    return;
                                }

                                fetch('/API/ApiAlumno.php?process=saveAll', {
                                    method: 'PUT',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify(alumnosCargar)
                                })
                                .then(response => response.json())
                                .then(data => {

                                    const emailsGuardados = [];
                                    for (let i = 0; i < data.guardados.length; i++) {
                                        emailsGuardados.push(data.guardados[i].email);
                                    }
                                    const emailsErrores = data.errores;

                                    data.guardados.forEach(alumno => {
                                        let fila = crearFilaTabla(
                                            alumno.id,
                                            alumno.nombre,
                                            alumno.apellido,
                                            alumno.email
                                        );
                                        tbody.appendChild(fila);
                                    });

                                    const filasTabla = Array.from(tbodyC.rows);

                                    filasTabla.forEach((fila, index) => {
                                        const emailInput = fila.querySelector('input[type="email"]');
                                        const checkbox = fila.querySelector('input[type="checkbox"]');
                                        
                                        if (emailInput && checkbox && checkbox.checked) {
                                            const email = emailInput.value;
                                            
                                            if (emailsGuardados.includes(email)) {
                                                fila.className = "saved"
                                                checkbox.parentNode.removeChild(checkbox);

                                            } else if (emailsErrores.includes(email)) {
                                                fila.className = "emailDupe"
                                                checkbox.disabled = true;
                                            }
                                        }
                                    });
                                    
                                    // Mostrar resultados en span
                                    if (data.errores.length > 0) {
                                        errorCarga.textContent = `Se guardaron ${data.guardados.length} alumnos. ${data.errores.length} emails ya existían: ${emailsErrores.join(', ')}`;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    errorCarga.textContent = 'Error al cargar los alumnos. Por favor, inténtalo de nuevo.';
                                });
                            });
                        }; 

                        lector.readAsText(this.files[0]);
                    }); 

                    btnEjemplo.addEventListener("click", function(){
                        if (ejemploCsvContent.style.display === 'none') {
                            ejemploCsvContent.style.display = 'block';
                        } else {
                            ejemploCsvContent.style.display = 'none';
                        }
                    });
                });




        btnAdd.addEventListener("click", function(){
            crearModal();
            const modalContent = document.getElementById("modalContent");
            const titulo = document.createElement("h2");
            titulo.textContent = "Registro de Usuario";
            
            const form = document.createElement("form");
            form.id = "registroForm";    

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